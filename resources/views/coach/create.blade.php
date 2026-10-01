@extends('layouts.user-layout')

@section('content')

<div class="breadcrumb-container">
    <a href="{{ route('welcome') }}" class="breadcrumb-link">
        <i class="fas fa-home"></i>
    </a>
    <i class="fas fa-chevron-right breadcrumb-separator"></i>
    <a href="{{ route('coach.list') }}" class="breadcrumb-link">
        <span class="label-fr">Coachs</span>
        <span class="label-ar">المدربين</span>
    </a>
    <i class="fas fa-chevron-right breadcrumb-separator"></i>
    <span class="breadcrumb-current">
        <span class="label-fr">Nouveau Coach</span>
        <span class="label-ar">مدرب جديد</span>
    </span>
</div>

<div class="modern-form-container {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="card-modern">
        <div class="card-header-modern">
            <div class="header-icon-container">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="header-text">
                <h2 class="header-title">
                    <span class="label-fr">Nouveau Coach</span>
                    <span class="label-ar">مدرب جديد</span>
                </h2>
                <p class="header-subtitle">
                    <span class="label-fr">Ajouter un nouveau coach au système</span>
                    <span class="label-ar">إضافة مدرب جديد إلى النظام</span>
                </p>
            </div>
        </div>
        
        <div class="form-body">
            @if ($errors->any())
                <div class="modern-alert alert-danger mb-4">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4 class="alert-title">
                            <span class="label-fr">Erreurs de validation</span>
                            <span class="label-ar">أخطاء في التحقق</span>
                        </h4>
                        <ul class="alert-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('coach.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                
                <div class="form-grid">
                    <!-- Name Field -->
                    <div class="form-group-modern">
                        <label for="name" class="form-label-modern">
                            <span class="label-fr">Nom Complet</span>
                            <span class="label-ar">الاسم الكامل</span>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                type="text" 
                                id="name" 
                                class="form-control-modern @error('name') is-invalid @enderror" 
                                name="name" 
                                required 
                                placeholder="Ex: Ahmed Benali"
                                value="{{ old('name') }}"
                            >
                        </div>
                        @error('name')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group-modern">
                        <label for="phone" class="form-label-modern">
                            <span class="label-fr">Téléphone</span>
                            <span class="label-ar">الهاتف</span>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-phone input-icon"></i>
                            <input 
                                type="text" 
                                id="phone" 
                                class="form-control-modern @error('phone') is-invalid @enderror" 
                                name="phone" 
                                required 
                                placeholder="0612345678"
                                value="{{ old('phone') }}"
                            >
                        </div>
                        @error('phone')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City Field -->
                    <div class="form-group-modern">
                        <label for="city" class="form-label-modern">
                            <span class="label-fr">Ville</span>
                            <span class="label-ar">المدينة</span>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <select name="city_id" id="city" class="form-control-modern select-modern @error('city_id') is-invalid @enderror" required>
                                <option value="" disabled selected>
                                    <span class="label-fr">Choisir une ville...</span>
                                    <span class="label-ar">اختر مدينة...</span>
                                </option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('city_id')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Belt Field -->
                    <div class="form-group-modern">
                        <label for="belt_name" class="form-label-modern">
                            <span class="label-fr">Ceinture</span>
                            <span class="label-ar">الحزام</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-ribbon input-icon"></i>
                            <select name="belt_name" id="belt_name" class="form-control-modern select-modern @error('belt_name') is-invalid @enderror">
                                <option value="" selected>
                                    <span class="label-fr">Choisir une ceinture...</span>
                                    <span class="label-ar">اختر حزاماً...</span>
                                </option>
                                @foreach($beltList as $belt)
                                    <option value="{{ $belt }}" {{ old('belt_name') == $belt ? 'selected' : '' }}>
                                        {{ $belt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('belt_name')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Training Grade Field -->
                    <div class="form-group-modern">
                        <label for="training_grade" class="form-label-modern">
                            <span class="label-fr">Grade d'entraînement</span>
                            <span class="label-ar">رتبة التدريب</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-graduation-cap input-icon"></i>
                            <input 
                                type="text" 
                                id="training_grade" 
                                name="training_grade" 
                                class="form-control-modern @error('training_grade') is-invalid @enderror" 
                                value="{{ old('training_grade') }}"
                                placeholder="Ex: Master"
                            >
                        </div>
                        @error('training_grade')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Profile Image Field -->
                    <div class="form-group-modern full-width">
                        <label class="form-label-modern">
                            <span class="label-fr">Photo de Profil</span>
                            <span class="label-ar">الصورة الشخصية</span>
                        </label>
                        <div class="file-upload-container" id="fileUpload">
                            <input type="file" id="image" name="profile_image" accept="image/png, image/jpeg, image/jpg">
                            <div class="upload-icon">📸</div>
                            <div class="upload-text">
                                <span class="label-fr">Cliquez pour télécharger la photo</span>
                                <span class="label-ar">انقر لتحميل الصورة</span>
                            </div>
                            <div class="upload-hint">
                                <span class="label-fr">PNG, JPG ou JPEG (Max. 2MB)</span>
                                <span class="label-ar">PNG, JPG أو JPEG (كحد أقصى 2 ميجابايت)</span>
                            </div>
                            <div id="fileName" style="margin-top: 15px; color: var(--primary); font-weight: 600;"></div>
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-5">
                    <a href="{{ route('coach.list') }}" class="btn-ghost">
                        <i class="fas fa-times me-2"></i>
                        <span class="label-fr">Annuler</span>
                        <span class="label-ar">إلغاء</span>
                    </a>
                    <button type="submit" class="btn-primary-modern">
                        <i class="fas fa-save me-2"></i>
                        <span class="label-fr">Enregistrer</span>
                        <span class="label-ar">حفظ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const display = document.getElementById('fileName');
        if (fileName) {
            display.textContent = fileName;
            document.getElementById('fileUpload').classList.add('has-file');
        }
    });
</script>
@endpush
@endsection

@endsection
