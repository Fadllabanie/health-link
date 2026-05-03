<?php

namespace App\Enums;

enum AppointmentType: string
{
    case InPerson = 'in_person';
    case Video = 'video';
    case Phone = 'phone';
}
