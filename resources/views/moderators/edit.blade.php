@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a href="{{ route('moderators.index') }}" class="btn btn-light rounded-circle me-3 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="page-title">{{ __('messages.edit_moderator') }}</h1>
                    <p class="page-subtitle">{{ __('messages.edit_moderator_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-body p-4">
                            <form action="{{ route('moderators.update', $moderator->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $moderator->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $moderator->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.password') }}</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('messages.leave_blank_password') }}">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.confirm_password') }}</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.role') }} <span class="text-danger">*</span></label>
                                        <select name="role" id="role-select" class="form-select @error('role') is-invalid @enderror" required>
                                            <option value="">{{ __('messages.select_role') }}</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ (old('role') ?? $moderator->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6" id="city-group" style="display:none;">
                                        <label class="form-label fw-bold">{{ __('messages.city') }}</label>
                                        <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                            <option value="">{{ __('messages.select_city') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id', $moderator->city_id) == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" id="gym-group" style="display:none;">
                                        <label class="form-label fw-bold">{{ __('messages.gym') }}</label>
                                        <select name="gym_id" class="form-select @error('gym_id') is-invalid @enderror">
                                            <option value="">{{ __('messages.select_gym') }}</option>
                                            @foreach($gyms as $gym)
                                                <option value="{{ $gym->id }}" {{ old('gym_id', $moderator->gym_id) == $gym->id ? 'selected' : '' }}>
                                                    {{ $gym->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <hr class="mb-4">
                                        <div class="d-flex justify-content-end gap-3">
                                            <a href="{{ route('moderators.index') }}" class="btn btn-light px-4">{{ __('messages.cancel') }}</a>
                                            <button type="submit" class="btn btn-primary px-5">{{ __('messages.update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const cityGroup = document.getElementById('city-group');
        const gymGroup = document.getElementById('gym-group');

        function toggleFields() {
            const role = roleSelect.value;
            cityGroup.style.display = (role === 'cityManager') ? 'block' : 'none';
            gymGroup.style.display = (role === 'gymManager') ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>
@endsection
