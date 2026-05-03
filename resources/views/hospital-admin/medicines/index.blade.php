@extends('layouts.app')

@section('title', __('medicines.medicines'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">{{ __('medicines.medicine_catalog') }}</h5>
        <a href="{{ route('hospital-admin.medicines.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>{{ __('medicines.add_medicine') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2">
            <div class="col-12 col-sm-4">
                <input type="text" name="search" class="form-control"
                    placeholder="{{ __('app.search') }}" value="{{ request('search') }}">
            </div>
            <div class="col-6 col-sm-3">
                <select name="category" class="form-select">
                    <option value="">{{ __('medicines.filter_category') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-3">
                <select name="form" class="form-select">
                    <option value="">{{ __('medicines.filter_form') }}</option>
                    @foreach(\App\Enums\MedicineForm::cases() as $f)
                        <option value="{{ $f->value }}" @selected(request('form') === $f->value)>
                            {{ __('medicines.'.$f->value) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-auto">
                <button type="submit" class="btn btn-outline-secondary w-100">{{ __('app.filter') }}</button>
            </div>
            @if(request()->hasAny(['search', 'category', 'form', 'status']))
                <div class="col-6 col-sm-auto">
                    <a href="{{ route('hospital-admin.medicines.index') }}" class="btn btn-outline-danger w-100">{{ __('app.cancel') }}</a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('medicines.name') }}</th>
                    <th>{{ __('medicines.generic_name') }}</th>
                    <th>{{ __('medicines.category') }}</th>
                    <th>{{ __('medicines.form') }}</th>
                    <th>{{ __('medicines.strength') }}</th>
                    <th>{{ __('medicines.requires_prescription') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $medicine->name }}</div>
                            @if($medicine->brand_name)
                                <small class="text-muted">{{ $medicine->brand_name }}</small>
                            @endif
                        </td>
                        <td>{{ $medicine->generic_name ?? '—' }}</td>
                        <td>{{ $medicine->category?->name ?? '—' }}</td>
                        <td>{{ __('medicines.'.$medicine->form->value) }}</td>
                        <td>{{ $medicine->strength ?? '—' }}</td>
                        <td>
                            @if($medicine->requires_prescription)
                                <span class="badge bg-label-warning">{{ __('medicines.prescription_required') }}</span>
                            @else
                                <span class="badge bg-label-success">{{ __('medicines.otc') }}</span>
                            @endif
                            @if($medicine->is_controlled)
                                <span class="badge bg-label-danger">{{ __('medicines.controlled') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($medicine->is_active)
                                <span class="badge bg-label-success">{{ __('app.active') }}</span>
                            @else
                                <span class="badge bg-label-secondary">{{ __('app.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('hospital-admin.medicines.show', $medicine) }}"
                                    class="btn btn-sm btn-outline-info" title="{{ __('app.show') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('hospital-admin.medicines.edit', $medicine) }}"
                                    class="btn btn-sm btn-outline-primary" title="{{ __('app.edit') }}">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form method="POST"
                                    action="{{ route('hospital-admin.medicines.toggle-status', $medicine) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning"
                                        title="{{ $medicine->is_active ? __('app.inactive') : __('app.active') }}">
                                        <i class="bx {{ $medicine->is_active ? 'bx-toggle-right' : 'bx-toggle-left' }}"></i>
                                    </button>
                                </form>
                                <form method="POST"
                                    action="{{ route('hospital-admin.medicines.destroy', $medicine) }}"
                                    onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        title="{{ __('app.delete') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            {{ __('medicines.medicines') }} — {{ __('app.no_records') ?? 'لا توجد سجلات' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($medicines->hasPages())
        <div class="card-footer">
            {{ $medicines->links() }}
        </div>
    @endif
</div>
@endsection
