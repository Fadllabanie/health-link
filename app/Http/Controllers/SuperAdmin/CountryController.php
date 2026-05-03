<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\CountryRequest;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::withTrashed()
            ->orderBy('name')
            ->paginate(20);

        return view('super-admin.master-data.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('super-admin.master-data.countries.create');
    }

    public function store(CountryRequest $request)
    {
        Country::create($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('super-admin.master-data.countries.index')
            ->with('success', __('master_data.country_created'));
    }

    public function edit(Country $country)
    {
        return view('super-admin.master-data.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        $country->update($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('super-admin.master-data.countries.index')
            ->with('success', __('master_data.country_updated'));
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('super-admin.master-data.countries.index')
            ->with('success', __('master_data.country_archived'));
    }

    public function restore(int $id)
    {
        Country::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('super-admin.master-data.countries.index')
            ->with('success', __('master_data.country_restored'));
    }

    public function toggle(Country $country)
    {
        $country->update(['is_active' => ! $country->is_active]);

        return back()->with('success', __('master_data.status_updated'));
    }
}
