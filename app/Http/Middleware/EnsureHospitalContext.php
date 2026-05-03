<?php

namespace App\Http\Middleware;

use App\Models\Hospital;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHospitalContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super admin bypasses hospital context
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        $hospitalIds = $user->hospitalIds();

        if (empty($hospitalIds)) {
            abort(403, __('app.no_hospital_assigned'));
        }

        // If already has context in session and it's valid, proceed
        $currentHospitalId = session('current_hospital_id');
        if ($currentHospitalId && in_array($currentHospitalId, $hospitalIds)) {
            // Check hospital is not suspended/inactive
            $hospital = Hospital::withoutGlobalScopes()->find($currentHospitalId);
            if ($hospital && $hospital->status->value === 'active') {
                return $next($request);
            }

            // Hospital suspended — clear context
            session()->forget('current_hospital_id');
        }

        // Single hospital: auto-set context
        if (count($hospitalIds) === 1) {
            $hospital = Hospital::withoutGlobalScopes()->find($hospitalIds[0]);
            if (! $hospital || $hospital->status->value !== 'active') {
                abort(403, __('app.hospital_suspended'));
            }
            session(['current_hospital_id' => $hospitalIds[0]]);

            return $next($request);
        }

        // Multiple hospitals: redirect to picker (unless already on picker route)
        if (! $request->routeIs('hospital.pick')) {
            return redirect()->route('hospital.pick');
        }

        return $next($request);
    }
}
