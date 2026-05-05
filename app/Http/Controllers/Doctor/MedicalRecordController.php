<?php

namespace App\Http\Controllers\Doctor;

use App\Enums\RecordStatus;
use App\Enums\VisitType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreMedicalRecordRequest;
use App\Http\Requests\Doctor\UpdateMedicalRecordRequest;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordAttachment;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    public function history(Patient $patient): View
    {
        $this->authorize('viewAny', [MedicalRecord::class, $patient]);

        $records = MedicalRecord::with(['doctor.user', 'attachments'])
            ->where('patient_id', $patient->id)
            ->latest('visit_date')
            ->paginate(15);

        return view('doctor.medical-records.history', compact('patient', 'records'));
    }

    public function create(Patient $patient): View
    {
        $this->authorize('create', [MedicalRecord::class, $patient]);

        $visitTypes = VisitType::cases();

        return view('doctor.medical-records.create', compact('patient', 'visitTypes'));
    }

    public function store(StoreMedicalRecordRequest $request, Patient $patient): RedirectResponse
    {
        $this->authorize('create', [MedicalRecord::class, $patient]);

        $doctor = auth()->user()->doctor;
        $data = $request->validated();

        $record = MedicalRecord::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'hospital_id' => $doctor->hospital_id,
            'visit_date' => $data['visit_date'],
            'visit_type' => $data['visit_type'],
            'diagnosis' => $data['diagnosis'],
            'notes' => $data['notes'],
            'status' => ($data['finalize'] ?? false) ? RecordStatus::Finalized : RecordStatus::Draft,
        ]);

        $this->handleAttachments($request, $record);

        return redirect()
            ->route('doctor.patients.medical-history', $patient)
            ->with('success', __('medical_records.created'));
    }

    public function show(Patient $patient, MedicalRecord $medicalRecord): View
    {
        $this->authorize('view', $medicalRecord);

        $medicalRecord->load(['doctor.user', 'attachments.uploader', 'prescriptions.items.medicine']);

        return view('doctor.medical-records.show', compact('patient', 'medicalRecord'));
    }

    public function edit(Patient $patient, MedicalRecord $medicalRecord): View
    {
        $this->authorize('update', $medicalRecord);

        $visitTypes = VisitType::cases();

        return view('doctor.medical-records.edit', compact('patient', 'medicalRecord', 'visitTypes'));
    }

    public function update(UpdateMedicalRecordRequest $request, Patient $patient, MedicalRecord $medicalRecord): RedirectResponse
    {
        $this->authorize('update', $medicalRecord);

        $data = $request->validated();

        $wasFinalized = $medicalRecord->status === RecordStatus::Finalized;

        $medicalRecord->update([
            'visit_date' => $data['visit_date'],
            'visit_type' => $data['visit_type'],
            'diagnosis' => $data['diagnosis'],
            'notes' => $data['notes'],
            'status' => ($data['finalize'] ?? false)
                ? RecordStatus::Finalized
                : ($wasFinalized ? RecordStatus::Amended : $medicalRecord->status),
        ]);

        $this->handleAttachments($request, $medicalRecord);

        return redirect()
            ->route('doctor.patients.medical-history', $patient)
            ->with('success', __('medical_records.updated'));
    }

    private function handleAttachments(StoreMedicalRecordRequest|UpdateMedicalRecordRequest $request, MedicalRecord $record): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        foreach ($request->file('attachments') as $index => $file) {
            $ext = $file->getClientOriginalExtension();
            $uuid = Str::uuid()->toString();
            $path = $file->storeAs(
                "medical-records/{$record->patient_id}",
                "{$uuid}.{$ext}",
                'public'
            );

            MedicalRecordAttachment::create([
                'medical_record_id' => $record->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => strtolower($ext),
                'file_size' => $file->getSize(),
                'description' => $request->input("attachment_descriptions.{$index}"),
                'uploaded_by' => auth()->id(),
            ]);
        }
    }
}
