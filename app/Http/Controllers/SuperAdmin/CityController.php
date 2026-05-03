<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\CityRequest;
use App\Models\City;
use App\Models\Country;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('country')
            ->when(request('country_id'), fn ($q) => $q->where('country_id', request('country_id')))
            ->when(request('search'), fn ($q) => $q->where('name', 'like', '%'.request('search').'%'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $countries = Country::active()->orderBy('name')->get();

        return view('super-admin.master-data.cities.index', compact('cities', 'countries'));
    }

    public function create()
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('super-admin.master-data.cities.create', compact('countries'));
    }

    public function store(CityRequest $request)
    {
        City::create($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('super-admin.master-data.cities.index')
            ->with('success', __('master_data.city_created'));
    }

    public function edit(City $city)
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('super-admin.master-data.cities.edit', compact('city', 'countries'));
    }

    public function update(CityRequest $request, City $city)
    {
        $city->update($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('super-admin.master-data.cities.index')
            ->with('success', __('master_data.city_updated'));
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('super-admin.master-data.cities.index')
            ->with('success', __('master_data.city_deleted'));
    }

    public function toggle(City $city)
    {
        $city->update(['is_active' => ! $city->is_active]);

        return back()->with('success', __('master_data.status_updated'));
    }
}
