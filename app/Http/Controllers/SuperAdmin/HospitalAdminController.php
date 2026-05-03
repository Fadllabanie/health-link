<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HospitalAdminController extends Controller
{
    public function index(Hospital $hospital): View
    {
        $admins = User::whereHas('userRoles', fn ($q) => $q
            ->where('hospital_id', $hospital->id)
            ->whereHas('role', fn ($q) => $q->where('name', 'hospital_admin'))
        )->paginate(15);

        return view('super-admin.hospitals.admins.index', compact('hospital', 'admins'));
    }

    public function create(Hospital $hospital): View
    {
        return view('super-admin.hospitals.admins.create', compact('hospital'));
    }

    public function store(Request $request, Hospital $hospital): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($data, $hospital): void {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'status' => UserStatus::Active,
            ]);

            $user->assignRoleInHospital('hospital_admin', $hospital->id, auth()->user()?->getKey());

            DB::table('audit_logs')->insert([
                'user_id' => auth()->user()?->getKey(),
                'auditable_type' => Hospital::class,
                'auditable_id' => $hospital->id,
                'action' => 'add_hospital_admin',
                'new_values' => json_encode(['admin_id' => $user->id, 'email' => $user->email]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        });

        return redirect()->route('super-admin.hospitals.admins.index', $hospital)
            ->with('success', __('hospitals.admin_added'));
    }

    public function edit(Hospital $hospital, User $user): View
    {
        return view('super-admin.hospitals.admins.edit', compact('hospital', 'user'));
    }

    public function update(Request $request, Hospital $hospital, User $user): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($data);

        return redirect()->route('super-admin.hospitals.admins.index', $hospital)
            ->with('success', __('hospitals.admin_updated'));
    }

    public function disable(Hospital $hospital, User $user): RedirectResponse
    {
        $user->update(['status' => UserStatus::Inactive]);

        DB::table('audit_logs')->insert([
            'user_id' => auth()->user()?->getKey(),
            'auditable_type' => Hospital::class,
            'auditable_id' => $hospital->id,
            'action' => 'disable_hospital_admin',
            'new_values' => json_encode(['admin_id' => $user->id]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', __('hospitals.admin_disabled'));
    }

    public function resetPassword(Hospital $hospital, User $user): RedirectResponse
    {
        $newPassword = Str::password(12);
        $user->update(['password' => Hash::make($newPassword)]);

        DB::table('audit_logs')->insert([
            'user_id' => auth()->user()?->getKey(),
            'auditable_type' => Hospital::class,
            'auditable_id' => $hospital->id,
            'action' => 'reset_admin_password',
            'new_values' => json_encode(['admin_id' => $user->id]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', __('hospitals.password_reset_done', ['password' => $newPassword]));
    }
}
