<?php

namespace App\Models;

use App\Enums\PharmacyStatus;
use App\Enums\PharmacyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pharmacy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid', 'hospital_id', 'name', 'slug', 'license_number',
        'email', 'phone', 'country_id', 'city_id', 'address',
        'latitude', 'longitude', 'logo', 'type', 'is_24_hours',
        'opening_time', 'closing_time', 'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => PharmacyType::class,
            'status' => PharmacyStatus::class,
            'is_24_hours' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn (Pharmacy $pharmacy) => $pharmacy->uuid ??= Str::uuid()->toString());
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function pharmacists(): HasMany
    {
        return $this->hasMany(Pharmacist::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(PharmacyInventory::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
}
