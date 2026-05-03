<?php

namespace App\Enums;

enum VisitType: string
{
    case Consultation = 'consultation';
    case FollowUp = 'follow_up';
    case Emergency = 'emergency';
    case Surgery = 'surgery';
    case Checkup = 'checkup';
}
