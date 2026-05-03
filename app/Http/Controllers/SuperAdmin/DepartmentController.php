<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\DepartmentRequest;
use App\Models\Department;
use App\Models\Hospital;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withTrashed()
            ->with('hospital')
            ->when(request('hospital_id'), fn ($q) => $q->where('hospital_id', request('hospital_id')))
            ->when(request('search'), fn ($q) => $q->where('name', 'like', '%'.request('search').'%'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $hospitals = Hospital::active()->orderBy('name')->get();

        return view('super-admin.master-data.departments.index', compact('departments', 'hospitals'));
    }

    public function create()
    {
        $hospitals = Hospital::active()->orderBy('name')->get();

        return view('super-admin.master-data.departments.create', compact('hospitals'));
    }

    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('super-admin.master-data.departments.index')
            ->with('success', __('master_data.department_created'));
    }

    public function edit(Department $department)
    {
        $hospitals = Hospital::active()->orderBy('name')->get();

        return view('super-admin.master-data.departments.edit', compact('department', 'hospitals'));
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('super-admin.master-data.departments.index')
            ->with('success', __('master_data.department_updated'));
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('super-admin.master-data.departments.index')
            ->with('success', __('master_data.department_archived'));
    }

    public function restore(int $id)
    {
        Department::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('super-admin.master-data.departments.index')
            ->with('success', __('master_data.department_restored'));
    }

    public function toggle(Department $department)
    {
        $department->update(['is_active' => ! $department->is_active]);

        return back()->with('success', __('master_data.status_updated'));
    }
}
