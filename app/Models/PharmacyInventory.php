<?php

namespace App\Models;

use App\Enums\InventoryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PharmacyInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pharmacy_id', 'medicine_id', 'batch_number', 'quantity_in_stock',
        'reorder_level', 'unit_cost', 'selling_price',
        'manufacturing_date', 'expiry_date', 'supplier', 'location', 'status',
    ];

    protected function casts(): array
    {
        return [
            'manufacturing_date' => 'date',
            'expiry_date' => 'date',
            'unit_cost' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'status' => InventoryStatus::class,
        ];
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
