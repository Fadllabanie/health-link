<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $doctor = auth()->user()->doctor;

        $stats = [
            'total_prescriptions' => $doctor ? Prescription::where('doctor_id', $doctor->id)->count() : 0,
            'pending' => $doctor ? Prescription::where('doctor_id', $doctor->id)->where('status', 'pending')->count() : 0,
            'dispensed' => $doctor ? Prescription::where('doctor_id', $doctor->id)->where('status', 'dispensed')->count() : 0,
        ];

        return view('doctor.dashboard', compact('stats'));
    }
}
