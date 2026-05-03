@extends('layouts.app')

@section('title', __('medicines.edit_medicine'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.medicines.show', $medicine) }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('medicines.edit_medicine') }}: {{ $medicine->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.medicines.update', $medicine) }}"
                    enctype="multipart/form-data">
                    @csrf @method('PUT')

                    @include('hospital-admin.medicines._form')

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.medicines.show', $medicine) }}" class="btn btn-outline-secondary">
                            {{ __('app.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
