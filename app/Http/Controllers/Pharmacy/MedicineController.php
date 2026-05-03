<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\StoreMedicineRequest;
use App\Http\Requests\Pharmacy\UpdateMedicineRequest;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Services\MedicineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicineController extends Controller
{
    public function __construct(private MedicineService $service) {}

    public function index(Request $request): View
    {
        $medicines = Medicine::with('category')
            ->when($request->search, fn ($q, $v) => $q->where('name', 'like', "%{$v}%")
                ->orWhere('generic_name', 'like', "%{$v}%")
                ->orWhere('barcode', 'like', "%{$v}%"))
            ->when($request->category, fn ($q, $v) => $q->where('category_id', $v))
            ->when($request->form, fn ($q, $v) => $q->where('form', $v))
            ->when($request->status !== null && $request->status !== '', fn ($q) => $q->where('is_active', $request->boolean('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = MedicineCategory::where('is_active', true)->orderBy('name')->get();

        return view('pharmacy.medicines.index', compact('medicines', 'categories'));
    }

    public function create(): View
    {
        $categories = MedicineCategory::where('is_active', true)->orderBy('name')->get();

        return view('pharmacy.medicines.create', compact('categories'));
    }

    public function store(StoreMedicineRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('pharmacy.medicines.index')
            ->with('success', __('medicines.medicine_created'));
    }

    public function show(Medicine $medicine): View
    {
        $medicine->load('category');

        return view('pharmacy.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine): View
    {
        $categories = MedicineCategory::where('is_active', true)->orderBy('name')->get();

        return view('pharmacy.medicines.edit', compact('medicine', 'categories'));
    }

    public function update(UpdateMedicineRequest $request, Medicine $medicine): RedirectResponse
    {
        $this->service->update($medicine, $request->validated());

        return redirect()->route('pharmacy.medicines.show', $medicine)
            ->with('success', __('medicines.medicine_updated'));
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        $this->service->destroy($medicine);

        return redirect()->route('pharmacy.medicines.index')
            ->with('success', __('medicines.medicine_deleted'));
    }

    public function toggleStatus(Medicine $medicine): RedirectResponse
    {
        $medicine->update(['is_active' => ! $medicine->is_active]);

        $message = $medicine->is_active
            ? __('medicines.medicine_enabled')
            : __('medicines.medicine_disabled');

        return back()->with('success', $message);
    }
}
