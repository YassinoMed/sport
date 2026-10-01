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
                                <a href="/" class="breadcrumb-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('combats.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Combats</span>
                                    <span class="label-ar">النزالات</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouveau combat</span>
                                <span class="label-ar">نزال جديد</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Créer un combat</span>
                        <span class="label-ar">إنشاء نزال جديد</span>
                    </h1>
                </div>
                <a href="{{ route('combats.index') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert-modern alert-danger mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Filter Section -->
                    <div class="modern-card mb-4">
                        <div class="card-body-modern p-4">
                            <form method="GET" action="{{ route('combats.create') }}">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="weight_kg">
                                                <span class="label-fr">Filtrer par poids (kg)</span>
                                                <span class="label-ar">تصفية حسب الوزن (كجم)</span>
                                            </label>
                                            <select id="weight_kg" name="weight_kg" class="form-control-modern select2">
                                                <option value="">
                                                    <span class="label-fr">Sélectionner un poids</span>
                                                    <span class="label-ar">اختر الوزن</span>
                                                </option>
                                                @foreach($weights as $w)
                                                    <option value="{{ $w }}" {{ (string)$selectedWeight === (string)$w ? 'selected' : '' }}>
                                                        {{ number_format((float)$w, 2, ',', ' ') }} kg
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn-primary-modern w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                            <span class="label-fr">Filtrer</span>
                                            <span class="label-ar">تصفية</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Match par poids identique</span>
                                    <span class="label-ar">نزال بوزن متماثل</span>
                                </h3>
                            </div>
                        </div>

                        <form action="{{ route('combats.store') }}" method="POST">
                            @csrf
                            <div class="card-body-modern p-4">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="athlete_a_id">
                                                <span class="label-fr">Athlète A</span>
                                                <span class="label-ar">اللاعب أ</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select id="athlete_a_id" name="athlete_a_id" class="form-control-modern select2 @error('athlete_a_id') is-invalid @enderror" required {{ $athletes->count() < 2 ? 'disabled' : '' }}>
                                                <option value="">
                                                    <span class="label-fr">Sélectionner</span>
                                                    <span class="label-ar">اختر</span>
                                                </option>
                                                @foreach($athletes as $a)
                                                    <option value="{{ $a->id }}" {{ (string)old('athlete_a_id') === (string)$a->id ? 'selected' : '' }}>
                                                        {{ $a->name }} ({{ number_format((float)$a->weight_kg, 2, ',', ' ') }} kg)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="athlete_b_id">
                                                <span class="label-fr">Athlète B</span>
                                                <span class="label-ar">اللاعب ب</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select id="athlete_b_id" name="athlete_b_id" class="form-control-modern select2 @error('athlete_b_id') is-invalid @enderror" required {{ $athletes->count() < 2 ? 'disabled' : '' }}>
                                                <option value="">
                                                    <span class="label-fr">Sélectionner</span>
                                                    <span class="label-ar">اختر</span>
                                                </option>
                                                @foreach($athletes as $a)
                                                    <option value="{{ $a->id }}" {{ (string)old('athlete_b_id') === (string)$a->id ? 'selected' : '' }}>
                                                        {{ $a->name }} ({{ number_format((float)$a->weight_kg, 2, ',', ' ') }} kg)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                @if($selectedWeight !== null && $selectedWeight !== '' && $athletes->count() < 2)
                                    <div class="alert-modern alert-warning mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        <span class="label-fr">Pas assez d’athlètes avec ce poids pour créer un combat.</span>
                                        <span class="label-ar">لا يوجد عدد كافٍ من اللاعبين بهذا الوزن لإنشاء نزال.</span>
                                    </div>
                                @endif

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="scheduled_at">
                                                <span class="label-fr">Date & heure (optionnel)</span>
                                                <span class="label-ar">التاريخ والوقت (اختياري)</span>
                                            </label>
                                            <input type="datetime-local" class="form-control-modern @error('scheduled_at') is-invalid @enderror" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') ? \Illuminate\Support\Carbon::parse(old('scheduled_at'))->format('Y-m-d\TH:i') : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="notes">
                                                <span class="label-fr">Notes (optionnel)</span>
                                                <span class="label-ar">ملاحظات (اختياري)</span>
                                            </label>
                                            <textarea class="form-control-modern @error('notes') is-invalid @enderror" id="notes" name="notes" rows="1" placeholder="...">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer-modern bg-light d-flex justify-content-between p-4">
                                <a href="{{ route('combats.index') }}" class="btn-ghost">
                                    <span class="label-fr">Annuler</span>
                                    <span class="label-ar">إلغاء</span>
                                </a>
                                <button type="submit" class="btn-primary-modern" {{ $athletes->count() < 2 ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    <span class="label-fr">Créer</span>
                                    <span class="label-ar">إنشاء</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
