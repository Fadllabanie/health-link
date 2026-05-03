<?php

namespace App\Enums;

enum PharmacyType: string
{
    case InHospital = 'in_hospital';
    case External = 'external';
    case Chain = 'chain';
}
