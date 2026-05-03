<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hospitalId = session('current_hospital_id');

        $stats = [
            'doctors' => Doctor::withoutGlobalScopes()
                ->where('hospital_id', $hospitalId)->count(),
            'patients' => Patient::withoutGlobalScopes()
                ->where('hospital_id', $hospitalId)->count(),
            'appointments_today' => Appointment::withoutGlobalScopes()
                ->where('hospital_id', $hospitalId)
                ->whereDate('scheduled_at', today())
                ->count(),
            'pending_prescriptions' => Prescription::withoutGlobalScopes()
                ->where('hospital_id', $hospitalId)
                ->where('status', 'pending')
                ->count(),
        ];

        return view('hospital-admin.dashboard', compact('stats'));
    }
}
