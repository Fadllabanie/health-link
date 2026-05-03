<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalAdmin\StoreDoctorRequest;
use App\Http\Requests\HospitalAdmin\UpdateDoctorRequest;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Services\DoctorOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function __construct(private DoctorOnboardingService $onboarding) {}

    public function index(Request $request): View
    {
        $hospitalId = session('current_hospital_id');

        $doctors = Doctor::with(['user', 'primarySpecialty', 'department'])
            ->where('hospital_id', $hospitalId)
            ->when($request->specialty, fn ($q, $v) => $q->where('primary_specialty_id', $v))
            ->when($request->department, fn ($q, $v) => $q->where('department_id', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->search, fn ($q, $v) => $q->whereHas(
                'user',
                fn ($u) => $u->where('first_name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")
                    ->orWhere('email', 'like', "%{$v}%")
            ))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $specialties = Specialty::orderBy('name')->get();
        $departments = Department::where('hospital_id', $hospitalId)->orderBy('name')->get();

        return view('hospital-admin.doctors.index', compact('doctors', 'specialties', 'departments'));
    }

    public function create(): View
    {
        $hospitalId = session('current_hospital_id');
        $specialties = Specialty::orderBy('name')->get();
        $departments = Department::where('hospital_id', $hospitalId)->orderBy('name')->get();

        return view('hospital-admin.doctors.create', compact('specialties', 'departments'));
    }

    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
        ];

        $doctorData = [
            'license_number' => $data['license_number'],
            'license_expires_at' => $data['license_expires_at'] ?? null,
            'primary_specialty_id' => $data['primary_specialty_id'],
            'department_id' => $data['department_id'],
            'secondary_specialties' => $data['secondary_specialties'] ?? [],
            'consultation_fee' => $data['consultation_fee'] ?? null,
            'years_of_experience' => $data['years_of_experience'] ?? null,
            'qualifications' => $data['qualifications'] ?? null,
            'bio' => $data['bio'] ?? null,
            'joined_at' => $data['joined_at'] ?? now()->toDateString(),
        ];

        $hospitalId = session('current_hospital_id');

        $this->onboarding->create($userData, $doctorData, $hospitalId);

        return redirect()->route('hospital-admin.doctors.index')
            ->with('success', __('doctors.doctor_created'));
    }

    public function show(Doctor $doctor): View
    {
        $doctor->load(['user', 'primarySpecialty', 'specialties', 'department', 'schedules']);

        return view('hospital-admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor): View
    {
        $doctor->load(['user', 'specialties']);
        $specialties = Specialty::orderBy('name')->get();
        $departments = Department::where('hospital_id', session('current_hospital_id'))->orderBy('name')->get();

        return view('hospital-admin.doctors.edit', compact('doctor', 'specialties', 'departments'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validated();

        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
        ];

        $doctorData = [
            'license_number' => $data['license_number'],
            'license_expires_at' => $data['license_expires_at'] ?? null,
            'primary_specialty_id' => $data['primary_specialty_id'],
            'department_id' => $data['department_id'],
            'secondary_specialties' => $data['secondary_specialties'] ?? [],
            'consultation_fee' => $data['consultation_fee'] ?? null,
            'years_of_experience' => $data['years_of_experience'] ?? null,
            'qualifications' => $data['qualifications'] ?? null,
            'bio' => $data['bio'] ?? null,
            'joined_at' => $data['joined_at'] ?? null,
        ];

        $this->onboarding->update($doctor, $userData, $doctorData);

        return redirect()->route('hospital-admin.doctors.show', $doctor)
            ->with('success', __('doctors.doctor_updated'));
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();
        $doctor->user->delete();

        return redirect()->route('hospital-admin.doctors.index')
            ->with('success', __('doctors.doctor_deleted'));
    }

    public function toggleStatus(Doctor $doctor): RedirectResponse
    {
        $doctor = $this->onboarding->toggleStatus($doctor);

        $message = $doctor->status->value === 'active'
            ? __('doctors.doctor_enabled')
            : __('doctors.doctor_disabled');

        return back()->with('success', $message);
    }
}
