<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalAdmin\StorePharmacyRequest;
use App\Http\Requests\HospitalAdmin\UpdatePharmacyRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Hospital;
use App\Models\Pharmacy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PharmacyController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Hospital $hospital */
        $hospital = Hospital::findOrFail(session('current_hospital_id'));

        $pharmacies = Pharmacy::with('city')
            ->where('hospital_id', $hospital->id)
            ->when(
                $request->search,
                fn ($q, $v) => $q->where('name', 'like', "%{$v}%")
                    ->orWhere('license_number', 'like', "%{$v}%")
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('hospital-admin.pharmacies.index', compact('pharmacies'));
    }

    public function create(): View
    {
        $countries = Country::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('hospital-admin.pharmacies.create', compact('countries', 'cities'));
    }

    public function store(StorePharmacyRequest $request): RedirectResponse
    {
        /** @var Hospital $hospital */
        $hospital = Hospital::findOrFail(session('current_hospital_id'));

        $data = $request->validated();
        $data['hospital_id'] = $hospital->id;
        $data['slug'] = Str::slug($data['name']);
        $data['is_24_hours'] = $request->boolean('is_24_hours');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('pharmacies/logos', 'public');
        }

        $pharmacy = Pharmacy::create($data);

        return redirect()
            ->route('hospital-admin.pharmacies.show', $pharmacy)
            ->with('success', __('pharmacies.pharmacy_created'));
    }

    public function show(Pharmacy $pharmacy): View
    {
        $pharmacy->load('pharmacists.user', 'inventories.medicine', 'city', 'country');

        return view('hospital-admin.pharmacies.show', compact('pharmacy'));
    }

    public function edit(Pharmacy $pharmacy): View
    {
        $countries = Country::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('hospital-admin.pharmacies.edit', compact('pharmacy', 'countries', 'cities'));
    }

    public function update(UpdatePharmacyRequest $request, Pharmacy $pharmacy): RedirectResponse
    {
        $data = $request->validated();
        $data['is_24_hours'] = $request->boolean('is_24_hours');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('pharmacies/logos', 'public');
        }

        $pharmacy->update($data);

        return redirect()
            ->route('hospital-admin.pharmacies.show', $pharmacy)
            ->with('success', __('pharmacies.pharmacy_updated'));
    }

    public function destroy(Pharmacy $pharmacy): RedirectResponse
    {
        $pharmacy->delete();

        return redirect()
            ->route('hospital-admin.pharmacies.index')
            ->with('success', __('pharmacies.pharmacy_deleted'));
    }
}
