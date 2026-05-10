<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Pharmacy;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function index(Request $request): View
    {
        $hospitalId = session('current_hospital_id');

        $doctors = Doctor::where('hospital_id', $hospitalId)
            ->with('user')
            ->get();

        $pharmacies = Pharmacy::where('hospital_id', $hospitalId)
            ->orderBy('name')
            ->get();

        $prescriptions = Prescription::with(['patient.user', 'doctor.user', 'pharmacy'])
            ->where('hospital_id', $hospitalId)
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->doctor_id, fn ($q, $v) => $q->where('doctor_id', $v))
            ->when($request->pharmacy_id, fn ($q, $v) => $q->where('pharmacy_id', $v))
            ->when($request->date_from, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->date_to, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($request->search, fn ($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('prescription_number', 'like', "%{$v}%")
                    ->orWhereHas('patient.user', fn ($u) => $u->where('first_name', 'like', "%{$v}%")
                        ->orWhere('last_name', 'like', "%{$v}%"));
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('hospital-admin.prescriptions.index', compact('prescriptions', 'doctors', 'pharmacies'));
    }

    public function show(Prescription $prescription): View
    {
        abort_unless($prescription->hospital_id === session('current_hospital_id'), 403);

        $prescription->load(['patient.user', 'doctor.user', 'pharmacy', 'items.medicine', 'dispensedBy']);

        return view('hospital-admin.prescriptions.show', compact('prescription'));
    }
}
