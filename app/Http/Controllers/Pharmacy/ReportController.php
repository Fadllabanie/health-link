<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\PharmacyInventory;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    private function currentPharmacy(): Pharmacy
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->pharmacist->pharmacy;
    }

    public function stock(): View
    {
        $pharmacy = $this->currentPharmacy();

        $items = PharmacyInventory::with('medicine.category')
            ->where('pharmacy_id', $pharmacy->id)
            ->get()
            ->groupBy(fn ($item) => $item->medicine?->category?->name ?? __('app.uncategorized'));

        return view('pharmacy.reports.stock', compact('items', 'pharmacy'));
    }

    public function movements(Request $request): View
    {
        $pharmacy = $this->currentPharmacy();

        $movements = StockMovement::with('pharmacyInventory.medicine', 'performer')
            ->whereHas('pharmacyInventory', fn ($q) => $q->where('pharmacy_id', $pharmacy->id))
            ->when($request->date_from, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->date_to, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pharmacy.reports.movements', compact('movements', 'pharmacy'));
    }

    public function lowStock(): View
    {
        $pharmacy = $this->currentPharmacy();

        $items = PharmacyInventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->whereColumn('quantity_in_stock', '<=', 'reorder_level')
            ->orderBy('quantity_in_stock')
            ->get();

        return view('pharmacy.reports.low-stock', compact('items', 'pharmacy'));
    }
}
