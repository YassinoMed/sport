@extends('layouts.user-layout')
@section('content')
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
                        <a href="{{ route('course.list') }}" class="breadcrumb-link">
                            <span class="label-fr">Cours</span>
                            <span class="label-ar">الدورات</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="label-fr">Créer un Cours</span>
                        <span class="label-ar">إنشاء دورة</span>
                    </li>
                </ol>
            </nav>
            <h1 class="page-title">
                <i class="fas fa-plus-circle text-primary me-2"></i>
                <span class="label-fr">Créer un Cours</span>
                <span class="label-ar" dir="rtl">إنشاء دورة</span>
            </h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="modern-card">
                <div class="card-header-modern">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        <span class="label-fr">Informations du cours</span>
                        <span class="label-ar">معلومات الدورة</span>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('course.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Nom du cours</span>
                                        <span class="label-ar" dir="rtl">اسم الدورة</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control-modern @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Ex: Judo Débutant">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Capacité maximale (optionnel)</span>
                                        <span class="label-ar" dir="rtl">السعة القصوى (اختياري)</span>
                                    </label>
                                    <input type="number" name="capacity" class="form-control-modern @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" min="1" placeholder="Ex: 20">
                                    <small class="text-muted">Laissez vide pour une capacité illimitée.</small>
                                    @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <span class="label-fr">Description</span>
                                <span class="label-ar" dir="rtl">الوصف</span>
                            </label>
                            <textarea name="description" class="form-control-modern @error('description') is-invalid @enderror" rows="3" placeholder="Détails sur le cours...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Salle (Gym)</span>
                                        <span class="label-ar" dir="rtl">القاعة</span>
                                    </label>
                                    <select name="gym_id" class="form-control-modern select2 @error('gym_id') is-invalid @enderror">
                                        <option value="">Sélectionner une salle (optionnel)</option>
                                        @foreach($gyms as $gym)
                                            <option value="{{ $gym->id }}" {{ old('gym_id') == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('gym_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Coachs</span>
                                        <span class="label-ar" dir="rtl">المدربون</span>
                                    </label>
                                    <select name="coach_ids[]" class="form-control-modern select2 @error('coach_ids') is-invalid @enderror" multiple data-placeholder="Choisir les coachs">
                                        @foreach(($coaches ?? []) as $coach)
                                            <option value="{{ $coach->id }}" {{ in_array($coach->id, (array)old('coach_ids', [])) ? 'selected' : '' }}>{{ $coach->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('coach_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-calendar-day me-2 text-primary"></i>
                                        <span class="label-fr">Date de début</span>
                                        <span class="label-ar" dir="rtl">تاريخ البدء</span>
                                    </label>
                                    <input type="date" name="start_date" class="form-control-modern @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                                        <span class="label-fr">Date de fin</span>
                                        <span class="label-ar" dir="rtl">تاريخ الانتهاء</span>
                                    </label>
                                    <input type="date" name="end_date" class="form-control-modern @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="form-group-modern">
                            <label class="form-label-modern d-block">
                                <span class="label-fr">Mode d'inscription</span>
                                <span class="label-ar" dir="rtl">طريقة التسجيل</span>
                            </label>
                            <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded-3 border">
                                @php($enrollMode = old('enroll_mode', 'groups'))
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_groups" value="groups" {{ $enrollMode === 'groups' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_groups">
                                        <span class="label-fr">Par groupes</span>
                                        <span class="label-ar" dir="rtl">بالمجموعات</span>
                                    </label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_categories" value="categories" {{ $enrollMode === 'categories' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_categories">
                                        <span class="label-fr">Par catégories</span>
                                        <span class="label-ar" dir="rtl">بالفئات</span>
                                    </label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_belts" value="belts" {{ $enrollMode === 'belts' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_belts">
                                        <span class="label-fr">Par ceintures</span>
                                        <span class="label-ar" dir="rtl">بالأحزمة</span>
                                    </label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_individual" value="individual" {{ $enrollMode === 'individual' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_individual">
                                        <span class="label-fr">Individuelle</span>
                                        <span class="label-ar" dir="rtl">فردي</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern" id="enroll_block_individual">
                            <label class="form-label-modern">
                                <span class="label-fr">Étudiants</span>
                                <span class="label-ar" dir="rtl">الطلاب</span>
                            </label>
                            <select name="user_ids[]" class="form-control-modern select2 @error('user_ids') is-invalid @enderror" multiple data-placeholder="Sélectionner les étudiants">
                                @foreach(($users ?? []) as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, (array)old('user_ids', [])) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group-modern" id="enroll_block_groups">
                            <label class="form-label-modern">
                                <span class="label-fr">Groupes</span>
                                <span class="label-ar" dir="rtl">المجموعات</span>
                            </label>
                            <select name="group_ids[]" class="form-control-modern select2 @error('group_ids') is-invalid @enderror" multiple data-placeholder="Sélectionner les groupes">
                                @foreach(($groups ?? []) as $group)
                                    <option value="{{ $group->id }}" {{ in_array($group->id, (array)old('group_ids', [])) ? 'selected' : '' }}>{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('group_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group-modern" id="enroll_block_categories">
                            <label class="form-label-modern">
                                <span class="label-fr">Catégories</span>
                                <span class="label-ar" dir="rtl">الفئات</span>
                            </label>
                            <select name="category_ids[]" class="form-control-modern select2 @error('category_ids') is-invalid @enderror" multiple data-placeholder="Sélectionner les catégories">
                                @foreach(($categories ?? []) as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, (array)old('category_ids', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group-modern" id="enroll_block_belts">
                            <label class="form-label-modern">
                                <span class="label-fr">Ceintures</span>
                                <span class="label-ar" dir="rtl">الأحزمة</span>
                            </label>
                            <select name="belt_names[]" class="form-control-modern select2 @error('belt_names') is-invalid @enderror" multiple data-placeholder="Sélectionner les ceintures">
                                @foreach(($beltNames ?? []) as $beltName)
                                    <option value="{{ $beltName }}" {{ in_array($beltName, (array)old('belt_names', [])) ? 'selected' : '' }}>{{ $beltName }}</option>
                                @endforeach
                            </select>
                            @error('belt_names')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div id="schedules_section" class="mt-4">
                            <div class="modern-card border-primary shadow-sm">
                                <div class="card-header-modern bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span class="label-fr">Planning hebdomadaire</span>
                                        <span class="label-ar" dir="rtl">الجدول الأسبوعي</span>
                                    </h5>
                                    <button type="button" class="btn btn-sm btn-light" id="add-schedule-row">
                                        <i class="fas fa-plus me-1"></i>
                                        <span class="label-fr">Ajouter un créneau</span>
                                        <span class="label-ar" dir="rtl">إضافة حصة</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="schedules-container">
                                        {{-- Will be populated by JS --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-modern mt-5">
                            <button type="submit" class="btn btn-primary-modern">
                                <i class="fas fa-save me-2"></i>
                                <span class="label-fr">Créer le cours</span>
                                <span class="label-ar" dir="rtl">إنشاء دورة</span>
                            </button>
                            <a href="{{ route('course.list') }}" class="btn btn-secondary-modern">
                                <i class="fas fa-times me-2"></i>
                                <span class="label-fr">Annuler</span>
                                <span class="label-ar" dir="rtl">إلغاء</span>
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
    /* ============================================
   MODERN COURSE CREATION FORM STYLES
   Enhanced UX without backend changes
   ============================================ */

/* === GLOBAL VARIABLES === */
:root {
    --primary: #6366f1;
    --primary-hover: #4f46e5;
    --primary-light: #818cf8;
    --primary-bg: #eef2ff;
    --success: #10b981;
    --success-hover: #059669;
    --danger: #ef4444;
    --danger-hover: #dc2626;
    --warning: #f59e0b;
    --info: #06b6d4;
    --white: #ffffff;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --radius-sm: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --radius-xl: 1.25rem;
    --radius-2xl: 1.5rem;
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* === CONTENT WRAPPER === */
.content-wrapper {
    background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
    min-height: 100vh;
    padding-bottom: 3rem;
}

/* === BREADCRUMB === */
.breadcrumb {
    background: var(--white);
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    margin-bottom: 1.5rem;
}

.breadcrumb-item {
    font-size: 0.875rem;
    font-weight: 500;
}

.breadcrumb-link {
    color: var(--gray-600);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
}

.breadcrumb-link:hover {
    color: var(--primary);
    background: var(--primary-bg);
}

.breadcrumb-link i {
    font-size: 0.875rem;
}

.breadcrumb-item.active {
    color: var(--gray-900);
    font-weight: 600;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--gray-400);
    font-size: 1.25rem;
    padding: 0 0.5rem;
}

/* === PAGE TITLE === */
.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--gray-900);
    margin: 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    letter-spacing: -0.025em;
}

.page-title i {
    font-size: 1.75rem;
}

/* === MODERN CARD === */
.modern-card {
    background: var(--white);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
}

.modern-card:hover {
    box-shadow: var(--shadow-xl);
}

.card-header-modern {
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
    border-bottom: 2px solid var(--gray-200);
    padding: 1.5rem 2rem;
}

.card-header-modern.bg-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    border-bottom: none;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-header-modern.bg-primary .card-title,
.card-header-modern.bg-primary h5 {
    color: var(--white) !important;
}

.card-body {
    padding: 2rem;
}

/* === FORM GROUPS === */
.form-group-modern {
    margin-bottom: 1.75rem;
}

.form-label-modern {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    display: block;
    letter-spacing: 0.025em;
    transition: var(--transition);
}

.form-group-modern:focus-within .form-label-modern {
    color: var(--primary);
}

.form-group-modern:focus-within .form-label-modern i {
    transform: scale(1.1);
}

.form-label-modern i {
    transition: var(--transition);
}

.form-label-modern .text-danger {
    color: var(--danger);
    margin-left: 0.25rem;
}

/* === FORM CONTROLS === */
.form-control-modern {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    font-weight: 500;
    color: var(--gray-900);
    background: var(--white);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    transition: var(--transition);
    box-shadow: var(--shadow-xs);
}

.form-control-modern:hover {
    border-color: var(--gray-300);
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-bg);
    background: var(--white);
}

.form-control-modern::placeholder {
    color: var(--gray-400);
    font-weight: 400;
}

.form-control-modern.is-invalid {
    border-color: var(--danger);
}

.form-control-modern.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

textarea.form-control-modern {
    resize: vertical;
    min-height: 120px;
}

/* === DATE INPUT STYLING === */
input[type="date"].form-control-modern {
    position: relative;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%236366f1' viewBox='0 0 24 24'%3E%3Cpath d='M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM19 8H5V6h14v2z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.25rem;
    padding-right: 3rem;
    cursor: pointer;
    transition: var(--transition);
}

[dir="rtl"] input[type="date"].form-control-modern {
    background-position: left 1rem center;
    padding-right: 1rem;
    padding-left: 3rem;
}

input[type="date"].form-control-modern:hover {
    border-color: var(--primary-light);
    background-color: var(--gray-50);
    transform: translateY(-1px);
}

input[type="date"].form-control-modern:focus {
    border-color: var(--primary);
    background-color: var(--white);
    box-shadow: 0 0 0 4px var(--primary-bg);
}

input[type="date"].form-control-modern::-webkit-calendar-picker-indicator {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

input[type="date"].form-control-modern::-webkit-datetime-edit {
    font-weight: 600;
    color: var(--gray-700);
}

input[type="date"].form-control-modern::-webkit-datetime-edit-fields-wrapper {
    padding: 0;
}

/* === TIME INPUT STYLING === */
input[type="time"].form-control-modern,
.schedule-row input[type="time"] {
    position: relative;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%236366f1' viewBox='0 0 24 24'%3E%3Cpath d='M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.1rem;
    padding-right: 2.5rem;
    cursor: pointer;
    transition: var(--transition);
}

[dir="rtl"] input[type="time"].form-control-modern,
[dir="rtl"] .schedule-row input[type="time"] {
    background-position: left 0.75rem center;
    padding-right: 0.875rem;
    padding-left: 2.5rem;
}

input[type="time"].form-control-modern:hover,
.schedule-row input[type="time"]:hover {
    border-color: var(--primary-light);
    background-color: var(--gray-50);
    transform: translateY(-1px);
}

input[type="time"]::-webkit-calendar-picker-indicator {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

/* === SELECT2 INTEGRATION === */
.select2-container--bootstrap4 .select2-selection {
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    min-height: 46px;
    transition: var(--transition);
    box-shadow: var(--shadow-xs);
}

.select2-container--bootstrap4 .select2-selection:hover {
    border-color: var(--gray-300);
}

.select2-container--bootstrap4.select2-container--focus .select2-selection,
.select2-container--bootstrap4.select2-container--open .select2-selection {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-bg);
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    padding: 0.5rem 1rem;
    color: var(--gray-900);
    font-weight: 500;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
    padding: 0.25rem 0.5rem;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
    background: var(--primary);
    border: none;
    border-radius: var(--radius-sm);
    color: var(--white);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0.25rem;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
    color: var(--white);
    opacity: 0.8;
    margin-right: 0.5rem;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
    opacity: 1;
}

/* === CUSTOM RADIO BUTTONS === */
.custom-radio {
    position: relative;
    padding: 0;
}

.custom-radio .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-top: 0;
    border: 2px solid var(--gray-300);
    cursor: pointer;
    transition: var(--transition);
}

.custom-radio .form-check-input:hover {
    border-color: var(--primary);
}

.custom-radio .form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-bg);
}

.custom-radio .form-check-label {
    margin-left: 0.5rem;
    cursor: pointer;
    color: var(--gray-700);
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
}

.custom-radio .form-check-input:checked ~ .form-check-label {
    color: var(--primary);
    background: var(--primary-bg);
}

/* === ENROLLMENT MODE SECTION === */
.bg-light.rounded-3.border {
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%) !important;
    border: 2px solid var(--gray-200) !important;
    border-radius: var(--radius-xl) !important;
    padding: 1.5rem !important;
    box-shadow: var(--shadow-sm);
}

/* === SCHEDULE SECTION === */
.modern-card.border-primary {
    border: 2px solid var(--primary) !important;
}

.schedule-row {
    background: linear-gradient(135deg, var(--white) 0%, var(--gray-50) 100%);
    border: 2px solid var(--gray-200) !important;
    border-radius: var(--radius-lg) !important;
    padding: 1.25rem !important;
    margin-bottom: 1rem !important;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.schedule-row:hover {
    border-color: var(--primary) !important;
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.schedule-row .form-label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.schedule-row .form-control,
.schedule-row .form-control-sm {
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: 0.625rem 0.875rem;
    font-weight: 500;
    transition: var(--transition);
}

.schedule-row .form-control:focus,
.schedule-row .form-control-sm:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-bg);
}

.schedule-row .btn-outline-danger {
    border: 2px solid var(--danger);
    color: var(--danger);
    border-radius: var(--radius-md);
    padding: 0.625rem;
    transition: var(--transition);
}

.schedule-row .btn-outline-danger:hover {
    background: var(--danger);
    color: var(--white);
    transform: scale(1.05);
}

/* === BUTTONS === */
.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 2rem;
    font-size: 1rem;
    font-weight: 700;
    color: var(--white);
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    border: none;
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    letter-spacing: 0.025em;
}

.btn-primary-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    color: var(--white);
}

.btn-primary-modern:active {
    transform: translateY(-1px);
}

.btn-secondary-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 2rem;
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-700);
    background: var(--white);
    border: 2px solid var(--gray-300);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    text-decoration: none;
}

.btn-secondary-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    color: var(--gray-900);
    transform: translateY(-2px);
}

.btn-light {
    background: var(--white);
    border: 2px solid var(--white);
    color: var(--primary);
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.btn-light:hover {
    background: var(--gray-50);
    color: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* === FORM ACTIONS === */
.form-actions-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-top: 2rem;
    border-top: 2px solid var(--gray-200);
    margin-top: 2rem;
}

/* === INVALID FEEDBACK === */
.invalid-feedback {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: var(--danger);
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    background: rgba(239, 68, 68, 0.1);
    border-radius: var(--radius-sm);
    border-left: 3px solid var(--danger);
}

/* === HELPER TEXT === */
.text-muted,
small.text-muted {
    color: var(--gray-500) !important;
    font-size: 0.8125rem;
    margin-top: 0.375rem;
    display: block;
    font-weight: 500;
}

/* === HR DIVIDER === */
hr {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gray-200), transparent);
    margin: 2rem 0;
}

/* === ANIMATIONS === */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        max-height: 1000px;
        transform: translateY(0);
    }
}

.schedule-row {
    animation: fadeIn 0.3s ease-out;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-actions-modern {
        flex-direction: column;
    }
    
    .btn-primary-modern,
    .btn-secondary-modern {
        width: 100%;
    }
    
    .schedule-row {
        padding: 1rem !important;
    }
    
    .schedule-row .col-md-1,
    .schedule-row .col-md-2,
    .schedule-row .col-md-3 {
        width: 100%;
        margin-bottom: 0.75rem;
    }
    
    .bg-light.rounded-3.border {
        flex-direction: column !important;
        gap: 1rem !important;
    }
}

/* === DARK MODE SUPPORT === */
.dark-mode {
    --white: #0f172a;
    --gray-50: #1e293b;
    --gray-100: #334155;
    --gray-200: #475569;
    --gray-300: #64748b;
    --gray-400: #94a3b8;
    --gray-500: #cbd5e1;
    --gray-600: #e2e8f0;
    --gray-700: #f1f5f9;
    --gray-800: #f8fafc;
    --gray-900: #ffffff;
}

.dark-mode .content-wrapper {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
}

.dark-mode .modern-card,
.dark-mode .breadcrumb,
.dark-mode .schedule-row {
    background: #1e293b;
    border-color: #334155 !important;
}

.dark-mode .card-header-modern {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-bottom-color: #334155;
}

.dark-mode .form-control-modern,
.dark-mode .schedule-row .form-control {
    background: #0f172a;
    border-color: #334155;
    color: #f1f5f9;
}

.dark-mode .form-control-modern:focus,
.dark-mode .schedule-row .form-control:focus {
    background: #1e293b;
}

.dark-mode .page-title,
.dark-mode .card-title,
.dark-mode .form-label-modern,
.dark-mode .breadcrumb-item.active {
    color: #f1f5f9;
}

.dark-mode .breadcrumb-link {
    color: #cbd5e1;
}

.dark-mode .btn-secondary-modern {
    background: #1e293b;
    border-color: #475569;
    color: #f1f5f9;
}

.dark-mode .btn-secondary-modern:hover {
    background: #334155;
    border-color: #64748b;
}

/* === ACCESSIBILITY === */
*:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* === UTILITY CLASSES === */
.fs-13 {
    font-size: 0.8125rem;
}

.shadow-xs {
    box-shadow: var(--shadow-xs);
}

.gap-4 {
    gap: 1.5rem;
}

/* === LABEL BILINGUAL SUPPORT === */
.label-fr,
.label-ar {
    display: inline-block;
}

/* Hide Arabic by default, show French */
.label-ar {
    display: none;
}

/* If RTL is active, reverse */
[dir="rtl"] .label-fr {
    display: none;
}

[dir="rtl"] .label-ar {
    display: inline-block;
}
</style>
@endpush
@push('scripts')
<script>
    $(function () {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Sélectionner...",
            allowClear: true,
            width: '100%'
        });

        const modeRadios = $('input[name="enroll_mode"]');
        const blocks = {
            'groups': $('#enroll_block_groups'),
            'categories': $('#enroll_block_categories'),
            'belts': $('#enroll_block_belts'),
            'individual': $('#enroll_block_individual')
        };
        const schedulesSection = $('#schedules_section');

        function toggleEnrollBlocks(animate = true) {
            const mode = $('input[name="enroll_mode"]:checked').val();
            
            // Toggle enrollment blocks with animation
            Object.keys(blocks).forEach(key => {
                const block = blocks[key];
                if (key === mode) {
                    if (animate) block.slideDown(300); else block.show();
                    block.find('select, input').prop('disabled', false);
                } else {
                    if (animate) block.slideUp(300); else block.hide();
                    block.find('select, input').prop('disabled', true);
                }
            });
            
            // Show/Hide schedules section based on mode
            if (mode === 'groups') {
                if (animate) schedulesSection.slideDown(300); else schedulesSection.show();
            } else {
                if (animate) schedulesSection.slideUp(300); else schedulesSection.hide();
            }
        }

        modeRadios.on('change', function() {
            toggleEnrollBlocks(true);
        });

        // Initial toggle without animation
        toggleEnrollBlocks(false);
    });

    (function () {
        var days = @json($days ?? []);
        var container = document.getElementById('schedules-container');
        var addBtn = document.getElementById('add-schedule-row');
        
        if (!container || !addBtn) return;

        function nextIndex() {
            var rows = container.querySelectorAll('.schedule-row');
            var max = -1;
            rows.forEach(function (row) {
                var idx = parseInt(row.getAttribute('data-index') || '-1', 10);
                if (!isNaN(idx) && idx > max) max = idx;
            });
            return max + 1;
        }

        function buildDayOptions() {
            var options = '<option value=""></option>';
            for (var i = 0; i < days.length; i++) {
                options += '<option value="' + String(days[i]).replace(/"/g, '&quot;') + '">' + days[i] + '</option>';
            }
            return options;
        }

        function addRow(animate = true) {
            var i = nextIndex();
            var row = document.createElement('div');
            row.className = 'row g-2 align-items-end schedule-row mb-3 p-3 border rounded-3 bg-white shadow-xs';
            if (animate) row.style.display = 'none';
            row.setAttribute('data-index', String(i));
            row.innerHTML =
                '<div class="col-md-2">' +
                    '<label class="form-label fs-13">' +
                        '<span class="label-fr">Jour</span>' +
                        '<span class="label-ar" dir="rtl">اليوم</span>' +
                    '</label>' +
                    '<select name="schedules[' + i + '][day]" class="form-control form-control-sm">' +
                        buildDayOptions() +
                    '</select>' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<label class="form-label fs-13">' +
                        '<span class="label-fr">Début</span>' +
                        '<span class="label-ar" dir="rtl">البداية</span>' +
                    '</label>' +
                    '<input type="time" name="schedules[' + i + '][start_time]" class="form-control form-control-sm">' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<label class="form-label fs-13">' +
                        '<span class="label-fr">Fin</span>' +
                        '<span class="label-ar" dir="rtl">النهاية</span>' +
                    '</label>' +
                    '<input type="time" name="schedules[' + i + '][end_time]" class="form-control form-control-sm">' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<label class="form-label fs-13">' +
                        '<span class="label-fr">Lieu</span>' +
                        '<span class="label-ar" dir="rtl">المكان</span>' +
                    '</label>' +
                    '<input type="text" name="schedules[' + i + '][location]" class="form-control form-control-sm" placeholder="Salle...">' +
                '</div>' +
                '<div class="col-md-3">' +
                    '<label class="form-label fs-13">' +
                        '<span class="label-fr">Notes</span>' +
                        '<span class="label-ar" dir="rtl">ملاحظات</span>' +
                    '</label>' +
                    '<input type="text" name="schedules[' + i + '][notes]" class="form-control form-control-sm" placeholder="Remarques...">' +
                '</div>' +
                '<div class="col-md-1">' +
                    '<button type="button" class="btn btn-outline-danger btn-sm w-100 remove-schedule-row"><i class="fas fa-trash"></i></button>' +
                '</div>';
            container.appendChild(row);
            if (animate) $(row).fadeIn(300);
        }

        addBtn.addEventListener('click', function() {
            addRow(true);
        });
        
        container.addEventListener('click', function (e) {
            var btn = e.target.closest('.remove-schedule-row');
            if (btn) {
                var row = btn.closest('.schedule-row');
                $(row).fadeOut(300, function() {
                    row.remove();
                });
            }
        });

        // Add initial row if empty
        if (container.children.length === 0) {
            addRow(false);
        }
    })();
</script>
@endpush
@endsection
