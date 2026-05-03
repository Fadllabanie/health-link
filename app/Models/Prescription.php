<?php

namespace App\Models;

use App\Enums\PrescriptionStatus;
use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Prescription extends Model implements AuditableContract
{
    use BelongsToHospital, HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'uuid', 'prescription_number', 'medical_record_id',
        'patient_id', 'doctor_id', 'hospital_id', 'pharmacy_id',
        'issued_at', 'valid_until', 'notes', 'diagnosis_summary',
        'status', 'dispensed_at', 'dispensed_by', 'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'dispensed_at' => 'datetime',
            'valid_until' => 'date',
            'status' => PrescriptionStatus::class,
            'total_amount' => 'decimal:2',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Prescription $prescription): void {
            $prescription->uuid ??= Str::uuid()->toString();
            if (empty($prescription->prescription_number)) {
                $prefix = 'RX-'.($prescription->hospital_id ?? '0');
                $seq = static::withTrashed()
                    ->when($prescription->hospital_id, fn ($q) => $q->where('hospital_id', $prescription->hospital_id))
                    ->count() + 1;
                $prescription->prescription_number = $prefix.'-'.str_pad((string) $seq, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function dispensedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }
}
