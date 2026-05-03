<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\SpecialtyRequest;
use App\Models\Specialty;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::withTrashed()
            ->when(request('search'), fn ($q) => $q->where('name', 'like', '%'.request('search').'%'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('super-admin.master-data.specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('super-admin.master-data.specialties.create');
    }

    public function store(SpecialtyRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('specialties', 'public');
        }

        Specialty::create($data);

        return redirect()->route('super-admin.master-data.specialties.index')
            ->with('success', __('master_data.specialty_created'));
    }

    public function edit(Specialty $specialty)
    {
        return view('super-admin.master-data.specialties.edit', compact('specialty'));
    }

    public function update(SpecialtyRequest $request, Specialty $specialty)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('icon')) {
            if ($specialty->icon) {
                Storage::disk('public')->delete($specialty->icon);
            }
            $data['icon'] = $request->file('icon')->store('specialties', 'public');
        }

        $specialty->update($data);

        return redirect()->route('super-admin.master-data.specialties.index')
            ->with('success', __('master_data.specialty_updated'));
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->delete();

        return redirect()->route('super-admin.master-data.specialties.index')
            ->with('success', __('master_data.specialty_archived'));
    }

    public function restore(int $id)
    {
        Specialty::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('super-admin.master-data.specialties.index')
            ->with('success', __('master_data.specialty_restored'));
    }

    public function toggle(Specialty $specialty)
    {
        $specialty->update(['is_active' => ! $specialty->is_active]);

        return back()->with('success', __('master_data.status_updated'));
    }
}
