<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StorePrescriptionRequest;
use App\Http\Requests\Doctor\UpdatePrescriptionRequest;
use App\Models\Medicine;
use App\Models\Patient;
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
        $doctor = auth()->user()->doctor;

        $prescriptions = Prescription::with(['patient.user', 'items'])
            ->where('doctor_id', $doctor->id)
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->search, fn ($q, $v) => $q->where('prescription_number', 'like', "%{$v}%")
                ->orWhereHas('patient.user', fn ($u) => $u->where('first_name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    public function create(Request $request): View
    {
        $doctor = auth()->user()->doctor;
        $hospitalId = session('current_hospital_id');

        $patients = Patient::with('user')
            ->where('hospital_id', $hospitalId)
            ->get();

        $medicines = Medicine::where('is_active', true)->orderBy('name')->get();

        $selectedPatient = $request->patient_id
            ? Patient::with('user')->find($request->patient_id)
            : null;

        return view('doctor.prescriptions.create', compact('patients', 'medicines', 'selectedPatient', 'doctor'));
    }

    public function store(StorePrescriptionRequest $request): RedirectResponse
    {
        $doctor = auth()->user()->doctor;
        $patient = Patient::findOrFail($request->validated()['patient_id']);

        try {
            $rx = $this->service->create($doctor, $patient, $request->validated(), $request->validated()['items'] ?? []);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('doctor.prescriptions.show', $rx)
            ->with('success', __('prescriptions.created'));
    }

    public function show(Prescription $prescription): View
    {
        $prescription->load(['patient.user', 'doctor.user', 'items.medicine', 'pharmacy', 'dispensedBy']);

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription): View
    {
        abort_if($prescription->status->value !== 'pending', 403);

        $prescription->load('items.medicine');
        $medicines = Medicine::where('is_active', true)->orderBy('name')->get();

        return view('doctor.prescriptions.edit', compact('prescription', 'medicines'));
    }

    public function update(UpdatePrescriptionRequest $request, Prescription $prescription): RedirectResponse
    {
        try {
            $this->service->update($prescription, $request->validated(), $request->validated()['items'] ?? []);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('doctor.prescriptions.show', $prescription)
            ->with('success', __('prescriptions.updated'));
    }

    public function cancel(Request $request, Prescription $prescription): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        try {
            $this->service->cancel($prescription, $request->reason);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', __('prescriptions.cancelled'));
    }
}
