@extends('layouts.user-layout')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper pb-5">
        <!-- Content Header (Page header) -->
    <div class="breadcrumb-container {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="breadcrumb-title">
                        <i class="fas fa-user-tie icon-gradient"></i>
                        <span class="label-fr">Ajouter un Manager</span>
                        <span class="label-ar">إضافة مدير</span>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb-modern">
                            <li class="breadcrumb-item-modern">
                                <a href="/">
                                    <i class="fas fa-home"></i>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item-modern">
                                <a href="{{ route('gymManager.list') }}">
                                    <span class="label-fr">Managers</span>
                                    <span class="label-ar">المدراء</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item-modern active">
                                <span class="label-fr">Nouveau</span>
                                <span class="label-ar">جديد</span>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">
                    <a href="{{ route('gymManager.list') }}" class="btn-modern btn-modern-light">
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar">رجوع</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="modern-form-container {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                @if ($errors->any())
                    <div class="modern-alert modern-alert-danger mb-4">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-content">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="header-main">
                            <i class="fas fa-id-card header-icon"></i>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Profil du Manager</span>
                                    <span class="label-ar">ملف المدير</span>
                                </h3>
                                <p class="header-subtitle">
                                    <span class="label-fr">Veuillez remplir les informations ci-dessous</span>
                                    <span class="label-ar">يرجى ملء المعلومات أدناه</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('gymManager.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body-modern">
                            <div class="form-grid">
                                <!-- Nom Complet -->
                                <div class="form-group-modern">
                                    <label class="label-modern" for="name">
                                        <span class="label-fr">Nom Complet</span>
                                        <span class="label-ar">الاسم الكامل</span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-group-modern has-icon">
                                        <i class="fas fa-user input-icon"></i>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            class="form-control-modern @error('name') is-invalid @enderror" 
                                            name="name" 
                                            required 
                                            placeholder="Ex: Jean Dupont"
                                            value="{{ old('name') }}"
                                            autofocus
                                        >
                                    </div>
                                    @error('name')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group-modern">
                                    <label class="label-modern" for="email">
                                        <span class="label-fr">Email</span>
                                        <span class="label-ar">البريد الإلكتروني</span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-group-modern has-icon">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            class="form-control-modern @error('email') is-invalid @enderror" 
                                            name="email" 
                                            required 
                                            placeholder="manager@example.com"
                                            value="{{ old('email') }}"
                                        >
                                    </div>
                                    @error('email')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group-modern">
                                    <label class="label-modern" for="password">
                                        <span class="label-fr">Mot de passe</span>
                                        <span class="label-ar">كلمة المرور</span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-group-modern has-icon">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input 
                                            type="password" 
                                            id="password" 
                                            class="form-control-modern @error('password') is-invalid @enderror" 
                                            name="password" 
                                            required
                                        >
                                    </div>
                                    @error('password')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Téléphone -->
                                <div class="form-group-modern">
                                    <label class="label-modern" for="phone">
                                        <span class="label-fr">Téléphone</span>
                                        <span class="label-ar">رقم الهاتف</span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-group-modern has-icon">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input 
                                            type="text" 
                                            id="phone" 
                                            class="form-control-modern @error('phone') is-invalid @enderror" 
                                            name="phone" 
                                            required 
                                            placeholder="06XXXXXXXX"
                                            value="{{ old('phone') }}"
                                        >
                                    </div>
                                    @error('phone')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- National ID -->
                                <div class="form-group-modern">
                                    <label class="label-modern" for="national_id">
                                        <span class="label-fr">ID National</span>
                                        <span class="label-ar">الرقم الوطني</span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-group-modern has-icon">
                                        <i class="fas fa-id-card input-icon"></i>
                                        <input 
                                            type="text" 
                                            id="national_id" 
                                            class="form-control-modern @error('national_id') is-invalid @enderror" 
                                            name="national_id" 
                                            required 
                                            placeholder="Entre 10 et 17 chiffres"
                                            value="{{ old('national_id') }}"
                                        >
                                    </div>
                                    @error('national_id')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Photo de Profil -->
                                <div class="form-group-modern">
                                    <label class="label-modern">
                                        <span class="label-fr">Photo de Profil</span>
                                        <span class="label-ar">الصورة الشخصية</span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="file-upload-container" id="fileUpload">
                                        <input type="file" id="image" name="profile_image" accept="image/png, image/jpeg, image/jpg" required>
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
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
                                    @error('profile_image')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer-modern">
                            <button type="submit" class="btn-modern btn-modern-primary">
                                <i class="fas fa-save"></i>
                                <span class="label-fr">Enregistrer le Manager</span>
                                <span class="label-ar">حفظ المدير</span>
                            </button>
                            <a href="{{ route('gymManager.list') }}" class="btn-modern btn-modern-light">
                                <span class="label-fr">Annuler</span>
                                <span class="label-ar">إلغاء</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    /* Custom styles for this page if needed */
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileNameDisplay = document.getElementById('fileName');
        if (fileName) {
            fileNameDisplay.textContent = fileName;
            document.getElementById('fileUpload').classList.add('active');
        } else {
            fileNameDisplay.textContent = '';
            document.getElementById('fileUpload').classList.remove('active');
        }
    });
</script>
@endpush
@endsection
