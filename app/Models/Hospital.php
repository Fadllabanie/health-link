<?php

namespace App\Models;

use App\Enums\HospitalStatus;
use App\Enums\SubscriptionPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Hospital extends Model implements AuditableContract
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'uuid', 'name', 'slug', 'license_number', 'email', 'phone', 'alternate_phone',
        'country_id', 'city_id', 'address', 'latitude', 'longitude',
        'logo', 'website', 'description', 'established_date', 'bed_capacity',
        'subscription_plan', 'subscription_expires_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'established_date' => 'date',
            'subscription_expires_at' => 'datetime',
            'subscription_plan' => SubscriptionPlan::class,
            'status' => HospitalStatus::class,
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Hospital $hospital) {
            $hospital->uuid ??= Str::uuid()->toString();
            $hospital->slug ??= Str::slug($hospital->name).'-'.Str::random(6);
        });
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function pharmacies(): HasMany
    {
        return $this->hasMany(Pharmacy::class);
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

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'hospital_specialties');
    }

    public function registeredPatients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'patient_hospitals')->withPivot('registered_at');
    }

    public function admins(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            UserRole::class,
            'hospital_id',
            'id',
            'id',
            'user_id'
        )->whereHas('roles', fn ($q) => $q->where('name', 'hospital_admin'));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', HospitalStatus::Active);
    }

    public function scopeBySubscription(Builder $query, SubscriptionPlan|string $plan): Builder
    {
        return $query->where('subscription_plan', $plan);
    }
}
