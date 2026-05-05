<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\View;

class PrescriptionController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient()->firstOrFail();

        $prescriptions = Prescription::where('patient_id', $patient->id)
            ->with(['doctor.user', 'hospital', 'items'])
            ->latest('issued_at')
            ->paginate(15);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    public function latest(): View
    {
        $patient = auth()->user()->patient()->firstOrFail();

        $prescription = Prescription::where('patient_id', $patient->id)
            ->with(['doctor.user', 'hospital', 'items.medicine'])
            ->latest('issued_at')
            ->first();

        return view('patient.prescriptions.latest', compact('prescription'));
    }

    public function show(Prescription $prescription): View
    {
        $patient = auth()->user()->patient()->firstOrFail();

        abort_unless($prescription->patient_id === $patient->id, 403);

        $prescription->load(['doctor.user', 'hospital', 'items.medicine']);

        return view('patient.prescriptions.show', compact('prescription'));
    }
}
