@extends('layouts.user-layout')
@section('content')

    <div class="content-wrapper pb-5">
        <!-- Content Header -->
        <section class="content-header py-4">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2">
                                <li class="breadcrumb-item">
                                    <a href="/" class="breadcrumb-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                        <span class="label-fr">Accueil</span>
                                        <span class="label-ar" dir="rtl">الرئيسية</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('gymManager.list') }}" class="breadcrumb-link">
                                        <span class="label-fr">Managers</span>
                                        <span class="label-ar" dir="rtl">المدراء</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span class="label-fr">Modifier</span>
                                    <span class="label-ar" dir="rtl">تعديل</span>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            <span class="label-fr">Modifier le Manager</span>
                            <span class="label-ar" dir="rtl">تعديل بيانات المدير</span>
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Errors Section -->
                        @if ($errors->any())
                            <div class="alert-modern alert-danger mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="modern-card shadow-lg">
                            <div class="card-header-modern card-header-primary">
                                <div class="header-icon bg-white text-primary shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div>
                                    <h3 class="header-title">
                                        <span class="label-fr">Profil du Manager</span>
                                        <span class="label-ar" dir="rtl">ملف المدير</span>
                                    </h3>
                                    <p class="header-subtitle text-white-50">
                                        <span class="label-fr">Modifier les informations de {{ $singleUser->name }}</span>
                                        <span class="label-ar" dir="rtl">تعديل معلومات {{ $singleUser->name }}</span>
                                    </p>
                                </div>
                            </div>

                            <form action="{{ route('gymManager.update', [$singleUser['id']]) }}" method="post" enctype="multipart/form-data" class="p-0">
                                @method('PUT')
                                @csrf
                                <div class="card-body p-4 p-md-5">
                                    <!-- Section 1: Personal Info -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <span class="section-number">1</span>
                                            <h4 class="section-title">
                                                <span class="label-fr">Informations Personnelles</span>
                                                <span class="label-ar" dir="rtl">المعلومات الشخصية</span>
                                            </h4>
                                        </div>
                                        
                                        <div class="row g-4">
                                            <!-- Name -->
                                            <div class="col-md-6">
                                                <div class="form-group-modern">
                                                    <label class="form-label-modern" for="name">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                        <span class="label-fr">Nom Complet</span>
                                                        <span class="label-ar" dir="rtl">الاسم الكامل</span>
                                                    </label>
                                                    <input type="text" id="name" class="form-control-modern" name="name" 
                                                           value="{{ old('name', $singleUser->name) }}" required placeholder="Ex: Jean Dupont">
                                                </div>
                                            </div>

                                            <!-- Phone -->
                                            <div class="col-md-6">
                                                <div class="form-group-modern">
                                                    <label class="form-label-modern" for="phone">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                                        <span class="label-fr">Téléphone</span>
                                                        <span class="label-ar" dir="rtl">رقم الهاتف</span>
                                                    </label>
                                                    <input type="text" id="phone" class="form-control-modern" name="phone" 
                                                           value="{{ old('phone', $singleUser->phone) }}" required placeholder="06XXXXXXXX">
                                                </div>
                                            </div>

                                            <!-- National ID -->
                                            <div class="col-md-6">
                                                <div class="form-group-modern">
                                                    <label class="form-label-modern" for="nationalID">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="3" x2="21" y1="10" y2="10"/><line x1="7" x2="7" y1="15" y2="15"/><line x1="12" x2="12" y1="15" y2="15"/><line x1="17" x2="17" y1="15" y2="15"/></svg>
                                                        <span class="label-fr">ID National</span>
                                                        <span class="label-ar" dir="rtl">الرقم الوطني</span>
                                                    </label>
                                                    <input type="text" id="nationalID" class="form-control-modern" name="national_id" 
                                                           value="{{ old('national_id', $singleUser->national_id) }}" required placeholder="Ex: AB123456">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 2: Profile Image -->
                                    <div class="form-section mb-0">
                                        <div class="section-header">
                                            <span class="section-number">2</span>
                                            <h4 class="section-title">
                                                <span class="label-fr">Photo de Profil</span>
                                                <span class="label-ar" dir="rtl">الصورة الشخصية</span>
                                            </h4>
                                        </div>
                                        
                                        <div class="profile-upload-container">
                                            <div class="current-profile-preview">
                                                <img src="{{ asset($singleUser->profile_image) }}" 
                                                     alt="Current Profile" 
                                                     class="img-circle shadow-sm"
                                                     onerror="this.src='{{ asset('dist/img/avatar5.png') }}'">
                                            </div>
                                            <div class="upload-controls">
                                                <div class="custom-file-modern">
                                                    <input type="file" class="file-input" id="image" name="profile_image" accept="image/*">
                                                    <label class="file-label" for="image">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                        <span class="label-fr">Choisir une nouvelle image...</span>
                                                        <span class="label-ar" dir="rtl">اختر صورة جديدة...</span>
                                                    </label>
                                                </div>
                                                <p class="upload-help">
                                                    <span class="label-fr">JPG, PNG ou GIF. Max 2Mo.</span>
                                                    <span class="label-ar" dir="rtl">JPG ، PNG أو GIF. الحد الأقصى 2 ميجا بايت.</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions-modern">
                                    <a href="{{ route('gymManager.list') }}" class="btn-ghost">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        <span class="label-fr">Annuler</span>
                                        <span class="label-ar" dir="rtl">إلغاء</span>
                                    </a>
                                    <button type="submit" class="btn-success-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        <span class="label-fr">Mettre à jour le Manager</span>
                                        <span class="label-ar" dir="rtl">تحديث بيانات المدير</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .profile-upload-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: var(--radius-md);
            border: 1px dashed var(--gray-300);
        }
        .current-profile-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 4px solid white;
        }
        .upload-controls {
            flex: 1;
        }
        .custom-file-modern {
            position: relative;
            margin-bottom: 0.5rem;
        }
        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }
        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            color: var(--gray-700);
            font-weight: 600;
            transition: var(--transition);
        }
        .file-input:hover + .file-label {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-bg);
        }
        .upload-help {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin: 0;
        }
        @media (max-width: 768px) {
            .profile-upload-container {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>
    @endpush
@endsection
