<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\StoreInventoryRequest;
use App\Http\Requests\Pharmacy\UpdateInventoryRequest;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\PharmacyInventory;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    private function currentPharmacy(): Pharmacy
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->pharmacist->pharmacy;
    }

    public function index(Request $request): View
    {
        $pharmacy = $this->currentPharmacy();

        $items = PharmacyInventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->when($request->search, fn ($q, $v) => $q->whereHas(
                'medicine',
                fn ($m) => $m->where('name', 'like', "%{$v}%")
            ))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pharmacy.inventory.index', compact('items', 'pharmacy'));
    }

    public function create(): View
    {
        $pharmacy = $this->currentPharmacy();
        $medicines = Medicine::where('is_active', true)->orderBy('name')->get();

        return view('pharmacy.inventory.create', compact('pharmacy', 'medicines'));
    }

    public function store(StoreInventoryRequest $request): RedirectResponse
    {
        $pharmacy = $this->currentPharmacy();

        /** @var User $user */
        $user = auth()->user();

        $item = $this->inventoryService->addStock($pharmacy, $request->validated(), $user);
        $this->inventoryService->updateStatuses($pharmacy);

        return redirect()
            ->route('pharmacy.inventory.show', $item)
            ->with('success', __('pharmacies.inventory_created'));
    }

    public function show(PharmacyInventory $inventory): View
    {
        $inventory->load('medicine', 'stockMovements.performer');

        return view('pharmacy.inventory.show', compact('inventory'));
    }

    public function edit(PharmacyInventory $inventory): View
    {
        return view('pharmacy.inventory.edit', compact('inventory'));
    }

    public function update(UpdateInventoryRequest $request, PharmacyInventory $inventory): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->inventoryService->updateStock($inventory, $request->validated(), $user);
        $this->inventoryService->updateStatuses($inventory->pharmacy);

        return redirect()
            ->route('pharmacy.inventory.show', $inventory)
            ->with('success', __('pharmacies.inventory_updated'));
    }

    public function expiring(): View
    {
        $pharmacy = $this->currentPharmacy();
        $now = now();

        $expired = PharmacyInventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->where('expiry_date', '<', $now->toDateString())
            ->orderBy('expiry_date')
            ->get();

        $within30 = PharmacyInventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->whereBetween('expiry_date', [$now->toDateString(), $now->copy()->addDays(30)->toDateString()])
            ->orderBy('expiry_date')
            ->get();

        $within60 = PharmacyInventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->whereBetween('expiry_date', [$now->copy()->addDays(31)->toDateString(), $now->copy()->addDays(60)->toDateString()])
            ->orderBy('expiry_date')
            ->get();

        $within90 = PharmacyInventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->whereBetween('expiry_date', [$now->copy()->addDays(61)->toDateString(), $now->copy()->addDays(90)->toDateString()])
            ->orderBy('expiry_date')
            ->get();

        return view('pharmacy.inventory.expiring', compact('expired', 'within30', 'within60', 'within90'));
    }
}
