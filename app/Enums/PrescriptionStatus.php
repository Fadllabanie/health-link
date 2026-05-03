<?php

namespace App\Enums;

enum PrescriptionStatus: string
{
    case Pending = 'pending';
    case PartiallyDispensed = 'partially_dispensed';
    case Dispensed = 'dispensed';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
}
