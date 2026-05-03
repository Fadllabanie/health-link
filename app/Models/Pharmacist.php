<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pharmacist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'pharmacy_id', 'license_number',
        'license_expires_at', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'license_expires_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
