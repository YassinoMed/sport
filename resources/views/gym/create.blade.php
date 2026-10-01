@extends('layouts.user-layout')
@section('content')

<div class="breadcrumb-container">
    <a href="{{ route('welcome') }}" class="breadcrumb-link">
        <i class="fas fa-home"></i>
    </a>
    <i class="fas fa-chevron-right breadcrumb-separator"></i>
    <a href="{{ route('gym.list') }}" class="breadcrumb-link">
        <span class="label-fr">Salles</span>
        <span class="label-ar">القاعات</span>
    </a>
    <i class="fas fa-chevron-right breadcrumb-separator"></i>
    <span class="breadcrumb-current">
        <span class="label-fr">Nouvelle salle</span>
        <span class="label-ar">قاعة جديدة</span>
    </span>
</div>

<div class="modern-form-container {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="card-modern">
        <div class="card-header-modern">
            <div class="header-icon-container">
                <i class="fas fa-dumbbell"></i>
            </div>
            <div class="header-text">
                <h2 class="header-title">
                    <span class="label-fr">Nouvelle salle</span>
                    <span class="label-ar">إنشاء صالة جديدة</span>
                </h2>
                <p class="header-subtitle">
                    <span class="label-fr">Ajouter une nouvelle salle de sport au système</span>
                    <span class="label-ar">إضافة صالة ألعاب رياضية جديدة إلى النظام</span>
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

            <form action="{{ route('gym.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                
                <div class="form-grid">
                    <!-- Name Field -->
                    <div class="form-group-modern">
                        <label for="name" class="form-label-modern">
                            <span class="label-fr">Nom de la salle</span>
                            <span class="label-ar">اسم الصالة</span>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-building input-icon"></i>
                            <input 
                                type="text" 
                                id="name" 
                                class="form-control-modern @error('name') is-invalid @enderror" 
                                name="name" 
                                required 
                                placeholder="Ex: Gym Center"
                                value="{{ old('name') }}"
                            >
                        </div>
                        @error('name')
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
                            <select id="city" name="city_id" class="form-control-modern select-modern @error('city_id') is-invalid @enderror" required>
                                <option value="" disabled selected>
                                    <span class="label-fr">Choisir une ville...</span>
                                    <span class="label-ar">اختر مدينة...</span>
                                </option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('city_id')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gym Manager Field -->
                    <div class="form-group-modern">
                        <label for="user_id" class="form-label-modern">
                            <span class="label-fr">Directeur de salle</span>
                            <span class="label-ar">مدير الصالة</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-user-tie input-icon"></i>
                            <select id="user_id" name="user_id" class="form-control-modern select-modern @error('user_id') is-invalid @enderror">
                                <option value="" disabled selected>
                                    <span class="label-fr">Choisir un directeur...</span>
                                    <span class="label-ar">اختر مديراً...</span>
                                </option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cover Image Field -->
                    <div class="form-group-modern full-width">
                        <label class="form-label-modern">
                            <span class="label-fr">Image de couverture</span>
                            <span class="label-ar">صورة الغلاف</span>
                        </label>
                        <div class="file-upload-container" id="fileUpload">
                            <input type="file" id="image" name="cover_image" accept="image/png, image/jpeg, image/jpg">
                            <div class="upload-icon">🖼️</div>
                            <div class="upload-text">
                                <span class="label-fr">Cliquez pour télécharger l'image</span>
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
                    <a href="{{ route('gym.list') }}" class="btn-ghost">
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

