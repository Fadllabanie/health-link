<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_hospitals' => Hospital::withoutGlobalScopes()->withTrashed()->count(),
            'active_hospitals' => Hospital::withoutGlobalScopes()->where('status', 'active')->count(),
            'suspended_hospitals' => Hospital::withoutGlobalScopes()->where('status', 'suspended')->count(),
            'inactive_hospitals' => Hospital::withoutGlobalScopes()->where('status', 'inactive')->count(),
            'total_users' => User::withTrashed()->count(),
        ];

        $recentActivity = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recentActivity'));
    }
}
