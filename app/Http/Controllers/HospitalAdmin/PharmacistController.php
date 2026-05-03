<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalAdmin\StorePharmacistRequest;
use App\Models\Pharmacist;
use App\Models\Pharmacy;
use App\Services\PharmacyOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PharmacistController extends Controller
{
    public function __construct(private PharmacyOnboardingService $onboarding) {}

    public function create(Pharmacy $pharmacy): View
    {
        return view('hospital-admin.pharmacists.create', compact('pharmacy'));
    }

    public function store(Pharmacy $pharmacy, StorePharmacistRequest $request): RedirectResponse
    {
        $this->onboarding->onboard($request->validated(), $pharmacy);

        return redirect()
            ->route('hospital-admin.pharmacies.show', $pharmacy)
            ->with('success', __('pharmacies.pharmacist_created'));
    }

    public function destroy(Pharmacy $pharmacy, Pharmacist $pharmacist): RedirectResponse
    {
        $pharmacist->user?->update(['status' => UserStatus::Inactive]);
        $pharmacist->delete();

        return redirect()
            ->route('hospital-admin.pharmacies.show', $pharmacy)
            ->with('success', __('pharmacies.pharmacist_disabled'));
    }
}
