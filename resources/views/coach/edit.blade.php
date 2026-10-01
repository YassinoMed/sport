@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <!-- Content Header -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">Modifier Coach</h1>
                    <p class="page-subtitle">تعديل مدرب</p>
                </div>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('coach.list') }}">Coachs</a></li>
                        <li class="breadcrumb-item active">{{ $coach->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show modern-alert mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-3"></i>
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="d-flex align-items-center">
                                <div class="header-icon">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div>
                                    <h3 class="card-title-modern">Informations du Coach / معلومات المدرب</h3>
                                    <p class="card-subtitle-modern">Mettez à jour les détails du profil du coach</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <form action="{{ route('coach.update', ['coach' => $coach['id']]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="name">
                                                <i class="fas fa-user me-2 text-primary"></i>Nom Complet / الاسم الكامل
                                            </label>
                                            <input type="text" id="name" name="name" 
                                                   class="form-control-modern @error('name') is-invalid @enderror" 
                                                   value="{{ old('name', $coach->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="phone">
                                                <i class="fas fa-phone me-2 text-primary"></i>Téléphone / الهاتف
                                            </label>
                                            <input type="text" id="phone" name="phone" 
                                                   class="form-control-modern @error('phone') is-invalid @enderror" 
                                                   value="{{ old('phone', $coach->phone) }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="city">
                                                <i class="fas fa-city me-2 text-primary"></i>Ville / المدينة
                                            </label>
                                            <select name="city_id" id="city" class="form-control-modern @error('city_id') is-invalid @enderror" required>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ old('city_id', $coach->city_id) == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Belt -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="belt_name">
                                                <i class="fas fa-ribbon me-2 text-primary"></i>Ceinture / الحزام
                                            </label>
                                            <select name="belt_name" id="belt_name" class="form-control-modern @error('belt_name') is-invalid @enderror">
                                                <option value="">Choisir une ceinture / اختر حزاماً</option>
                                                @foreach(($beltGroups ?? []) as $groupLabel => $options)
                                                    <optgroup label="{{ $groupLabel }}">
                                                        @foreach($options as $value => $label)
                                                            <option value="{{ $value }}" {{ old('belt_name', $coach->belts->last()->belt_name ?? '') == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                            @error('belt_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Training Grade -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="training_grade">
                                                <i class="fas fa-graduation-cap me-2 text-primary"></i>Grade d'entraînement / رتبة التدريب
                                            </label>
                                            <input type="text" id="training_grade" name="training_grade" 
                                                   class="form-control-modern @error('training_grade') is-invalid @enderror" 
                                                   value="{{ old('training_grade', $coach->belts->last()->training_grade ?? '') }}">
                                            @error('training_grade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Profile Image -->
                                    <div class="col-md-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="image">
                                                <i class="fas fa-image me-2 text-primary"></i>Photo de Profil / الصورة الشخصية
                                            </label>
                                            
                                            @if($coach->profile_image)
                                                <div class="mb-3">
                                                    <img src="{{ asset($coach->profile_image) }}" 
                                                         alt="{{ $coach->name }}" 
                                                         class="img-thumbnail shadow-sm" 
                                                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px;">
                                                </div>
                                            @endif

                                            <div class="input-group">
                                                <input type="file" class="form-control-modern @error('profile_image') is-invalid @enderror" 
                                                       id="image" name="profile_image" accept="image/*">
                                            </div>
                                            <small class="text-muted mt-2 d-block">Laissez vide pour conserver l'image actuelle</small>
                                            @error('profile_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-folder-open me-2 text-primary"></i>Dossiers personnalisés / ملفات مخصصة
                                            </label>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label-modern" for="custom_folder_name">
                                                        Nom du dossier / اسم المجلد
                                                    </label>
                                                    <input type="text" id="custom_folder_name" name="custom_folder_name"
                                                           class="form-control-modern @error('custom_folder_name') is-invalid @enderror"
                                                           value="{{ old('custom_folder_name') }}"
                                                           placeholder="Ex: Contrats / عقود">
                                                    @error('custom_folder_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label-modern">
                                                        Fichiers à enregistrer / ملفات للحفظ
                                                    </label>
                                                    <div class="file-upload-container">
                                                        <input type="file" name="custom_files[]" multiple accept=".pdf,image/png,image/jpeg,image/jpg">
                                                        <div class="upload-icon">
                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                        </div>
                                                        <div class="upload-text">
                                                            Cliquez pour sélectionner les fichiers / انقر لاختيار الملفات
                                                        </div>
                                                        <div class="upload-hint">
                                                            PDF, PNG, JPG (Max 10MB)
                                                        </div>
                                                    </div>
                                                    @error('custom_files')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    @error('custom_files.*')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 d-flex justify-content-between align-items-center">
                                    <a href="{{ route('coach.list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-arrow-left me-2"></i>Retour / retour
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                        <i class="fas fa-save me-2"></i>Mettre à jour / تحديث
                                    </button>
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
