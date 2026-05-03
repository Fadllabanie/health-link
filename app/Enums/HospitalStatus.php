<?php

namespace App\Enums;

enum HospitalStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
}
