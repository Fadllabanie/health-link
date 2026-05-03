<?php

namespace App\Models;

use App\Enums\BloodType;
use App\Enums\MaritalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Patient extends Model implements AuditableContract
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $patient): void {
            if (empty($patient->medical_record_number)) {
                $patient->medical_record_number = static::generateMrn($patient->hospital_id);
            }
        });
    }

    private static function generateMrn(?int $hospitalId): string
    {
        $prefix = $hospitalId ? "MRN-{$hospitalId}" : 'MRN';
        $sequence = static::withTrashed()
            ->when($hospitalId, fn ($q) => $q->where('hospital_id', $hospitalId))
            ->count() + 1;

        return $prefix.'-'.str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
    }

    protected $fillable = [
        'user_id', 'hospital_id', 'qr_code_id', 'city_id',
        'medical_record_number', 'blood_type', 'height_cm', 'weight_kg',
        'allergies', 'chronic_conditions', 'current_medications',
        'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
        'insurance_provider', 'insurance_policy_number',
        'marital_status', 'occupation',
    ];

    protected function casts(): array
    {
        return [
            'blood_type' => BloodType::class,
            'marital_status' => MaritalStatus::class,
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function primaryHospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    public function hospitals(): BelongsToMany
    {
        return $this->belongsToMany(Hospital::class, 'patient_hospitals')->withPivot('registered_at');
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
