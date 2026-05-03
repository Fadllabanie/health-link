<?php

namespace App\Enums;

enum InventoryStatus: string
{
    case Available = 'available';
    case LowStock = 'low_stock';
    case OutOfStock = 'out_of_stock';
    case Expired = 'expired';
}
