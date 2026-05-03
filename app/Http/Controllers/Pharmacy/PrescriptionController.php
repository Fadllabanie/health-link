<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Prescription;
use App\Services\PrescriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function __construct(private PrescriptionService $service) {}

    public function index(Request $request): View
    {
        $hospitalId = session('current_hospital_id');

        $prescriptions = Prescription::with(['patient.user', 'doctor.user', 'items.medicine'])
            ->where('hospital_id', $hospitalId)
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when(! $request->status, fn ($q) => $q->whereIn('status', ['pending', 'partially_dispensed']))
            ->when($request->search, fn ($q, $v) => $q->where('prescription_number', 'like', "%{$v}%")
                ->orWhereHas('patient.user', fn ($u) => $u->where('first_name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pharmacy.prescriptions.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription): View
    {
        $prescription->load(['patient.user', 'doctor.user', 'items.medicine', 'pharmacy']);

        $hospitalId = session('current_hospital_id');
        $pharmacy = Pharmacy::where('hospital_id', $hospitalId)->first();

        return view('pharmacy.prescriptions.show', compact('prescription', 'pharmacy'));
    }

    public function dispense(Request $request, Prescription $prescription): RedirectResponse
    {
        $hospitalId = session('current_hospital_id');
        $pharmacy = Pharmacy::where('hospital_id', $hospitalId)->firstOrFail();

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:prescription_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        try {
            $this->service->dispense($prescription, $pharmacy, auth()->id(), $request->items);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('pharmacy.prescriptions.index')
            ->with('success', __('prescriptions.dispensed'));
    }

    public function reject(Request $request, Prescription $prescription): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        try {
            app(PrescriptionService::class)->cancel($prescription, $request->reason);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', __('prescriptions.rejected'));
    }
}
