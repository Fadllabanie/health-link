<?php

namespace App\Services;

use App\Enums\InventoryStatus;
use App\Enums\StockMovementType;
use App\Models\Pharmacy;
use App\Models\PharmacyInventory;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Add a new inventory record and record a purchase stock movement.
     *
     * @param  array<string, mixed>  $data
     */
    public function addStock(Pharmacy $pharmacy, array $data, User $performer): PharmacyInventory
    {
        return DB::transaction(function () use ($pharmacy, $data, $performer): PharmacyInventory {
            $status = $this->resolveStatus(
                (int) $data['quantity'],
                (int) $data['reorder_level'],
                $data['expiry_date']
            );

            $item = PharmacyInventory::create([
                'pharmacy_id' => $pharmacy->id,
                'medicine_id' => $data['medicine_id'],
                'batch_number' => $data['batch_number'],
                'quantity_in_stock' => $data['quantity'],
                'reorder_level' => $data['reorder_level'],
                'unit_cost' => $data['unit_cost'],
                'selling_price' => $data['selling_price'],
                'manufacturing_date' => $data['manufacturing_date'] ?? null,
                'expiry_date' => $data['expiry_date'],
                'supplier' => $data['supplier'] ?? null,
                'location' => $data['location'] ?? null,
                'status' => $status,
            ]);

            StockMovement::create([
                'pharmacy_inventory_id' => $item->id,
                'type' => StockMovementType::Purchase,
                'quantity' => $data['quantity'],
                'unit_price' => $data['unit_cost'],
                'notes' => 'إضافة مخزون أولي',
                'performed_by' => $performer->id,
            ]);

            return $item;
        });
    }

    /**
     * Update editable fields and record an adjustment if quantity changed.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateStock(PharmacyInventory $item, array $data, User $performer): PharmacyInventory
    {
        return DB::transaction(function () use ($item, $data, $performer): PharmacyInventory {
            $oldQuantity = (int) $item->quantity_in_stock;
            $newQuantity = (int) $data['quantity'];

            $status = $this->resolveStatus($newQuantity, (int) $data['reorder_level'], $item->expiry_date);

            $item->update([
                'quantity_in_stock' => $newQuantity,
                'selling_price' => $data['selling_price'],
                'reorder_level' => $data['reorder_level'],
                'location' => $data['location'] ?? null,
                'status' => $status,
            ]);

            if ($oldQuantity !== $newQuantity) {
                $diff = $newQuantity - $oldQuantity;

                StockMovement::create([
                    'pharmacy_inventory_id' => $item->id,
                    'type' => StockMovementType::Adjustment,
                    'quantity' => abs($diff),
                    'unit_price' => $item->selling_price,
                    'notes' => $diff > 0 ? "زيادة {$diff}" : 'تخفيض '.abs($diff),
                    'performed_by' => $performer->id,
                ]);
            }

            return $item->fresh();
        });
    }

    /**
     * Mark expired and low-stock items for a pharmacy.
     */
    public function updateStatuses(Pharmacy $pharmacy): void
    {
        // Mark expired
        PharmacyInventory::where('pharmacy_id', $pharmacy->id)
            ->where('expiry_date', '<', now()->toDateString())
            ->where('status', '!=', InventoryStatus::Expired)
            ->update(['status' => InventoryStatus::Expired]);

        // Mark out-of-stock
        PharmacyInventory::where('pharmacy_id', $pharmacy->id)
            ->where('quantity_in_stock', 0)
            ->where('status', '!=', InventoryStatus::Expired)
            ->update(['status' => InventoryStatus::OutOfStock]);

        // Mark low stock (quantity > 0 but <= reorder_level)
        PharmacyInventory::where('pharmacy_id', $pharmacy->id)
            ->whereColumn('quantity_in_stock', '<=', 'reorder_level')
            ->where('quantity_in_stock', '>', 0)
            ->where('status', '!=', InventoryStatus::Expired)
            ->update(['status' => InventoryStatus::LowStock]);

        // Mark available (quantity > reorder_level)
        PharmacyInventory::where('pharmacy_id', $pharmacy->id)
            ->whereColumn('quantity_in_stock', '>', 'reorder_level')
            ->where('expiry_date', '>=', now()->toDateString())
            ->update(['status' => InventoryStatus::Available]);
    }

    private function resolveStatus(int $quantity, int $reorderLevel, mixed $expiryDate): InventoryStatus
    {
        $expiry = $expiryDate instanceof Carbon ? $expiryDate : Carbon::parse($expiryDate);

        if ($expiry->isPast()) {
            return InventoryStatus::Expired;
        }

        if ($quantity === 0) {
            return InventoryStatus::OutOfStock;
        }

        if ($quantity <= $reorderLevel) {
            return InventoryStatus::LowStock;
        }

        return InventoryStatus::Available;
    }
}
