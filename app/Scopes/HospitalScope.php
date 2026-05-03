<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class HospitalScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        $hospitalId = session('current_hospital_id');

        if ($hospitalId) {
            $builder->where($model->getTable().'.hospital_id', $hospitalId);
        }
    }
}
