@extends('layouts.app')

@section('title', __('hospitals.admins') . ' — ' . $hospital->name)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ __('hospitals.admins') }}</h5>
            <small class="text-muted">{{ $hospital->name }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('super-admin.hospitals.admins.create', $hospital) }}" class="btn btn-primary btn-sm">
                {{ __('hospitals.add_admin') }}
            </a>
            <a href="{{ route('super-admin.hospitals.show', $hospital) }}" class="btn btn-outline-secondary btn-sm">
                {{ __('app.back') }}
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('hospitals.admin_first_name') }} / {{ __('hospitals.admin_last_name') }}</th>
                    <th>{{ __('hospitals.admin_email') }}</th>
                    <th>{{ __('hospitals.admin_phone') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->phone ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $admin->status->value === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ __('app.' . $admin->status->value) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('super-admin.hospitals.admins.edit', [$hospital, $admin]) }}"
                               class="btn btn-xs btn-outline-primary me-1">{{ __('app.edit') }}</a>

                            @if($admin->status->value === 'active')
                                <form method="POST" action="{{ route('super-admin.hospitals.admins.disable', [$hospital, $admin]) }}"
                                      class="d-inline" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-xs btn-outline-warning">{{ __('hospitals.disable_admin') }}</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('super-admin.hospitals.admins.reset-password', [$hospital, $admin]) }}"
                                  class="d-inline" onsubmit="return confirm('{{ __('app.yes') }}?')">
                                @csrf
                                <button class="btn btn-xs btn-outline-secondary">{{ __('hospitals.reset_password') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">{{ __('hospitals.no_admins') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $admins->links() }}</div>
</div>
@endsection
