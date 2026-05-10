<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class QrCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'qrable_type', 'qrable_id', 'image_path',
        'scan_count', 'last_scanned_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'last_scanned_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'scan_count' => 'integer',
        ];
    }

    public function qrable(): MorphTo
    {
        return $this->morphTo();
    }

    public function regenerate(): static
    {
        $this->update([
            'code' => Str::random(64),
            'is_active' => true,
            'scan_count' => 0,
            'last_scanned_at' => null,
        ]);

        return $this;
    }

    public function incrementScan(): void
    {
        $this->increment('scan_count');
        $this->update(['last_scanned_at' => now()]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
