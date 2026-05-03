<?php

namespace App\Services;

use App\Enums\PrescriptionStatus;
use App\Enums\StockMovementType;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Pharmacy;
use App\Models\PharmacyInventory;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PrescriptionService
{
    public function __construct(private QrCodeService $qrCodeService) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $items
     */
    public function create(Doctor $doctor, Patient $patient, array $data, array $items): Prescription
    {
        if (empty($items)) {
            throw new \InvalidArgumentException(__('prescriptions.at_least_one_item'));
        }

        return DB::transaction(function () use ($doctor, $patient, $data, $items): Prescription {
            $rxNumber = 'RX-'.strtoupper(Str::substr((string) $doctor->hospital_id, 0, 4))
                .'-'.date('Ymd')
                .'-'.str_pad(
                    (string) (Prescription::withTrashed()->where('hospital_id', $doctor->hospital_id)->count() + 1),
                    5, '0', STR_PAD_LEFT
                );

            $rx = Prescription::create([
                'prescription_number' => $rxNumber,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'hospital_id' => $doctor->hospital_id,
                'medical_record_id' => $data['medical_record_id'] ?? null,
                'issued_at' => now(),
                'valid_until' => $data['valid_until'] ?? null,
                'notes' => $data['notes'] ?? null,
                'diagnosis_summary' => $data['diagnosis_summary'] ?? null,
                'status' => PrescriptionStatus::Pending,
            ]);

            $total = 0;
            foreach ($items as $item) {
                $rxItem = PrescriptionItem::create([
                    'prescription_id' => $rx->id,
                    'medicine_id' => $item['medicine_id'],
                    'dosage' => $item['dosage'] ?? null,
                    'frequency' => $item['frequency'] ?? null,
                    'duration_days' => $item['duration_days'] ?? null,
                    'quantity' => $item['quantity'],
                    'route' => $item['route'] ?? null,
                    'instructions' => $item['instructions'] ?? null,
                    'unit_price' => $item['unit_price'] ?? 0,
                    'total_price' => ($item['unit_price'] ?? 0) * $item['quantity'],
                ]);
                $total += $rxItem->total_price;
            }

            $rx->update(['total_amount' => $total]);

            $this->qrCodeService->generateForPrescription($rx);

            return $rx->load('items.medicine', 'patient', 'doctor');
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $items
     */
    public function update(Prescription $rx, array $data, array $items): Prescription
    {
        if ($rx->status !== PrescriptionStatus::Pending) {
            throw new \RuntimeException(__('prescriptions.cannot_edit_dispensed'));
        }

        if (empty($items)) {
            throw new \InvalidArgumentException(__('prescriptions.at_least_one_item'));
        }

        return DB::transaction(function () use ($rx, $data, $items): Prescription {
            $rx->update([
                'medical_record_id' => $data['medical_record_id'] ?? $rx->medical_record_id,
                'valid_until' => $data['valid_until'] ?? $rx->valid_until,
                'notes' => $data['notes'] ?? null,
                'diagnosis_summary' => $data['diagnosis_summary'] ?? null,
            ]);

            $rx->items()->delete();

            $total = 0;
            foreach ($items as $item) {
                $rxItem = PrescriptionItem::create([
                    'prescription_id' => $rx->id,
                    'medicine_id' => $item['medicine_id'],
                    'dosage' => $item['dosage'] ?? null,
                    'frequency' => $item['frequency'] ?? null,
                    'duration_days' => $item['duration_days'] ?? null,
                    'quantity' => $item['quantity'],
                    'route' => $item['route'] ?? null,
                    'instructions' => $item['instructions'] ?? null,
                    'unit_price' => $item['unit_price'] ?? 0,
                    'total_price' => ($item['unit_price'] ?? 0) * $item['quantity'],
                ]);
                $total += $rxItem->total_price;
            }

            $rx->update(['total_amount' => $total]);

            return $rx->fresh('items.medicine');
        });
    }

    public function cancel(Prescription $rx, string $reason): Prescription
    {
        if ($rx->status === PrescriptionStatus::Dispensed) {
            throw new \RuntimeException(__('prescriptions.cannot_cancel_dispensed'));
        }

        $rx->update([
            'status' => PrescriptionStatus::Cancelled,
            'notes' => trim(($rx->notes ?? '')."\n".__('prescriptions.cancellation_reason').': '.$reason),
        ]);

        return $rx->fresh();
    }

    /**
     * @param  array<int, array{item_id: int, quantity: int}>  $itemQuantities
     */
    public function dispense(Prescription $rx, Pharmacy $pharmacy, int $dispensedById, array $itemQuantities): Prescription
    {
        if ($rx->status === PrescriptionStatus::Dispensed) {
            throw new \RuntimeException(__('prescriptions.already_dispensed'));
        }
        if ($rx->status === PrescriptionStatus::Cancelled) {
            throw new \RuntimeException(__('prescriptions.cannot_dispense_cancelled'));
        }

        return DB::transaction(function () use ($rx, $pharmacy, $dispensedById, $itemQuantities): Prescription {
            $allDispensed = true;

            foreach ($itemQuantities as $entry) {
                /** @var PrescriptionItem $item */
                $item = PrescriptionItem::findOrFail($entry['item_id']);
                $qtyToDispense = (int) $entry['quantity'];

                if ($qtyToDispense <= 0) {
                    continue;
                }

                $inventory = PharmacyInventory::where('pharmacy_id', $pharmacy->id)
                    ->where('medicine_id', $item->medicine_id)
                    ->where('quantity_in_stock', '>=', $qtyToDispense)
                    ->orderBy('expiry_date')
                    ->firstOrFail();

                $inventory->decrement('quantity_in_stock', $qtyToDispense);

                StockMovement::create([
                    'pharmacy_inventory_id' => $inventory->id,
                    'type' => StockMovementType::Sale,
                    'quantity' => $qtyToDispense,
                    'reference_type' => Prescription::class,
                    'reference_id' => $rx->id,
                    'performed_by' => $dispensedById,
                    'notes' => 'Dispensed via prescription '.$rx->prescription_number,
                ]);

                $item->update([
                    'quantity_dispensed' => $qtyToDispense,
                    'is_dispensed' => true,
                ]);

                if ($qtyToDispense < $item->quantity) {
                    $allDispensed = false;
                }
            }

            $status = $allDispensed ? PrescriptionStatus::Dispensed : PrescriptionStatus::PartiallyDispensed;

            $rx->update([
                'status' => $status,
                'pharmacy_id' => $pharmacy->id,
                'dispensed_by' => $dispensedById,
                'dispensed_at' => now(),
            ]);

            return $rx->fresh('items.medicine');
        });
    }
}
