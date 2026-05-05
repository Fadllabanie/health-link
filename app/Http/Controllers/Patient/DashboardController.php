<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient()->with(['user', 'city', 'qrCode'])->firstOrFail();

        $stats = [
            'total_prescriptions' => $patient->prescriptions()->count(),
            'pending_prescriptions' => $patient->prescriptions()->where('status', 'pending')->count(),
            'medical_records' => $patient->medicalRecords()->count(),
        ];

        return view('patient.dashboard.index', compact('patient', 'stats'));
    }
}
