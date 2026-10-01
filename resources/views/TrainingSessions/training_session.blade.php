@extends('layouts.user-layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
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
                                    <span class="label-ar" dir="rtl">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('TrainingSessions.listSessions') }}" class="breadcrumb-link">
                                    <span class="label-fr">Séances</span>
                                    <span class="label-ar" dir="rtl">الحصص</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouvelle séance</span>
                                <span class="label-ar" dir="rtl">حصة جديدة</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Nouvelle séance</span>
                        <span class="label-ar" dir="rtl">حصة تدريب جديدة</span>
                    </h1>
                </div>
                <a href="{{ route('TrainingSessions.listSessions') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar" dir="rtl">العودة للقائمة</span>
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

            <div class="row g-4">
                <!-- Single Session Form -->
                <div class="col-lg-6">
                    <form action="{{route('TrainingSessions.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modern-card h-100">
                            <div class="card-header-modern">
                                <div class="header-icon header-icon-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                </div>
                                <div>
                                    <h3 class="header-title">
                                        <span class="label-fr">Créer une séance unique</span>
                                        <span class="label-ar" dir="rtl">إنشاء حصة واحدة</span>
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="card-body-modern p-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="name">
                                                <span class="label-fr">Nom de la séance</span>
                                                <span class="label-ar" dir="rtl">اسم الحصة</span>
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control-modern" placeholder="Ex: Musculation" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="day">
                                                <span class="label-fr">Jour</span>
                                                <span class="label-ar" dir="rtl">اليوم</span>
                                            </label>
                                            <input type="date" id="day" name="day" class="form-control-modern" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="coach">
                                                <span class="label-fr">Coach</span>
                                                <span class="label-ar" dir="rtl">المدرب</span>
                                            </label>
                                            <select id="coach" name="user_id" class="form-control-modern custom-select" required>
                                                <option value="" disabled selected>
                                                    <span class="label-fr">Choisir un coach...</span>
                                                    <span class="label-ar" dir="rtl">اختر مدرباً...</span>
                                                </option>
                                                @foreach ($coaches as $coach)
                                                    <option value="{{$coach->id}}">{{ $coach->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="starts_at">
                                                <span class="label-fr">Début</span>
                                                <span class="label-ar" dir="rtl">البداية</span>
                                            </label>
                                            <input type="time" id="starts_at" name="starts_at" class="form-control-modern" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="finishes_at">
                                                <span class="label-fr">Fin</span>
                                                <span class="label-ar" dir="rtl">النهاية</span>
                                            </label>
                                            <input type="time" id="finishes_at" name="finishes_at" class="form-control-modern" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer-modern bg-light d-flex justify-content-end p-4 mt-auto">
                                <button type="submit" class="btn-primary-modern">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    <span class="label-fr">Enregistrer</span>
                                    <span class="label-ar" dir="rtl">حفظ</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Monthly Planning Form -->
                <div class="col-lg-6">
                    <form action="{{ route('TrainingSessions.plan') }}" method="POST">
                        @csrf
                        <div class="modern-card h-100">
                            <div class="card-header-modern">
                                <div class="header-icon header-icon-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <div>
                                    <h3 class="header-title">
                                        <span class="label-fr">Planifier sur un mois</span>
                                        <span class="label-ar" dir="rtl">تخطيط شهري</span>
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="card-body-modern p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Nom</span>
                                                <span class="label-ar" dir="rtl">الاسم</span>
                                            </label>
                                            <input type="text" name="name" class="form-control-modern" placeholder="Ex: MMA Session" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Coach</span>
                                                <span class="label-ar" dir="rtl">المدرب</span>
                                            </label>
                                            <select name="user_id" class="form-control-modern" required>
                                                <option value="" disabled selected>
                                                    <span class="label-fr">Choisir un coach...</span>
                                                    <span class="label-ar" dir="rtl">اختر مدرباً...</span>
                                                </option>
                                                @foreach ($coaches as $coach)
                                                    <option value="{{$coach->id}}">{{ $coach->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Mois</span>
                                                <span class="label-ar" dir="rtl">الشهر</span>
                                            </label>
                                            <input type="month" name="month" class="form-control-modern" value="{{ date('Y-m') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Jours</span>
                                                <span class="label-ar" dir="rtl">الأيام</span>
                                            </label>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                @php
                                                    $days = [
                                                        'Monday' => ['fr' => 'Lun', 'ar' => 'الإثنين'],
                                                        'Tuesday' => ['fr' => 'Mar', 'ar' => 'الثلاثاء'],
                                                        'Wednesday' => ['fr' => 'Mer', 'ar' => 'الأربعاء'],
                                                        'Thursday' => ['fr' => 'Jeu', 'ar' => 'الخميس'],
                                                        'Friday' => ['fr' => 'Ven', 'ar' => 'الجمعة'],
                                                        'Saturday' => ['fr' => 'Sam', 'ar' => 'السبت'],
                                                        'Sunday' => ['fr' => 'Dim', 'ar' => 'الأحد']
                                                    ];
                                                @endphp
                                                @foreach($days as $value => $labels)
                                                    <div class="form-check-modern">
                                                        <input class="form-check-input-modern d-none" type="checkbox" name="days[]" value="{{ $value }}" id="day_{{ $value }}">
                                                        <label class="btn btn-outline-primary btn-sm rounded-pill px-3 d-flex flex-column align-items-center" for="day_{{ $value }}" style="min-width: 60px;">
                                                            <span style="font-size: 0.8rem;">{{ $labels['fr'] }}</span>
                                                            <span dir="rtl" style="font-size: 0.7rem;">{{ $labels['ar'] }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Début</span>
                                                <span class="label-ar" dir="rtl">البداية</span>
                                            </label>
                                            <input type="time" name="starts_at" class="form-control-modern" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Fin</span>
                                                <span class="label-ar" dir="rtl">النهاية</span>
                                            </label>
                                            <input type="time" name="finishes_at" class="form-control-modern" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer-modern bg-light d-flex justify-content-end p-4 mt-auto">
                                <button type="submit" class="btn-success-modern">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    <span class="label-fr">Générer le planning</span>
                                    <span class="label-ar" dir="rtl">إنشاء الجدول</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.form-check-input-modern:checked + label {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}
</style>
@endsection
