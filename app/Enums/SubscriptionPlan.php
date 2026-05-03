<?php

namespace App\Enums;

enum SubscriptionPlan: string
{
    case Free = 'free';
    case Basic = 'basic';
    case Premium = 'premium';
    case Enterprise = 'enterprise';
}
