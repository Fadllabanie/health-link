<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreHospitalRequest;
use App\Http\Requests\SuperAdmin\UpdateHospitalRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Services\HospitalOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalController extends Controller
{
    public function index(Request $request): View
    {
        $hospitals = Hospital::withoutGlobalScopes()
            ->with(['country', 'city'])
            ->when($request->search, fn ($q, $s) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")
            ))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->withTrashed()
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('super-admin.hospitals.index', compact('hospitals'));
    }

    public function create(): View
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $specialties = Specialty::orderBy('name')->get();

        return view('super-admin.hospitals.create', compact('countries', 'cities', 'specialties'));
    }

    public function store(StoreHospitalRequest $request): RedirectResponse
    {
        $hospitalData = $request->safe()->except(['admin_first_name', 'admin_last_name', 'admin_email', 'admin_phone', 'admin_password', 'specialty_ids', 'logo']);
        $adminData = $request->safe()->only(['admin_first_name', 'admin_last_name', 'admin_email', 'admin_phone', 'admin_password']);
        $adminData = array_combine(
            array_map(fn ($k) => str_replace('admin_', '', $k), array_keys($adminData)),
            array_values($adminData)
        );

        if ($request->hasFile('logo')) {
            $hospitalData['logo'] = $request->file('logo')->store('hospitals/logos', 'public');
        }

        $hospital = HospitalOnboardingService::create($hospitalData, $adminData);

        if ($request->filled('specialty_ids')) {
            $hospital->specialties()->sync($request->specialty_ids);
        }

        return redirect()->route('super-admin.hospitals.show', $hospital)
            ->with('success', __('hospitals.created_successfully'));
    }

    public function show(Hospital $hospital): View
    {
        $hospital->loadMissing(['country', 'city', 'specialties', 'departments', 'admins']);

        return view('super-admin.hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital): View
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $specialties = Specialty::orderBy('name')->get();
        $hospital->loadMissing(['specialties']);

        return view('super-admin.hospitals.edit', compact('hospital', 'countries', 'cities', 'specialties'));
    }

    public function update(UpdateHospitalRequest $request, Hospital $hospital): RedirectResponse
    {
        $data = $request->safe()->except(['specialty_ids', 'logo']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('hospitals/logos', 'public');
        }

        HospitalOnboardingService::update($hospital, $data);

        if ($request->has('specialty_ids')) {
            $hospital->specialties()->sync($request->specialty_ids ?? []);
        }

        return redirect()->route('super-admin.hospitals.show', $hospital)
            ->with('success', __('hospitals.updated_successfully'));
    }

    public function destroy(Hospital $hospital): RedirectResponse
    {
        HospitalOnboardingService::archive($hospital);

        return redirect()->route('super-admin.hospitals.index')
            ->with('success', __('hospitals.archived_successfully'));
    }

    public function updateStatus(Request $request, Hospital $hospital): RedirectResponse
    {
        $request->validate(['status' => 'required|in:active,inactive,suspended']);

        HospitalOnboardingService::updateStatus($hospital, $request->status);

        return back()->with('success', __('hospitals.status_updated'));
    }
}
