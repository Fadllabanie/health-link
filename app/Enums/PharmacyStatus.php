<?php

namespace App\Enums;

enum PharmacyStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
}
