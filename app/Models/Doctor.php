<?php

namespace App\Models;

use App\Enums\DoctorStatus;
use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Doctor extends Model implements AuditableContract
{
    use BelongsToHospital, HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'user_id', 'hospital_id', 'department_id', 'primary_specialty_id',
        'license_number', 'license_expires_at', 'qualifications',
        'years_of_experience', 'bio', 'consultation_fee', 'signature',
        'is_available', 'rating', 'total_reviews', 'status', 'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'license_expires_at' => 'date',
            'joined_at' => 'date',
            'rating' => 'decimal:2',
            'consultation_fee' => 'decimal:2',
            'status' => DoctorStatus::class,
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->user?->full_name ?? '';
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->attributes['is_available']
            && $this->status === DoctorStatus::Active;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function primarySpecialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class, 'primary_specialty_id');
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialties');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
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
