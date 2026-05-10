<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $hospitalId = session('current_hospital_id');

        $patients = Patient::with('user')
            ->where('hospital_id', $hospitalId)
            ->when($request->search, fn ($q, $v) => $q->whereHas(
                'user',
                fn ($u) => $u->where('first_name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")
                    ->orWhere('phone', 'like', "%{$v}%")
            )->orWhere('medical_record_number', 'like', "%{$v}%"))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('doctor.patients.index', compact('patients'));
    }

    public function show(Patient $patient): View
    {
        abort_if($patient->hospital_id !== (int) session('current_hospital_id'), 403);

        $patient->load(['user', 'medicalRecords' => fn ($q) => $q->latest('visit_date')->limit(5), 'prescriptions' => fn ($q) => $q->latest()->limit(5)]);

        return view('doctor.patients.show', compact('patient'));
    }
}
