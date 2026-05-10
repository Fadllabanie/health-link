<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $doctor = auth()->user()->doctor->load([
            'user',
            'hospital.city',
            'department',
            'primarySpecialty',
            'specialties',
            'schedules' => fn ($q) => $q->where('is_active', true)->orderBy('day_of_week'),
        ]);

        return view('doctor.profile.show', compact('doctor'));
    }
}
