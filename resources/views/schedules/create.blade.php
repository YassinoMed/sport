@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header Section -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="page-title mb-1">
                        <span class="label-fr">Ajouter un horaire</span>
                        <span class="label-ar">إضافة حصة</span>
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
                                <a href="{{ route('schedules.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Planning</span>
                                    <span class="label-ar">البرنامج</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Ajouter</span>
                                <span class="label-ar">إضافة</span>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="header-actions">
                    <a href="{{ route('schedules.index') }}" class="btn-ghost">
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
                            <form action="{{ route('schedules.store') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <!-- Group -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="group_id" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                <span class="label-fr">Groupe</span>
                                                <span class="label-ar">المجموعة</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="group_id" id="group_id" class="form-control-modern @error('group_id') is-invalid @enderror" required>
                                                <option value="">
                                                    <span class="label-fr">Sélectionner un groupe</span>
                                                    <span class="label-ar">اختر مجموعة</span>
                                                </option>
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('group_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Day -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="day" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                <span class="label-fr">Jour</span>
                                                <span class="label-ar">اليوم</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="day" id="day" class="form-control-modern @error('day') is-invalid @enderror" required>
                                                <option value="">
                                                    <span class="label-fr">Sélectionner un jour</span>
                                                    <span class="label-ar">اختر يوماً</span>
                                                </option>
                                                @foreach($days as $day)
                                                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Times -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="start_time" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                <span class="label-fr">Heure de début</span>
                                                <span class="label-ar">وقت البدء</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="time" name="start_time" id="start_time" class="form-control-modern @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="end_time" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                <span class="label-fr">Heure de fin</span>
                                                <span class="label-ar">وقت الانتهاء</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="time" name="end_time" id="end_time" class="form-control-modern @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Location -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="location" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <span class="label-fr">Lieu</span>
                                                <span class="label-ar">المكان</span>
                                            </label>
                                            <input type="text" name="location" id="location" class="form-control-modern @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="Ex: Salle principale / القاعة الرئيسية">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="notes" class="form-label-modern">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                                <span class="label-fr">Notes / Remarques</span>
                                                <span class="label-ar">ملاحظات</span>
                                            </label>
                                            <textarea name="notes" id="notes" class="form-control-modern @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn-primary-modern w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            <span class="label-fr">Enregistrer l'horaire</span>
                                            <span class="label-ar">حفظ الحصة</span>
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
