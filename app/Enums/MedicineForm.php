<?php

namespace App\Enums;

enum MedicineForm: string
{
    case Tablet = 'tablet';
    case Capsule = 'capsule';
    case Syrup = 'syrup';
    case Injection = 'injection';
    case Cream = 'cream';
    case Drops = 'drops';
    case Inhaler = 'inhaler';
    case Other = 'other';
}
