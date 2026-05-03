<?php

namespace App\Traits;

use App\Models\Hospital;
use App\Scopes\HospitalScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToHospital
{
    public static function bootBelongsToHospital(): void
    {
        static::addGlobalScope(new HospitalScope);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }
}
