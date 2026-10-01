@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="/" class="breadcrumb-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('staff.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Personnel</span>
                                    <span class="label-ar">الموظفين</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouveau membre</span>
                                <span class="label-ar">عضو جديد</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-user-tie"></i>
                        <span class="label-fr">Nouveau membre du personnel</span>
                        <span class="label-ar">عضو جديد في الطاقم</span>
                    </h1>
                    <p class="page-subtitle">
                        <span class="label-fr">Créer un nouveau compte pour un membre du personnel</span>
                        <span class="label-ar">إنشاء حساب جديد لعضو في الطاقم</span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('staff.index') }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar">عودة</span>
                    </a>
                </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-body p-4">
                            <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-user"></i>
                                                <span class="label-fr">Nom complet</span>
                                                <span class="label-ar">الاسم الكامل</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control-modern @error('name') is-invalid @enderror" required placeholder="Ex: Jean Dupont">
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-envelope"></i>
                                                <span class="label-fr">Email</span>
                                                <span class="label-ar">البريد الإلكتروني</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control-modern @error('email') is-invalid @enderror" required placeholder="staff@example.com">
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-lock"></i>
                                                <span class="label-fr">Mot de passe</span>
                                                <span class="label-ar">كلمة المرور</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" name="password" class="form-control-modern @error('password') is-invalid @enderror" required>
                                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-check-double"></i>
                                                <span class="label-fr">Confirmer le mot de passe</span>
                                                <span class="label-ar">تأكيد كلمة المرور</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" name="password_confirmation" class="form-control-modern" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-user-tag"></i>
                                                <span class="label-fr">Rôle</span>
                                                <span class="label-ar">الدور</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="role" id="role-select" class="form-select-modern @error('role') is-invalid @enderror" required>
                                                <option value="">
                                                    <span class="label-fr">Choisir un rôle...</span>
                                                    <span class="label-ar">اختر دوراً...</span>
                                                </option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-phone"></i>
                                                <span class="label-fr">Téléphone</span>
                                                <span class="label-ar">الهاتف</span>
                                            </label>
                                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control-modern @error('phone') is-invalid @enderror" placeholder="06XXXXXXXX">
                                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4" id="city-field" style="display: none;">
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-city"></i>
                                                <span class="label-fr">Ville</span>
                                                <span class="label-ar">المدينة</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="city_id" class="form-select-modern @error('city_id') is-invalid @enderror">
                                                <option value="">
                                                    <span class="label-fr">Choisir une ville...</span>
                                                    <span class="label-ar">اختر مدينة...</span>
                                                </option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4" id="gym-field" style="display: none;">
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-dumbbell"></i>
                                                <span class="label-fr">Club</span>
                                                <span class="label-ar">النادي</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="gym_id" class="form-select-modern @error('gym_id') is-invalid @enderror">
                                                <option value="">
                                                    <span class="label-fr">Choisir un club...</span>
                                                    <span class="label-ar">اختر نادياً...</span>
                                                </option>
                                                @foreach($gyms as $gym)
                                                    <option value="{{ $gym->id }}" {{ old('gym_id') == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('gym_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-id-card"></i>
                                                <span class="label-fr">CIN</span>
                                                <span class="label-ar">رقم البطاقة الوطنية</span>
                                            </label>
                                            <input type="text" name="national_id" value="{{ old('national_id') }}" class="form-control-modern @error('national_id') is-invalid @enderror" placeholder="Ex: AB123456">
                                            @error('national_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-image"></i>
                                                <span class="label-fr">Photo de profil</span>
                                                <span class="label-ar">الصورة الشخصية</span>
                                            </label>
                                            <input type="file" name="profile_image" class="form-control-modern @error('profile_image') is-invalid @enderror">
                                            @error('profile_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="{{ route('staff.index') }}" class="btn-ghost px-4">
                                        <span class="label-fr">Annuler</span>
                                        <span class="label-ar">إلغاء</span>
                                    </a>
                                    <button type="submit" class="btn-primary-modern px-5">
                                        <i class="fas fa-save mr-2"></i>
                                        <span class="label-fr">Enregistrer</span>
                                        <span class="label-ar">حفظ</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="modern-card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                <span class="label-fr">Informations</span>
                                <span class="label-ar">معلومات</span>
                            </h5>
                            <p class="text-muted small mb-0">
                                <span class="label-fr">Veuillez remplir tous les champs obligatoires marqués d'un astérisque (*) pour créer un nouveau compte membre du personnel.</span>
                                <span class="label-ar">يرجى ملء جميع الحقول الإلزامية التي تحمل علامة (*) لإنشاء حساب عضو جديد في الطاقم.</span>
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
        toggleFields(); // Initial check
    });
</script>
@endsection
