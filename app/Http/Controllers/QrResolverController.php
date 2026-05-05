<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QrResolverController extends Controller
{
    public function __invoke(Request $request, string $code, QrCodeService $qrCodeService): View
    {
        $qr = QrCode::where('code', $code)->where('is_active', true)->first();

        if (! $qr) {
            return view('qr.invalid', ['reason' => 'not_found']);
        }

        if ($qr->isExpired()) {
            return view('qr.invalid', ['reason' => 'expired']);
        }

        $qr->incrementScan();

        $subject = $qr->qrable;

        if (! $subject) {
            return view('qr.invalid', ['reason' => 'not_found']);
        }

        $patient = match (true) {
            $subject instanceof Patient => $subject,
            $subject instanceof Prescription => $subject->patient,
            default => null,
        };

        if (! $patient) {
            return view('qr.invalid', ['reason' => 'not_found']);
        }

        $patient->load(['user', 'primaryHospital']);

        $latestPrescription = $patient->prescriptions()
            ->with(['items.medicine', 'doctor.user', 'hospital'])
            ->latest('issued_at')
            ->first();

        $isAuthenticated = auth()->check();

        return view('qr.resolved', compact('patient', 'latestPrescription', 'isAuthenticated'));
    }
}
