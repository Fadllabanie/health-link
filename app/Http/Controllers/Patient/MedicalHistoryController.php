<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\View\View;

class MedicalHistoryController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient()->firstOrFail();

        $records = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor.user', 'hospital', 'attachments'])
            ->orderByDesc('visit_date')
            ->paginate(10);

        return view('patient.medical-history.index', compact('records'));
    }
}
