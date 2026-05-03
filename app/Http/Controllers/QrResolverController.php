<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QrResolverController extends Controller
{
    public function __invoke(Request $request, string $code, QrCodeService $qrCodeService): RedirectResponse
    {
        try {
            $patient = $qrCodeService->verifyAndResolve($code);
        } catch (\Throwable) {
            abort(404);
        }

        // Redirect to the patient's prescriptions or a public summary view.
        return redirect()->route('hospital-admin.patients.show', $patient);
    }
}
