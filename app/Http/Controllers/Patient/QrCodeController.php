<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QrCodeController extends Controller
{
    public function __construct(private readonly QrCodeService $qrCodeService) {}

    public function show(): View
    {
        $patient = auth()->user()->patient()->with(['qrCode'])->firstOrFail();

        if (! $patient->qrCode) {
            $this->qrCodeService->generateForPatient($patient);
            $patient->load('qrCode');
        }

        return view('patient.qr-code.show', compact('patient'));
    }

    public function regenerate(): RedirectResponse
    {
        $patient = auth()->user()->patient()->with(['qrCode'])->firstOrFail();

        if ($patient->qrCode) {
            $this->qrCodeService->regenerate($patient->qrCode);
        } else {
            $this->qrCodeService->generateForPatient($patient);
        }

        return redirect()->route('patient.qr-code.show')
            ->with('success', __('patients.qr_regenerated'));
    }
}
