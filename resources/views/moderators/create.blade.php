@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="/" class="breadcrumb-link">
                                    <i class="fas fa-home"></i>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('moderators.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Modérateurs</span>
                                    <span class="label-ar">المشرفين</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouveau modérateur</span>
                                <span class="label-ar">مشرف جديد</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">{{ __('messages.new_moderator') }}</span>
                        <span class="label-ar">إنشاء مشرف جديد</span>
                    </h1>
                </div>
                <a href="{{ route('moderators.index') }}" class="btn-ghost">
                    <i class="fas fa-arrow-left"></i>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert-modern alert-danger mb-4">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Informations du modérateur</span>
                                    <span class="label-ar">معلومات المشرف</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body-modern p-4">
                            <form action="{{ route('moderators.store') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">{{ __('messages.name') }}</span>
                                                <span class="label-ar">الاسم</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" class="form-control-modern @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">{{ __('messages.email') }}</span>
                                                <span class="label-ar">البريد الإلكتروني</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" class="form-control-modern @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">{{ __('messages.password') }}</span>
                                                <span class="label-ar">كلمة المرور</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" name="password" class="form-control-modern @error('password') is-invalid @enderror" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">{{ __('messages.confirm_password') }}</span>
                                                <span class="label-ar">تأكيد كلمة المرور</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" name="password_confirmation" class="form-control-modern" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">{{ __('messages.role') }}</span>
                                                <span class="label-ar">الدور</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="role" id="role-select" class="form-select-modern @error('role') is-invalid @enderror" required>
                                                <option value="">{{ __('messages.select_role') }}</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="city-group" style="display:none;">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">{{ __('messages.city') }}</span>
                                                <span class="label-ar">المدينة</span>
                                            </label>
                                            <select name="city_id" class="form-select-modern @error('city_id') is-invalid @enderror">
                                                <option value="">{{ __('messages.select_city') }}</option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="gym-group" style="display:none;">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">{{ __('messages.gym') }}</span>
                                                <span class="label-ar">الصالة الرياضية</span>
                                            </label>
                                            <select name="gym_id" class="form-select-modern @error('gym_id') is-invalid @enderror">
                                                <option value="">{{ __('messages.select_gym') }}</option>
                                                @foreach($gyms as $gym)
                                                    <option value="{{ $gym->id }}" {{ old('gym_id') == $gym->id ? 'selected' : '' }}>
                                                        {{ $gym->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('moderators.index') }}" class="btn-ghost">
                                                <span class="label-fr">Annuler</span>
                                                <span class="label-ar">إلغاء</span>
                                            </a>
                                            <button type="submit" class="btn-primary-modern">
                                                <i class="fas fa-save me-2"></i>
                                                <span class="label-fr">Enregistrer</span>
                                                <span class="label-ar">حفظ</span>
                                            </button>
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
