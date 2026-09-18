<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StorePatientRequest;
use App\Http\Requests\Doctor\UpdatePatientRequest;
use App\Models\City;
use App\Models\Hospital;
use App\Models\Patient;
use App\Services\PatientRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function __construct(private PatientRegistrationService $registration) {}

    public function index(Request $request): View
    {
        $hospitalId = session('current_hospital_id');

        $patients = Patient::with('user')
            ->where('hospital_id', $hospitalId)
            ->when($request->search, fn ($q, $v) => $q->where(fn ($w) => $w->whereHas(
                'user',
                fn ($u) => $u->where('first_name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")
                    ->orWhere('phone', 'like', "%{$v}%")
            )->orWhere('medical_record_number', 'like', "%{$v}%")))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('doctor.patients.index', compact('patients'));
    }

    public function create(): View
    {
        $cities = City::orderBy('name')->get();

        return view('doctor.patients.create', compact('cities'));
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        /** @var Hospital $hospital */
        $hospital = Hospital::findOrFail(session('current_hospital_id'));

        $patient = $this->registration->register($request->validated(), $hospital);

        return redirect()
            ->route('doctor.patients.show', $patient)
            ->with('success', __('patients.patient_created'));
    }

    public function show(Patient $patient): View
    {
        abort_if($patient->hospital_id !== (int) session('current_hospital_id'), 403);

        $patient->load(['user', 'medicalRecords' => fn ($q) => $q->latest('visit_date')->limit(5), 'prescriptions' => fn ($q) => $q->latest()->limit(5)]);

        return view('doctor.patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        abort_if($patient->hospital_id !== (int) session('current_hospital_id'), 403);

        $patient->load('user');
        $cities = City::orderBy('name')->get();

        return view('doctor.patients.edit', compact('patient', 'cities'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        abort_if($patient->hospital_id !== (int) session('current_hospital_id'), 403);

        $data = $request->validated();

        DB::transaction(function () use ($data, $patient): void {
            $patient->user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'national_id' => $data['national_id'] ?? null,
            ]);

            $patient->update([
                'city_id' => $data['city_id'] ?? null,
                'blood_type' => $data['blood_type'] ?? null,
                'height_cm' => $data['height_cm'] ?? null,
                'weight_kg' => $data['weight_kg'] ?? null,
                'allergies' => $data['allergies'] ?? null,
                'chronic_conditions' => $data['chronic_conditions'] ?? null,
                'current_medications' => $data['current_medications'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'emergency_contact_relation' => $data['emergency_contact_relation'] ?? null,
                'insurance_provider' => $data['insurance_provider'] ?? null,
                'insurance_policy_number' => $data['insurance_policy_number'] ?? null,
                'marital_status' => $data['marital_status'] ?? null,
                'occupation' => $data['occupation'] ?? null,
            ]);
        });

        return redirect()
            ->route('doctor.patients.show', $patient)
            ->with('success', __('patients.patient_updated'));
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        abort_if($patient->hospital_id !== (int) session('current_hospital_id'), 403);

        $patient->delete();

        return redirect()
            ->route('doctor.patients.index')
            ->with('success', __('patients.patient_deleted'));
    }
}
