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
                                <a href="{{ route('course-attendance.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Présence</span>
                                    <span class="label-ar">الحضور</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">{{ $course->name }}</span>
                                <span class="label-ar">{{ $course->name }}</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-user-check"></i>
                        <span class="label-fr">Marquer la présence</span>
                        <span class="label-ar">تسجيل الحضور</span>
                    </h1>
                    <p class="page-subtitle">
                        <span class="label-fr">Session de cours:</span>
                        <span class="label-ar">حصة الدورة:</span>
                        {{ $course->name }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('course-attendance.index') }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar">عودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="modern-card mb-4">
                    <div class="card-header-modern card-header-primary">
                        <div class="header-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="header-title">
                                <span class="label-fr">Feuille de présence - {{ $course->name }}</span>
                                <span class="label-ar">ورقة الحضور - {{ $course->name }}</span>
                            </h3>
                            <p class="header-subtitle mb-0">
                                <span class="label-fr">Marquer les présences des athlètes inscrits</span>
                                <span class="label-ar">تسجيل حضور الرياضيين المسجلين</span>
                            </p>
                        </div>
                    </div>

                    <div class="card-body-modern">
                        @if ($errors->any())
                            <div class="alert-modern alert-error mb-4">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <ul class="mb-0 list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('course-attendance.store', $course->id) }}" method="POST" id="attendanceForm">
                            @csrf

                            <!-- Informations du cours et Date -->
                            <div class="row mb-4 align-items-end">
                                <div class="col-md-8">
                                    <div class="stat-card stat-card-primary border-0 shadow-none bg-light">
                                        <div class="stat-card-body p-3">
                                            <div class="stat-icon bg-white" style="width: 50px; height: 50px;"><i class="fas fa-graduation-cap text-primary"></i></div>
                                            <div class="stat-info">
                                                <h3 class="stat-value text-primary mb-0" style="font-size: 1.25rem;">{{ $course->name }}</h3>
                                                <p class="stat-label mb-0">
                                                    @if(($course->coaches ?? collect())->isNotEmpty())
                                                        <span class="small text-muted"><i class="fas fa-user-tie mr-1"></i> Coachs: {{ $course->coaches->pluck('name')->filter()->join(', ') }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern mb-0">
                                        <label class="form-label-modern">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span class="label-fr">Date de présence</span>
                                            <span class="label-ar">تاريخ الحضور</span>
                                            *
                                        </label>
                                        <input type="date" 
                                               class="form-control-modern @error('attendance_date') is-invalid @enderror" 
                                               id="attendance_date" 
                                               name="attendance_date" 
                                               value="{{ old('attendance_date', date('Y-m-d')) }}" 
                                               required readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Planning -->
                            <div class="modern-card mb-4 overflow-hidden border bg-light shadow-none">
                                <div class="card-header-modern bg-transparent border-bottom py-2">
                                    <h5 class="header-title mb-0" style="font-size: 0.95rem;">
                                        <i class="fas fa-clock mr-2 text-primary"></i>
                                        <span class="label-fr">Planning du cours</span>
                                        <span class="label-ar">جدول الدورة</span>
                                    </h5>
                                </div>
                                <div class="card-body-modern p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 border-0">
                                                        <span class="label-fr">Jour</span>
                                                        <span class="label-ar">اليوم</span>
                                                    </th>
                                                    <th class="border-0">
                                                        <span class="label-fr">Créneaux</span>
                                                        <span class="label-ar">الفترات</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(($weekDays ?? []) as $day)
                                                    @php
                                                        $daySchedules = ($schedulesByDay ?? collect())->get($day, collect());
                                                    @endphp
                                                    <tr>
                                                        <td class="font-weight-bold px-4 border-0">{{ $day }}</td>
                                                        <td class="border-0">
                                                            @if($daySchedules->isEmpty())
                                                                <span class="text-muted small">-</span>
                                                            @else
                                                                @foreach($daySchedules as $sch)
                                                                    <div class="small mb-1">
                                                                        <span class="badge badge-primary-soft">{{ substr((string)$sch->start_time, 0, 5) }} - {{ substr((string)$sch->end_time, 0, 5) }}</span>
                                                                        @if($sch->group)
                                                                            <span class="text-muted ml-1">({{ $sch->group->name }})</span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @if($participations->isEmpty())
                                <div class="modern-card p-5 text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-4x text-muted mb-4"></i>
                                        <h4 class="font-weight-bold">
                                            <span class="label-fr">Aucun participant inscrit</span>
                                            <span class="label-ar">لا يوجد مشاركين مسجلين</span>
                                        </h4>
                                        <p class="text-muted mb-4">
                                            <span class="label-fr">Il n'y a aucun participant inscrit à ce cours pour le moment.</span>
                                            <span class="label-ar">لا يوجد أي مشارك مسجل في هذه الدورة حالياً.</span>
                                        </p>
                                        <a href="{{ route('participation.create') }}" class="btn-primary-modern">
                                            <i class="fas fa-plus"></i>
                                            <span class="label-fr">Ajouter des participants</span>
                                            <span class="label-ar">إضافة مشاركين</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- Actions rapides et Filtres -->
                                <div class="modern-card bg-light border-0 mb-4 shadow-none">
                                    <div class="card-body-modern p-3">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7 col-lg-12 mb-3 mb-xl-0">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <div class="btn-group mr-2">
                                                        <button type="button" class="btn btn-sm btn-success-modern" onclick="markAll('present')">
                                                            <i class="fas fa-check"></i>
                                                            <span class="label-fr">Tous présents</span>
                                                            <span class="label-ar">الكل حاضر</span>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger-modern" onclick="markAll('absent')">
                                                            <i class="fas fa-times"></i>
                                                            <span class="label-fr">Tous absents</span>
                                                            <span class="label-ar">الكل غائب</span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group mr-2">
                                                        <button type="button" class="btn btn-sm btn-warning-modern" onclick="markRemainingAbsent()">
                                                            <i class="fas fa-user-slash"></i>
                                                            <span class="label-fr">Clôturer</span>
                                                            <span class="label-ar">إغلاق</span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group mr-2">
                                                        <button type="button" class="btn btn-sm btn-ghost" id="toggleUnmarkedOnly">
                                                            <i class="fas fa-filter"></i>
                                                            <span class="label-fr">Reste</span>
                                                            <span class="label-ar">الباقي</span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group mr-2">
                                                        <button type="button" class="btn btn-sm btn-ghost" onclick="autoFromLastStatus()">
                                                            <i class="fas fa-history"></i>
                                                            <span class="label-fr">Précédent</span>
                                                            <span class="label-ar">السابق</span>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary-modern" data-toggle="modal" data-target="#qrScannerModal">
                                                            <i class="fas fa-qrcode"></i>
                                                            <span class="label-fr">Scanner</span>
                                                            <span class="label-ar">مسح</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-lg-12">
                                                <div class="row no-gutters">
                                                    <div class="col-4 pr-1">
                                                        <select id="filterType" class="form-select-modern py-1" style="height: 38px;">
                                                            <option value="none">📋 Tous / الكل</option>
                                                            <option value="category">🏷️ Catégorie / فئة</option>
                                                            <option value="group">👥 Groupe / مجموعة</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4 pr-1" id="filterValueContainer" style="display:none;">
                                                        <select id="categoryFilter" class="form-select-modern py-1 filter-value" style="display:none; height: 38px;">
                                                            <option value="">Toutes / الكل</option>
                                                            @php
                                                                $categories = $participations->pluck('user.category')->filter()->unique('id')->sortBy('name');
                                                            @endphp
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select id="groupFilter" class="form-select-modern py-1 filter-value" style="display:none; height: 38px;">
                                                            <option value="">Tous / الكل</option>
                                                            @php
                                                                $groups = $participations->pluck('user.groups')->flatten()->unique('id')->sortBy('name');
                                                            @endphp
                                                            @foreach($groups as $group)
                                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" id="participantSearch" class="form-control-modern py-1" placeholder="🔍 Chercher... / بحث..." style="height: 38px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Liste des participants -->
                                <div class="modern-card overflow-hidden">
                                    <div class="table-responsive">
                                        <table class="modern-table" id="attendanceTable">
                                            <thead>
                                                <tr>
                                                    <th class="px-4">
                                                        <span class="label-fr">Athlète</span>
                                                        <span class="label-ar">الرياضي</span>
                                                    </th>
                                                    <th class="text-center d-none d-md-table-cell">
                                                        <span class="label-fr">Assiduité</span>
                                                        <span class="label-ar">المواظبة</span>
                                                    </th>
                                                    <th class="text-center">
                                                        <span class="label-fr">Statut</span>
                                                        <span class="label-ar">الحالة</span>
                                                    </th>
                                                    <th class="px-4 d-none d-md-table-cell">
                                                        <span class="label-fr">Notes</span>
                                                        <span class="label-ar">ملاحظات</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($participations as $index => $participation)
                                                <tr data-user-id="{{ optional($participation->user)->id }}" 
                                                    data-paid="{{ (optional(optional(optional($participation->user)->payments)->first())->status === 'paid') ? '1' : '0' }}" 
                                                    data-last-status="{{ optional($participation->courseAttendances->first())->status ?? '' }}" 
                                                    data-name="{{ optional($participation->user)->name ?? '—' }}" 
                                                    data-email="{{ optional($participation->user)->email ?? '—' }}" 
                                                    data-category="{{ optional($participation->user)->category_id ?? '' }}" 
                                                    data-groups="{{ optional($participation->user)->groups ? $participation->user->groups->pluck('id')->implode(',') : '' }}">
                                                    <td class="px-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm avatar-placeholder mr-3">
                                                                {{ strtoupper(substr(optional($participation->user)->name ?? 'U', 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-bold text-dark d-flex align-items-center gap-2">
                                                                    {{ optional($participation->user)->name ?? '—' }}
                                                                    @if(isset($participation->user->payments) && $participation->user->payments->isEmpty())
                                                                        <i class="fas fa-circle text-danger blink-payment" title="Paiement manquant" style="font-size: 0.7rem;"></i>
                                                                    @elseif(isset($participation->user->payments))
                                                                        <i class="fas fa-circle text-success" title="Paiement à jour" style="font-size: 0.7rem;"></i>
                                                                    @endif
                                                                </div>
                                                                @if(isset($participation->user->payments) && $participation->user->payments->isEmpty())
                                                                    <span class="badge badge-danger-soft py-0" style="font-size: 0.6rem;">
                                                                        <span class="label-fr">Non payé</span>
                                                                        <span class="label-ar">غير مدفوع</span>
                                                                    </span>
                                                                @endif
                                                                @if(optional($participation->user)->category)
                                                                    <div class="text-muted small d-none d-lg-block">{{ $participation->user->category->name }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="attendances[{{ $index }}][participation_id]" value="{{ $participation->id }}">
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <div class="streak-container" title="Historique des 5 dernières sessions">
                                                            @foreach($participation->courseAttendances as $att)
                                                                <div class="streak-dot {{ $att->status }}" data-toggle="tooltip" title="{{ \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') }}: {{ __($att->status) }}"></div>
                                                            @endforeach
                                                            @for($i = count($participation->courseAttendances); $i < 5; $i++)
                                                                <div class="streak-dot" title="N/A"></div>
                                                            @endfor
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            <div class="d-flex attendance-buttons-mobile">
                                                                <input type="radio" class="btn-check" name="attendances[{{ $index }}][status]" id="status_{{ $index }}_present" value="present" autocomplete="off" required>
                                                                <label class="btn btn-outline-success btn-sm thumb-btn" for="status_{{ $index }}_present">P</label>

                                                                <input type="radio" class="btn-check" name="attendances[{{ $index }}][status]" id="status_{{ $index }}_absent" value="absent" autocomplete="off">
                                                                <label class="btn btn-outline-danger btn-sm thumb-btn" for="status_{{ $index }}_absent">A</label>

                                                                <input type="radio" class="btn-check" name="attendances[{{ $index }}][status]" id="status_{{ $index }}_excused" value="excused" autocomplete="off">
                                                                <label class="btn btn-outline-warning btn-sm thumb-btn" for="status_{{ $index }}_excused">E</label>
                                                            </div>
                                                            <select class="form-select-modern d-none d-md-block status-select-desktop" name="attendances[{{ $index }}][status_sync]" style="height: 32px; font-size: 0.75rem;">
                                                                <option value="" disabled selected>
                                                                    <span class="label-fr">Statut...</span>
                                                                    <span class="label-ar">الحالة...</span>
                                                                </option>
                                                                <option value="present">
                                                                    ✅ <span class="label-fr">Présent</span><span class="label-ar">حاضر</span>
                                                                </option>
                                                                <option value="absent">
                                                                    ❌ <span class="label-fr">Absent</span><span class="label-ar">غائب</span>
                                                                </option>
                                                                <option value="excused">
                                                                    ⚠️ <span class="label-fr">Excusé</span><span class="label-ar">معذور</span>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <input type="text" class="form-control-modern" 
                                                               name="attendances[{{ $index }}][notes]" 
                                                               placeholder="Note... / ملاحظة..."
                                                               style="height: 38px; font-size: 0.875rem;"
                                                               value="{{ old('attendances.'.$index.'.notes') }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Résumé -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="stats-grid">
                                            <div class="stat-card stat-success">
                                                <div class="stat-icon">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-value" id="present-count">0</span>
                                                    <span class="stat-label">
                                                        <span class="label-fr">Présents</span>
                                                        <span class="label-ar">حاضرون</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="stat-card stat-danger">
                                                <div class="stat-icon">
                                                    <i class="fas fa-times-circle"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-value" id="absent-count">0</span>
                                                    <span class="stat-label">
                                                        <span class="label-fr">Absents</span>
                                                        <span class="label-ar">غائبون</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="stat-card stat-warning">
                                                <div class="stat-icon">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-value" id="excused-count">0</span>
                                                    <span class="stat-label">
                                                        <span class="label-fr">Excusés</span>
                                                        <span class="label-ar">معذورون</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="stat-card stat-primary">
                                                <div class="stat-icon">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-value">{{ $participations->count() }}</span>
                                                    <span class="stat-label">
                                                        <span class="label-fr">Total Athlètes</span>
                                                        <span class="label-ar">إجمالي اللاعبين</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('course-attendance.index') }}" class="btn-ghost">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                <span class="label-fr">Retour</span>
                                                <span class="label-ar">عودة</span>
                                            </a>
                                            <button type="submit" class="btn-success-modern px-5 py-3">
                                                <i class="fas fa-save mr-2"></i>
                                                <span class="label-fr">Enregistrer les présences</span>
                                                <span class="label-ar">حفظ الحضور</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Scanner QR -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    <i class="fas fa-qrcode mr-2"></i>
                    <span class="label-fr">Scanner de Présence</span>
                    <span class="label-ar" dir="rtl">ماسح الحضور</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="qr-reader" style="width: 100%; min-height: 400px; background: #000;"></div>
                <div id="qr-reader-results" class="p-3 text-center" style="display: none;">
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="label-fr">Utilisateur identifié : </span>
                        <span class="label-ar" dir="rtl">تم تحديد المستخدم: </span>
                        <strong id="scanned-user-name"></strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div class="text-muted small">
                    <i class="fas fa-info-circle"></i>
                    <span class="label-fr">Placez le QR Code devant la caméra.</span>
                    <span class="label-ar" dir="rtl">ضع رمز QR أمام الكاميرا.</span>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="label-fr">Fermer</span>
                    <span class="label-ar" dir="rtl">إغلاق</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Spécificités pour la page de présence */
    .stat-value {
        font-size: 1.5rem !important;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .filter-value {
        transition: var(--transition);
    }
    
    /* Animation pour le scan QR */
    @keyframes pulse-success {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    
    .table-success {
        background-color: rgba(16, 185, 129, 0.1) !important;
        animation: pulse-success 2s infinite;
    }

    /* Compteurs de présence */
    #present-count, #absent-count, #excused-count {
        font-weight: 700;
    }

    /* Bandeau flottant mobile */
    .floating-attendance-counter {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 50px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        z-index: 1050;
        display: flex;
        gap: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .floating-stat {
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: bold;
        font-size: 0.9rem;
    }

    /* Boutons Thumb-friendly */
    .attendance-buttons-mobile {
        gap: 5px;
    }

    .thumb-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: bold;
        transition: all 0.2s;
    }

    .btn-check:checked + .thumb-btn.btn-outline-success { background-color: #28a745; color: white; border-color: #28a745; transform: scale(1.1); }
    .btn-check:checked + .thumb-btn.btn-outline-danger { background-color: #dc3545; color: white; border-color: #dc3545; transform: scale(1.1); }
    .btn-check:checked + .thumb-btn.btn-outline-warning { background-color: #ffc107; color: white; border-color: #ffc107; transform: scale(1.1); }

    .blink-payment {
        animation: blinker 1.5s linear infinite;
    }

    @keyframes blinker {
        50% { opacity: 0.3; }
    }

    /* Reste à marquer row style */
    tr.is-marked {
        opacity: 0.7;
    }

    tr.is-hidden {
        display: none !important;
    }

    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        .status-select-desktop {
            display: none !important;
        }
    }
    @media (min-width: 769px) {
        .floating-attendance-counter {
            display: none !important;
        }
    }
</style>
<div class="floating-attendance-counter d-md-none shadow-lg">
    <div class="floating-stat text-success">
        <i class="fas fa-check-circle"></i> <span id="float-present">0</span>
    </div>
    <div class="floating-stat text-danger">
        <i class="fas fa-times-circle"></i> <span id="float-absent">0</span>
    </div>
    <div class="floating-stat text-warning">
        <i class="fas fa-exclamation-circle"></i> <span id="float-excused">0</span>
    </div>
    <div class="floating-stat text-primary border-left pl-2">
        <i class="fas fa-users"></i> <span id="float-total">0</span>
    </div>
</div>
@endpush
@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
$(document).ready(function() {
    let html5QrcodeScanner = null;
    const courseId = "{{ $course->id }}";
    const storageKey = `attendance_draft_${courseId}`;
    let unmarkedOnly = false;

    function updateCounts() {
        let presentCount = $('input[name$="[status]"]:checked[value="present"]').length;
        let absentCount = $('input[name$="[status]"]:checked[value="absent"]').length;
        let excusedCount = $('input[name$="[status]"]:checked[value="excused"]').length;
        let total = $('tr[data-user-id]').length;
        
        $('#present-count, #float-present').text(presentCount);
        $('#absent-count, #float-absent').text(absentCount);
        $('#excused-count, #float-excused').text(excusedCount);
        $('#float-total').text(total);

        // Save to localStorage
        saveDraft();
    }

    function updateRowStyle($input) {
        var status = $input.val();
        var $row = $input.closest('tr');
        $row.removeClass('table-success table-danger table-warning is-marked');
        if (status === 'present') $row.addClass('table-success is-marked');
        else if (status === 'absent') $row.addClass('table-danger is-marked');
        else if (status === 'excused') $row.addClass('table-warning is-marked');
        
        if (unmarkedOnly && status) {
            $row.addClass('is-hidden');
        }
    }

    // Sauvegarde locale
    function saveDraft() {
        const data = {};
        $('input[name$="[status]"]:checked').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        localStorage.setItem(storageKey, JSON.stringify(data));
    }

    function loadDraft() {
        const draft = localStorage.getItem(storageKey);
        if (draft) {
            const data = JSON.parse(draft);
            Object.keys(data).forEach(name => {
                $(`input[name="${name}"][value="${data[name]}"]`).prop('checked', true).trigger('change');
            });
        }
    }

    // Clôturer l'appel (marquer les restants comme absents)
    window.markRemainingAbsent = function() {
        Swal.fire({
            title: 'Clôturer l\'appel ?',
            text: "Tous les athlètes non marqués seront enregistrés comme ABSENTS.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, clôturer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $('tr[data-user-id]').each(function() {
                    const $row = $(this);
                    if (!$row.find('input[name$="[status]"]:checked').length) {
                        $row.find('input[name$="[status]"][value="absent"]').prop('checked', true).trigger('change');
                    }
                });
                Swal.fire('Succès', 'Les restants ont été marqués absents.', 'success');
            }
        });
    }

    // Filtrer les non-marqués
    $('#toggleUnmarkedOnly').on('click', function() {
        unmarkedOnly = !unmarkedOnly;
        $(this).toggleClass('btn-primary btn-light border');
        
        $('tr[data-user-id]').each(function() {
            const $row = $(this);
            const isMarked = $row.find('input[name$="[status]"]:checked').length > 0;
            if (unmarkedOnly && isMarked) {
                $row.addClass('is-hidden');
            } else {
                $row.removeClass('is-hidden');
            }
        });
    });

    // Synchronisation boutons radio et select desktop
    $('input[name$="[status]"]').on('change', function() {
        const $input = $(this);
        const $row = $input.closest('tr');
        const val = $input.val();
        $row.find('.status-select-desktop').val(val);
        updateRowStyle($input);
        updateCounts();
    });

    $('.status-select-desktop').on('change', function() {
        const $select = $(this);
        const $row = $select.closest('tr');
        const val = $select.val();
        $row.find(`input[name$="[status]"][value="${val}"]`).prop('checked', true).trigger('change');
    });

    // Nettoyer le brouillon après soumission réussie
    $('#attendanceForm').on('submit', function() {
        localStorage.removeItem(storageKey);
    });

    // Charger le brouillon au démarrage
    loadDraft();
    updateCounts();

    // ... Reste du code (QR Scanner, etc.)

        if (attendanceDate && participationId && status) {
            $select.addClass('loading-status');
            $.ajax({
                url: "{{ route('course-attendance.ajaxUpdate') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    participation_id: participationId,
                    status: status,
                    attendance_date: attendanceDate
                },
                success: function(response) {
                    $select.removeClass('loading-status').addClass('save-success');
                    setTimeout(() => $select.removeClass('save-success'), 2000);
                },
                error: function() {
                    $select.removeClass('loading-status').addClass('save-error');
                }
            });
        }
    });

    // Fonctions pour les actions rapides
    window.markAll = function(status) {
        $('select[name*="[status]"]').val(status).trigger('change');
    };

    window.autoFromLastStatus = function() {
        $('tbody tr').each(function() {
            var last = $(this).data('last-status');
            if (last) {
                $(this).find('select[name*="[status]"]').val(String(last)).trigger('change');
            }
        });
    };

    function filterParticipants() {
        var searchQuery = $('#participantSearch').val().toLowerCase();
        var filterType = $('#filterType').val();
        
        $('#filterValueContainer').toggle(filterType !== 'none');
        $('#categoryFilter').toggle(filterType === 'category');
        $('#groupFilter').toggle(filterType === 'group');

        var selectedCategory = $('#categoryFilter').val();
        var selectedGroup = $('#groupFilter').val();
        
        $('tbody tr').each(function() {
            var $row = $(this);
            var name = (String($row.data('name')) || '').toLowerCase();
            var email = (String($row.data('email')) || '').toLowerCase();
            var category = String($row.data('category') || '');
            var groups = String($row.data('groups') || '').split(',').map(g => String(g || '').trim()).filter(g => g !== '');
            
            var matchesSearch = !searchQuery || name.indexOf(searchQuery) !== -1 || email.indexOf(searchQuery) !== -1;
            var matchesFilter = true;

            if (filterType === 'category' && selectedCategory) {
                matchesFilter = category === selectedCategory;
            } else if (filterType === 'group' && selectedGroup) {
                matchesFilter = groups.includes(selectedGroup);
            }
            
            if (matchesSearch && matchesFilter) $row.show();
            else $row.hide();
        });
        
        updateCounts();
    }
    
    $('#participantSearch').on('input', filterParticipants);
    $('#filterType').on('change', function() {
        $('#categoryFilter').val('');
        $('#groupFilter').val('');
        filterParticipants();
    });
    $('#categoryFilter, #groupFilter').on('change', filterParticipants);

    $('#bulkStatus').on('change', function(){
        var v = $(this).val();
        if (v) { markAll(v); }
    });
    
    updateCounts();
});
</script>
@endpush
