<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalAdmin\StorePatientRequest;
use App\Models\City;
use App\Models\Hospital;
use App\Models\Patient;
use App\Services\PatientRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function __construct(private PatientRegistrationService $registration) {}

    public function index(Request $request): View
    {
        /** @var Hospital $hospital */
        $hospital = Hospital::findOrFail(session('current_hospital_id'));

        $patients = Patient::with('user', 'city')
            ->where('hospital_id', $hospital->id)
            ->when($request->search, fn ($q, $v) => $q->whereHas(
                'user',
                fn ($u) => $u->where('first_name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")
                    ->orWhere('phone', 'like', "%{$v}%")
            )->orWhere('medical_record_number', 'like', "%{$v}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('hospital-admin.patients.index', compact('patients'));
    }

    public function create(): View
    {
        $cities = City::orderBy('name')->get();

        return view('hospital-admin.patients.create', compact('cities'));
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        /** @var Hospital $hospital */
        $hospital = Hospital::findOrFail(session('current_hospital_id'));

        $patient = $this->registration->register($request->validated(), $hospital);

        return redirect()
            ->route('hospital-admin.patients.show', $patient)
            ->with('success', __('patients.patient_created'));
    }

    public function show(Patient $patient): View
    {
        $patient->load('user', 'city', 'qrCode', 'medicalRecords', 'prescriptions', 'appointments');

        return view('hospital-admin.patients.show', compact('patient'));
    }
}
