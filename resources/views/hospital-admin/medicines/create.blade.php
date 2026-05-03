@extends('layouts.app')

@section('title', __('medicines.add_medicine'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.medicines.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('medicines.add_medicine') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.medicines.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    @include('hospital-admin.medicines._form')

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.medicines.index') }}" class="btn btn-outline-secondary">
                            {{ __('app.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
