@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header Section -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="page-title mb-1">
                        <span class="label-fr">Nouvelle sous-catégorie</span>
                        <span class="label-ar">فئة فرعية جديدة</span>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="/" class="breadcrumb-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('expense-subcategories.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Sous-catégories</span>
                                    <span class="label-ar">الفئات الفرعية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Créer</span>
                                <span class="label-ar">إنشاء</span>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="header-actions">
                    <a href="{{ route('expense-subcategories.index') }}" class="btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar">رجوع</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card-modern shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('expense-subcategories.store') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <!-- Parent Category -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="expense_category_id" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                                <span class="label-fr">Catégorie parente</span>
                                                <span class="label-ar">الفئة الأم</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select id="expense_category_id" name="expense_category_id" class="form-control-modern @error('expense_category_id') is-invalid @enderror" required>
                                                <option value="" disabled selected>
                                                    <span class="label-fr">Choisir une catégorie...</span>
                                                    <span class="label-ar">اختر فئة...</span>
                                                </option>
                                                @foreach($categories as $c)
                                                    <option value="{{ $c->id }}" {{ old('expense_category_id') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('expense_category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Subcategory Name -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="name" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/></svg>
                                                <span class="label-fr">Nom de la sous-catégorie</span>
                                                <span class="label-ar">اسم الفئة الفرعية</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="name" name="name" 
                                                class="form-control-modern @error('name') is-invalid @enderror" 
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="description" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                <span class="label-fr">Description</span>
                                                <span class="label-ar">الوصف</span>
                                            </label>
                                            <textarea id="description" name="description" 
                                                class="form-control-modern" rows="4">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn-primary-modern w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            <span class="label-fr">Enregistrer la sous-catégorie</span>
                                            <span class="label-ar">حفظ الفئة الفرعية</span>
                                        </button>
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
@endsection
