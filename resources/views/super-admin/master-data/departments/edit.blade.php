@extends('layouts.app')

@section('title', 'تعديل قسم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">تعديل: {{ $department->name }}</h4>
    <a href="{{ route('super-admin.master-data.departments.index') }}" class="btn btn-outline-secondary">
        <i class="iconify me-1" data-icon="tabler:arrow-right"></i>
        {{ __('app.back') }}
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('super-admin.master-data.departments.update', $department) }}">
            @csrf
            @method('PATCH')
            @include('super-admin.master-data.departments._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('super-admin.master-data.departments.index') }}" class="btn btn-outline-secondary ms-2">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
