@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('staff.index') }}" class="btn btn-icon btn-outline-secondary rounded-circle">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="page-title">{{ __('messages.edit_staff') }}</h1>
                    <p class="page-subtitle">{{ __('messages.update_staff_member_info') }}</p>
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
                            <form action="{{ route('staff.update', $staff) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4 text-center">
                                    <div class="col-12">
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset($staff->profile_image ?: 'imgs/defaultImg.jpg') }}" alt="" class="rounded-circle border shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                            <div class="position-absolute bottom-0 end-0">
                                                <label for="profile_image" class="btn btn-icon btn-primary btn-sm rounded-circle shadow">
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                                <input type="file" name="profile_image" id="profile_image" class="d-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $staff->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.password') }}</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('messages.leave_blank_to_keep_current') }}">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.confirm_password') }}</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('messages.leave_blank_to_keep_current') }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.role') }} <span class="text-danger">*</span></label>
                                        <select name="role" id="role-select" class="form-select @error('role') is-invalid @enderror" required>
                                            <option value="">{{ __('messages.select_role') }}</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.phone') }}</label>
                                        <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-4" id="city-field" style="display: {{ old('role', $currentRole) === 'cityManager' ? 'block' : 'none' }};">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">{{ __('messages.city') }} <span class="text-danger">*</span></label>
                                        <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                            <option value="">{{ __('messages.select_city') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id', $staff->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-4" id="gym-field" style="display: {{ old('role', $currentRole) === 'gymManager' ? 'block' : 'none' }};">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">{{ __('messages.gym') }} <span class="text-danger">*</span></label>
                                        <select name="gym_id" class="form-select @error('gym_id') is-invalid @enderror">
                                            <option value="">{{ __('messages.select_gym') }}</option>
                                            @foreach($gyms as $gym)
                                                <option value="{{ $gym->id }}" {{ old('gym_id', $staff->gym_id) == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('gym_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('messages.national_id') }}</label>
                                        <input type="text" name="national_id" value="{{ old('national_id', $staff->national_id) }}" class="form-control @error('national_id') is-invalid @enderror">
                                        @error('national_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="{{ route('staff.index') }}" class="btn btn-light px-4">{{ __('messages.cancel') }}</a>
                                    <button type="submit" class="btn btn-primary px-5">{{ __('messages.update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="modern-card">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold mb-3">{{ __('messages.current_status') }}</h5>
                            <div class="mb-4">
                                <span class="badge rounded-pill bg-primary-soft text-primary px-4 py-2 fw-bold fs-6">
                                    {{ ucfirst($currentRole) }}
                                </span>
                            </div>
                            <hr class="opacity-25 mb-4">
                            <p class="text-muted small mb-0">
                                {{ __('messages.staff_edit_info_text') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const cityField = document.getElementById('city-field');
        const gymField = document.getElementById('gym-field');

        function toggleFields() {
            const role = roleSelect.value;
            cityField.style.display = (role === 'cityManager') ? 'block' : 'none';
            gymField.style.display = (role === 'gymManager') ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', toggleFields);
    });
</script>
@endsection
