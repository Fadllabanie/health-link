<?php

namespace App\Http\Controllers\Pharmacy;

use App\Enums\InventoryStatus;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Pharmacy $pharmacy */
        $pharmacy = $user->pharmacist->pharmacy;

        $totalItems = $pharmacy->inventories()->count();
        $lowStockCount = $pharmacy->inventories()->where('status', InventoryStatus::LowStock)->count();
        $expiredCount = $pharmacy->inventories()->where('status', InventoryStatus::Expired)->count();
        $expiringSoonCount = $pharmacy->inventories()
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('status', '!=', InventoryStatus::Expired)
            ->count();

        return view('pharmacy.dashboard', compact(
            'pharmacy',
            'totalItems',
            'lowStockCount',
            'expiredCount',
            'expiringSoonCount'
        ));
    }
}
