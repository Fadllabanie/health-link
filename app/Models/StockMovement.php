<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_inventory_id', 'type', 'quantity',
        'reference_type', 'reference_id', 'unit_price', 'notes', 'performed_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
            'unit_price' => 'decimal:2',
        ];
    }

    public function pharmacyInventory(): BelongsTo
    {
        return $this->belongsTo(PharmacyInventory::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo('reference');
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
