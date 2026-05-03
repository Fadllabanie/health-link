<?php

namespace App\Models;

use App\Enums\MedicineForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Medicine extends Model implements AuditableContract
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'name', 'generic_name', 'brand_name', 'barcode', 'category_id',
        'manufacturer', 'form', 'strength', 'unit', 'description',
        'side_effects', 'contraindications', 'dosage_instructions',
        'requires_prescription', 'is_controlled', 'image', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'form' => MedicineForm::class,
            'requires_prescription' => 'boolean',
            'is_controlled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

    public function prescriptionItems(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(PharmacyInventory::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
