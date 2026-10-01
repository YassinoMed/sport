@extends('layouts.user-layout')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper pb-4">
        <section class="content-header">
            <div class="container-fluid">
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
                            <a href="{{ route('cityManager.list') }}" class="breadcrumb-link">
                                <span class="label-fr">Gestionnaires de Ville</span>
                                <span class="label-ar">مدراء المدن</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <span class="label-fr">Nouveau Gestionnaire</span>
                            <span class="label-ar">مدير جديد</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="fas fa-user-shield text-primary me-2"></i>
                    <span class="label-fr">Nouveau Gestionnaire de Ville</span>
                    <span class="label-ar" dir="rtl">مدير مدينة جديد</span>
                </h1>
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert-modern alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="modern-card">
                    <div class="card-header-modern">
                        <h3 class="card-title">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span class="label-fr">Ajouter un gestionnaire</span>
                            <span class="label-ar">إضافة مدير</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cityManager.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="name">
                                            <span class="label-fr">Nom Complet</span>
                                            <span class="label-ar">الاسم الكامل</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" 
                                            class="form-control-modern @error('name') is-invalid @enderror" 
                                            placeholder="Ex: Mourad Benyahya" required value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="email">
                                            <span class="label-fr">Email</span>
                                            <span class="label-ar">البريد الإلكتروني</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" 
                                            class="form-control-modern @error('email') is-invalid @enderror" 
                                            placeholder="email@exemple.com" required value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="password">
                                            <span class="label-fr">Mot de passe</span>
                                            <span class="label-ar">كلمة المرور</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" id="password" name="password" 
                                            class="form-control-modern @error('password') is-invalid @enderror" 
                                            placeholder="••••••••" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="national_id">
                                            <span class="label-fr">Identifiant National</span>
                                            <span class="label-ar">الرقم الوطني</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="national_id" name="national_id" 
                                            class="form-control-modern @error('national_id') is-invalid @enderror" 
                                            placeholder="10-17 chiffres" required value="{{ old('national_id') }}">
                                        @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="image">
                                            <span class="label-fr">Photo de Profil</span>
                                            <span class="label-ar">الصورة الشخصية</span>
                                        </label>
                                        <input type="file" id="image" name="profile_image" 
                                            class="form-control-modern @error('profile_image') is-invalid @enderror" 
                                            accept="image/*">
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions-modern mt-4">
                                <button type="submit" class="btn btn-primary-modern">
                                    <i class="fas fa-save me-2"></i>
                                    <span class="label-fr">Enregistrer</span>
                                    <span class="label-ar">حفظ</span>
                                </button>
                                <a href="{{ route('cityManager.list') }}" class="btn btn-secondary-modern">
                                    <i class="fas fa-times me-2"></i>
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
@endsection
