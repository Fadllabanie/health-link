<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalAdmin\StoreInventoryRequest;
use App\Http\Requests\HospitalAdmin\UpdateInventoryRequest;
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

    public function index(Request $request, Pharmacy $pharmacy): View
    {
        $this->authorizePharmacy($pharmacy);

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

        return view('hospital-admin.inventory.index', compact('pharmacy', 'items'));
    }

    public function create(Pharmacy $pharmacy): View
    {
        $this->authorizePharmacy($pharmacy);

        $medicines = Medicine::where('is_active', true)->orderBy('name')->get();

        return view('hospital-admin.inventory.create', compact('pharmacy', 'medicines'));
    }

    public function store(StoreInventoryRequest $request, Pharmacy $pharmacy): RedirectResponse
    {
        $this->authorizePharmacy($pharmacy);

        /** @var User $user */
        $user = auth()->user();

        $item = $this->inventoryService->addStock($pharmacy, $request->validated(), $user);
        $this->inventoryService->updateStatuses($pharmacy);

        return redirect()
            ->route('hospital-admin.pharmacies.inventory.show', [$pharmacy, $item])
            ->with('success', __('pharmacies.inventory_created'));
    }

    public function show(Pharmacy $pharmacy, PharmacyInventory $inventory): View
    {
        $this->authorizePharmacy($pharmacy);

        $inventory->load('medicine', 'stockMovements.performer');

        return view('hospital-admin.inventory.show', compact('pharmacy', 'inventory'));
    }

    public function edit(Pharmacy $pharmacy, PharmacyInventory $inventory): View
    {
        $this->authorizePharmacy($pharmacy);

        return view('hospital-admin.inventory.edit', compact('pharmacy', 'inventory'));
    }

    public function update(UpdateInventoryRequest $request, Pharmacy $pharmacy, PharmacyInventory $inventory): RedirectResponse
    {
        $this->authorizePharmacy($pharmacy);

        /** @var User $user */
        $user = auth()->user();

        $this->inventoryService->updateStock($inventory, $request->validated(), $user);
        $this->inventoryService->updateStatuses($pharmacy);

        return redirect()
            ->route('hospital-admin.pharmacies.inventory.show', [$pharmacy, $inventory])
            ->with('success', __('pharmacies.inventory_updated'));
    }

    private function authorizePharmacy(Pharmacy $pharmacy): void
    {
        $hospitalId = session('current_hospital_id');

        abort_unless($pharmacy->hospital_id === $hospitalId, 403);
    }
}
