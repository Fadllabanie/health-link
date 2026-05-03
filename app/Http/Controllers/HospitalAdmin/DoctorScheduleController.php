<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorScheduleController extends Controller
{
    private const DAYS = [0, 1, 2, 3, 4, 5, 6];

    public function edit(Doctor $doctor): View
    {
        $schedules = $doctor->schedules->keyBy('day_of_week');

        return view('hospital-admin.doctors.schedules', compact('doctor', 'schedules'));
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $request->validate([
            'schedules' => ['nullable', 'array'],
            'schedules.*.start_time' => ['required_with:schedules.*.end_time', 'date_format:H:i'],
            'schedules.*.end_time' => ['required_with:schedules.*.start_time', 'date_format:H:i', 'after:schedules.*.start_time'],
            'schedules.*.slot_duration_minutes' => ['required_with:schedules.*.start_time', 'integer', 'min:5', 'max:120'],
        ]);

        foreach (self::DAYS as $day) {
            $entry = $request->input("schedules.{$day}");

            if (! empty($entry['start_time'])) {
                DoctorSchedule::updateOrCreate(
                    ['doctor_id' => $doctor->id, 'day_of_week' => $day],
                    [
                        'start_time' => $entry['start_time'],
                        'end_time' => $entry['end_time'],
                        'slot_duration_minutes' => $entry['slot_duration_minutes'],
                        'is_active' => true,
                    ]
                );
            } else {
                DoctorSchedule::where('doctor_id', $doctor->id)
                    ->where('day_of_week', $day)
                    ->update(['is_active' => false]);
            }
        }

        return redirect()->route('hospital-admin.doctors.show', $doctor)
            ->with('success', __('doctors.schedule_saved'));
    }
}
