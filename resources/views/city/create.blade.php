@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <!-- Header Section -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="{{ route('welcome') }}" class="breadcrumb-link">
                                    <i class="fas fa-home me-1"></i>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('city.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Villes</span>
                                    <span class="label-ar">المدن</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouvelle Ville</span>
                                <span class="label-ar">مدينة جديدة</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-city me-2 text-primary"></i>
                        <span class="label-fr">Nouvelle Ville</span>
                        <span class="label-ar" dir="rtl">إضافة مدينة جديدة</span>
                    </h1>
                </div>
                <a href="{{ route('city.list') }}" class="btn-ghost shadow-sm bg-white">
                    <i class="fas fa-arrow-left me-2"></i>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="modern-form-container {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                <div class="modern-card">
                    <div class="card-header-modern">
                        <div class="header-icon-container">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="header-text">
                            <h2 class="header-title">
                                <span class="label-fr">Nouvelle Ville</span>
                                <span class="label-ar">إضافة مدينة جديدة</span>
                            </h2>
                            <p class="header-subtitle">
                                <span class="label-fr">Ajouter une nouvelle ville au système</span>
                                <span class="label-ar">إضافة مدينة جديدة إلى النظام</span>
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

            <form action="{{ route('city.store') }}" method="post">
                @csrf
                
                <div class="form-grid">
                    <!-- Name Field -->
                    <div class="form-group-modern">
                        <label for="name" class="form-label-modern">
                            <span class="label-fr">Nom de la ville</span>
                            <span class="label-ar">اسم المدينة</span>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-map-marked-alt input-icon"></i>
                            <input 
                                type="text" 
                                id="name" 
                                class="form-control-modern @error('name') is-invalid @enderror" 
                                name="name" 
                                required 
                                minlength="4" 
                                maxlength="100"
                                placeholder="Ex: Tunis"
                                value="{{ old('name') }}"
                                autofocus
                            >
                        </div>
                        @error('name')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City Manager Field -->
                    <div class="form-group-modern">
                        <label for="manager_id" class="form-label-modern">
                            <span class="label-fr">Gestionnaire de la ville</span>
                            <span class="label-ar">مدير المدينة</span>
                            <small class="text-muted ms-1">(Optionnel)</small>
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-user-shield input-icon"></i>
                            <select name="manager_id" id="manager_id" class="form-control-modern select-modern @error('manager_id') is-invalid @enderror">
                                <option value="" disabled selected>
                                    <span class="label-fr">Sélectionner un gestionnaire...</span>
                                    <span class="label-ar">اختر مديراً...</span>
                                </option>
                                @foreach ($cityManagers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('manager_id')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions mt-5">
                    <a href="{{ route('city.list') }}" class="btn-ghost">
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
                </div> <!-- /.form-body -->
            </div> <!-- /.modern-card -->
        </div> <!-- /.modern-form-container -->
    </div> <!-- /.container-fluid -->
</section> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection
