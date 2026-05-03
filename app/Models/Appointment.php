<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid', 'appointment_number', 'patient_id', 'doctor_id',
        'hospital_id', 'department_id', 'scheduled_at', 'duration_minutes',
        'type', 'reason', 'status', 'cancellation_reason', 'fee',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'type' => AppointmentType::class,
            'status' => AppointmentStatus::class,
            'fee' => 'decimal:2',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn (Appointment $appointment) => $appointment->uuid ??= Str::uuid()->toString());
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
