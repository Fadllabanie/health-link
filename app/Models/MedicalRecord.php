<?php

namespace App\Models;

use App\Enums\RecordStatus;
use App\Enums\VisitType;
use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MedicalRecord extends Model implements AuditableContract
{
    use BelongsToHospital, HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'uuid', 'patient_id', 'doctor_id', 'hospital_id',
        'visit_date', 'visit_type', 'notes', 'diagnosis', 'status',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'datetime',
            'visit_type' => VisitType::class,
            'status' => RecordStatus::class,
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn (MedicalRecord $record) => $record->uuid ??= Str::uuid()->toString());
    }

    public function canBeEdited(): bool
    {
        if ($this->status === RecordStatus::Draft) {
            return true;
        }

        return $this->updated_at->diffInHours(now()) <= 24;
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

    public function attachments(): HasMany
    {
        return $this->hasMany(MedicalRecordAttachment::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
}
