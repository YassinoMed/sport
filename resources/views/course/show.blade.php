@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <!-- En-tête -->
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
                                <a href="{{ route('course.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Cours</span>
                                    <span class="label-ar" dir="rtl">الدورات</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Détails</span>
                                <span class="label-ar" dir="rtl">التفاصيل</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Détails du cours</span>
                        <span class="label-ar" dir="rtl">تفاصيل الدورة</span>
                    </h1>
                </div>
                <div class="header-actions">
                    <a href="{{ route('course.edit', $course->id) }}" class="btn-primary-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar" dir="rtl">تعديل</span>
                    </a>
                    <a href="{{ route('course.list') }}" class="btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar" dir="rtl">عودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Alertes -->
            @if ($errors->any())
                <div class="alert-modern alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="alert-modern alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('status'))
                <div class="alert-modern alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="alert-modern alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="row g-4">
                <!-- Carte Informations du cours -->
                <div class="col-lg-4">
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                            </div>
                            <h2 class="course-name">{{ $course->name }}</h2>
                            @if(optional($course->gym)->name)
                                <span class="gym-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                                    {{ $course->gym->name }}
                                </span>
                            @endif
                        </div>

                        <div class="course-details">
                            <!-- Coachs -->
                            <div class="detail-section">
                                <div class="detail-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <span class="label-fr">Coachs</span>
                                    <span class="label-ar" dir="rtl">المدربون</span>
                                </div>
                                <div class="detail-content">
                                    @if(($course->coaches ?? collect())->isNotEmpty())
                                        <div class="coaches-list">
                                            @foreach($course->coaches as $coach)
                                                <span class="coach-tag">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                    {{ $coach->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="no-data">
                                            <span class="label-fr">Aucun coach assigné</span>
                                            <span class="label-ar" dir="rtl">لا يوجد مدربون</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="detail-section">
                                <div class="detail-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    <span class="label-fr">Durée</span>
                                    <span class="label-ar" dir="rtl">المدة</span>
                                </div>
                                <div class="date-range">
                                    <div class="date-item">
                                        <span class="date-label">
                                            <span class="label-fr">Début</span>
                                            <span class="label-ar" dir="rtl">البدء</span>
                                        </span>
                                        <span class="date-value">{{ optional($course->start_date)->format('d M Y') ?? '—' }}</span>
                                    </div>
                                    <div class="date-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </div>
                                    <div class="date-item">
                                        <span class="date-label">
                                            <span class="label-fr">Fin</span>
                                            <span class="label-ar" dir="rtl">الانتهاء</span>
                                        </span>
                                        <span class="date-value">{{ optional($course->end_date)->format('d M Y') ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Capacité -->
                            <div class="detail-section">
                                <div class="detail-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <span class="label-fr">Capacité</span>
                                    <span class="label-ar" dir="rtl">السعة</span>
                                </div>
                                <div class="detail-content">
                                    @if($course->capacity)
                                        <div class="capacity-info">
                                            <span class="capacity-value">{{ $course->participations_count ?? 0 }} / {{ $course->capacity }}</span>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar {{ ($course->participations_count ?? 0) >= $course->capacity ? 'bg-danger' : 'bg-primary' }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, (($course->participations_count ?? 0) / $course->capacity) * 100) }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted mt-1 d-block">
                                                {{ $course->capacity - ($course->participations_count ?? 0) }} 
                                                <span class="label-fr">places restantes</span>
                                                <span class="label-ar" dir="rtl">أماكن متبقية</span>
                                            </small>

                                            @if(($course->participations_count ?? 0) >= $course->capacity)
                                                <div class="mt-3">
                                                    @if($isParticipating)
                                                        <span class="badge badge-success p-2 w-100">
                                                            <span class="label-fr">Vous êtes déjà inscrit</span>
                                                            <span class="label-ar" dir="rtl">أنت مسجل بالفعل</span>
                                                        </span>
                                                    @elseif($userWaitlist)
                                                        <div class="alert alert-info p-2 mb-0" style="font-size: 0.875rem;">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            <span class="label-fr">En liste d'attente (Position: {{ $userWaitlist->priority }})</span>
                                                            <span class="label-ar" dir="rtl">في قائمة الانتظار (المركز: {{ $userWaitlist->priority }})</span>
                                                        </div>
                                                    @else
                                                        <form action="{{ route('participation.waitlist') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                                            <button type="submit" class="btn btn-warning w-100 font-weight-bold">
                                                                <i class="fas fa-list-ol mr-1"></i>
                                                                <span class="label-fr">Rejoindre la liste d'attente</span>
                                                                <span class="label-ar" dir="rtl">الانضمام إلى قائمة الانتظار</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="capacity-text">
                                            <span class="label-fr">Illimitée</span>
                                            <span class="label-ar" dir="rtl">غير محدودة</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            @if($course->description)
                                <div class="detail-section">
                                    <div class="detail-header">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" x2="7" y1="10" y2="10"/><line x1="21" x2="3" y1="6" y2="6"/><line x1="21" x2="3" y1="14" y2="14"/><line x1="17" x2="7" y1="18" y2="18"/></svg>
                                        <span class="label-fr">Description</span>
                                        <span class="label-ar" dir="rtl">الوصف</span>
                                    </div>
                                    <p class="description-text">{{ $course->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cartes Statistiques -->
                    <div class="stats-grid mt-4">
                        @php($pCounts = $participationStatusCounts ?? [])
                        <div class="stat-card stat-primary">
                            <div class="stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $course->participations_count ?? 0 }}</span>
                                <span class="stat-label">Participants</span>
                            </div>
                        </div>
                        <div class="stat-card stat-success">
                            <div class="stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $pCounts['attended'] ?? 0 }}</span>
                                <span class="stat-label">Présents</span>
                            </div>
                        </div>
                        <div class="stat-card stat-warning">
                            <div class="stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $pCounts['absent'] ?? 0 }}</span>
                                <span class="stat-label">Absents</span>
                            </div>
                        </div>
                        <div class="stat-card stat-info">
                            <div class="stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $pCounts['registered'] ?? 0 }}</span>
                                <span class="stat-label">Inscrits</span>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé des présences -->
                    @php($aCounts = $attendanceStatusCounts ?? [])
                    <div class="attendance-summary mt-4">
                        <div class="summary-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>Résumé des présences</span>
                        </div>
                        <div class="summary-content">
                            <div class="summary-item">
                                <span class="summary-label">Dernière séance</span>
                                <span class="summary-value">{{ $lastAttendanceDate ? \Illuminate\Support\Carbon::parse($lastAttendanceDate)->format('d M Y') : '—' }}</span>
                            </div>
                            <div class="summary-stats">
                                <div class="mini-stat present">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    {{ $aCounts['present'] ?? 0 }}
                                </div>
                                <div class="mini-stat absent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                    {{ $aCounts['absent'] ?? 0 }}
                                </div>
                                <div class="mini-stat excused">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                    {{ $aCounts['excused'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modern-card mt-4">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Présences</h3>
                                <span class="header-subtitle">Séances enregistrées</span>
                            </div>
                            <div class="ms-auto d-flex gap-2">
                                <a href="{{ route('course-attendance.create', $course->id) }}" class="btn-ghost">Marquer</a>
                                <a href="{{ route('course-attendance.statistics', $course->id) }}" class="btn-ghost">Stats</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-center">P</th>
                                        <th class="text-center">A</th>
                                        <th class="text-center">E</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($recentAttendanceSessions ?? collect()) as $s)
                                        @php($sessionDate = (string) ($s->attendance_date ?? ''))
                                        <tr>
                                            <td>{{ $sessionDate ? \Illuminate\Support\Carbon::parse($sessionDate)->format('d/m/Y') : '—' }}</td>
                                            <td class="text-center">{{ (int) ($s->present ?? 0) }}</td>
                                            <td class="text-center">{{ (int) ($s->absent ?? 0) }}</td>
                                            <td class="text-center">{{ (int) ($s->excused ?? 0) }}</td>
                                            <td class="text-end">
                                                @if($sessionDate)
                                                    <a class="btn-ghost" href="{{ route('course-attendance.show', [$course->id, $sessionDate]) }}">Voir</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Aucune séance enregistrée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @php($lastList = $lastSessionAttendances ?? collect())
                        @if(($lastAttendanceDate ?? null) && $lastList->isNotEmpty())
                            <div class="p-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Liste ({{ \Illuminate\Support\Carbon::parse($lastAttendanceDate)->format('d/m/Y') }})</strong>
                                    <a class="btn-ghost" href="{{ route('course-attendance.show', [$course->id, $lastAttendanceDate]) }}">Ouvrir</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Participant</th>
                                                <th class="text-center">Statut</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lastList as $attendance)
                                                @php($u = optional(optional($attendance->participation)->user))
                                                <tr>
                                                    <td>{{ $u?->name ?? '—' }}</td>
                                                    <td class="text-center">
                                                        @php($st = (string) ($attendance->status ?? ''))
                                                        @if($st === 'present')
                                                            <span class="badge bg-success">Présent</span>
                                                        @elseif($st === 'absent')
                                                            <span class="badge bg-danger">Absent</span>
                                                        @elseif($st === 'excused')
                                                            <span class="badge bg-warning text-dark">Excusé</span>
                                                        @else
                                                            <span class="badge bg-secondary">—</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $attendance->notes ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Colonne droite - Plannings -->
                <div class="col-lg-8">
                    <!-- Planning hebdomadaire -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Planning hebdomadaire</h3>
                                <span class="header-subtitle">Créneaux de la semaine</span>
                            </div>
                        </div>
                        <div class="schedule-grid">
                            @foreach(($weekDays ?? []) as $day)
                                @php($daySchedules = ($schedulesByDay ?? collect())->get($day, collect()))
                                <div class="schedule-day {{ $daySchedules->isEmpty() ? 'empty' : '' }}">
                                    <div class="day-header">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <span class="day-name">{{ $day }}</span>
                                    </div>
                                    <div class="day-slots">
                                        @if($daySchedules->isEmpty())
                                            <div class="no-slot">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
                                                Pas de séance
                                            </div>
                                        @else
                                            @foreach($daySchedules as $sch)
                                                <div class="slot-card">
                                                    <div class="slot-time">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                        {{ substr((string)$sch->start_time, 0, 5) }} - {{ substr((string)$sch->end_time, 0, 5) }}
                                                    </div>
                                                    @if($sch->group)
                                                        <div class="slot-group">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                            {{ $sch->group->name }}
                                                        </div>
                                                    @endif
                                                    @if($sch->location)
                                                        <div class="slot-location">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                            {{ $sch->location }}
                                                        </div>
                                                    @endif
                                                    @if($sch->notes)
                                                        <div class="slot-notes">{{ $sch->notes }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Planning mensuel -->
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Planning mensuel</h3>
                                <span class="header-subtitle">Séances prévues par mois</span>
                            </div>
                        </div>
                        <div class="monthly-planning">
                            @forelse(($plannedSessionsByMonth ?? []) as $month => $sessions)
                                <div class="month-accordion">
                                    <button class="month-header" onclick="toggleMonth(this)">
                                        <div class="month-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            <span class="month-name">{{ $month }}</span>
                                        </div>
                                        <div class="month-meta">
                                            <span class="session-count">{{ count($sessions) }} séances</span>
                                            <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </button>
                                    <div class="month-content">
                                        <div class="sessions-list">
                                            @foreach($sessions as $session)
                                                <div class="session-row">
                                                    <div class="session-date">
                                                        <span class="date-day">{{ \Illuminate\Support\Carbon::parse($session['date'])->format('d') }}</span>
                                                        <span class="date-month">{{ \Illuminate\Support\Carbon::parse($session['date'])->format('M') }}</span>
                                                        <span class="date-weekday">{{ $session['day'] }}</span>
                                                    </div>
                                                    <div class="session-details">
                                                        <div class="session-time">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                            {{ $session['start_time'] }} - {{ $session['end_time'] }}
                                                        </div>
                                                        <div class="session-group">{{ $session['group'] }}</div>
                                                        @if($session['location'])
                                                            <div class="session-location">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                                {{ $session['location'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-planning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><line x1="10" x2="14" y1="14" y2="18"/><line x1="14" x2="10" y1="14" y2="18"/></svg>
                                    <h4>Aucun planning mensuel</h4>
                                    <p>Il n'y a pas encore de planning mensuel disponible pour ce cours.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function toggleMonth(button) {
    const accordion = button.parentElement;
    accordion.classList.toggle('open');
}
</script>

@push('styles')
<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #a5b4fc;
    --primary-bg: #eef2ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --success-dark: #059669;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --info: #06b6d4;
    --info-light: #cffafe;
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
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

.page-title { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.header-actions { display: flex; gap: 0.75rem; }
.breadcrumb { padding: 0; margin: 0; background: transparent; font-size: 0.875rem; }
.breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--gray-400); }
.breadcrumb-link { color: var(--gray-500); text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: var(--transition); }
.breadcrumb-link:hover { color: var(--primary); }

.btn-primary-modern { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; font-size: 0.875rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4); }
.btn-primary-modern:hover { transform: translateY(-1px); box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.5); color: white; }
.btn-ghost { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: transparent; color: var(--gray-600); font-weight: 500; font-size: 0.875rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }

.alert-modern { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: var(--radius-md); font-weight: 500; }
.alert-success { background: var(--success-light); color: var(--success-dark); }
.alert-error { background: var(--danger-light); color: #991b1b; }

.course-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.course-header { background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%); padding: 2rem; text-align: center; }
.course-icon { width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; margin: 0 auto 1rem; }
.course-name { color: white; font-size: 1.375rem; font-weight: 700; margin: 0 0 0.75rem; }
.gym-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; }
.course-details { padding: 1.5rem; }
.detail-section { padding: 1rem 0; border-bottom: 1px solid var(--gray-100); }
.detail-section:last-child { border-bottom: none; padding-bottom: 0; }
.detail-section:first-child { padding-top: 0; }
.detail-header { display: flex; align-items: center; gap: 0.5rem; color: var(--gray-500); font-size: 0.8125rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; margin-bottom: 0.75rem; }
.detail-content { color: var(--gray-900); }
.coaches-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.coach-tag { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: var(--info-light); color: #0891b2; font-size: 0.8125rem; font-weight: 600; border-radius: 9999px; }
.no-data { color: var(--gray-400); font-size: 0.875rem; }
.date-range { display: flex; align-items: center; gap: 1rem; }
.date-item { display: flex; flex-direction: column; gap: 0.125rem; }
.date-label { font-size: 0.6875rem; color: var(--gray-500); text-transform: uppercase; font-weight: 600; }
.date-value { font-weight: 600; color: var(--gray-900); }
.date-arrow { color: var(--gray-300); }
.description-text { color: var(--gray-600); font-size: 0.9375rem; line-height: 1.6; margin: 0; }

.stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
.stat-card { background: white; border-radius: var(--radius-md); padding: 1rem; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 0.75rem; transition: var(--transition); }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.stat-icon { width: 40px; height: 40px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; }
.stat-primary .stat-icon { background: var(--primary-bg); color: var(--primary); }
.stat-success .stat-icon { background: var(--success-light); color: var(--success); }
.stat-warning .stat-icon { background: var(--warning-light); color: var(--warning); }
.stat-info .stat-icon { background: var(--info-light); color: var(--info); }
.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.25rem; font-weight: 700; color: var(--gray-900); }
.stat-label { font-size: 0.75rem; color: var(--gray-500); }

.attendance-summary { background: white; border-radius: var(--radius-md); padding: 1.25rem; box-shadow: var(--shadow-md); }
.summary-header { display: flex; align-items: center; gap: 0.5rem; color: var(--gray-700); font-weight: 600; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--gray-100); }
.summary-content { display: flex; flex-direction: column; gap: 0.75rem; }
.summary-item { display: flex; justify-content: space-between; align-items: center; }
.summary-label { font-size: 0.8125rem; color: var(--gray-500); }
.summary-value { font-weight: 600; color: var(--gray-900); }
.summary-stats { display: flex; gap: 0.5rem; }
.mini-stat { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
.mini-stat.present { background: var(--success-light); color: var(--success-dark); }
.mini-stat.absent { background: var(--danger-light); color: var(--danger); }
.mini-stat.excused { background: var(--warning-light); color: #92400e; }

.modern-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.card-header-modern { display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-100); }
.header-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.header-icon-primary { background: var(--primary-bg); color: var(--primary); }
.header-icon-success { background: var(--success-light); color: var(--success); }
.header-title { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.header-subtitle { font-size: 0.8125rem; color: var(--gray-500); }

.schedule-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1px; background: var(--gray-200); }
.schedule-day { background: white; padding: 1rem; }
.schedule-day.empty { background: var(--gray-50); }
.day-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-bg); }
.day-header svg { color: var(--primary); }
.day-name { font-weight: 600; color: var(--gray-900); font-size: 0.875rem; }
.day-slots { display: flex; flex-direction: column; gap: 0.5rem; }
.no-slot { display: flex; align-items: center; gap: 0.375rem; color: var(--gray-400); font-size: 0.8125rem; }
.slot-card { background: var(--gray-50); border-radius: var(--radius-sm); padding: 0.625rem; border-left: 3px solid var(--primary); }
.slot-time { display: flex; align-items: center; gap: 0.375rem; font-weight: 600; color: var(--gray-900); font-size: 0.8125rem; margin-bottom: 0.375rem; }
.slot-time svg { color: var(--primary); }
.slot-group { display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: var(--gray-600); margin-bottom: 0.25rem; }
.slot-location { display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: var(--gray-500); }
.slot-notes { font-size: 0.6875rem; color: var(--gray-400); font-style: italic; margin-top: 0.25rem; }

.monthly-planning { padding: 1rem; }
.month-accordion { background: var(--gray-50); border-radius: var(--radius-md); margin-bottom: 0.75rem; overflow: hidden; transition: var(--transition); }
.month-accordion:last-child { margin-bottom: 0; }
.month-accordion.open { box-shadow: var(--shadow-md); }
.month-header { width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; background: transparent; border: none; cursor: pointer; transition: var(--transition); }
.month-header:hover { background: var(--gray-100); }
.month-info { display: flex; align-items: center; gap: 0.75rem; }
.month-info svg { color: var(--primary); }
.month-name { font-weight: 600; color: var(--gray-900); text-transform: capitalize; }
.month-meta { display: flex; align-items: center; gap: 0.75rem; }
.session-count { font-size: 0.8125rem; font-weight: 600; color: var(--primary); background: var(--primary-bg); padding: 0.25rem 0.75rem; border-radius: 9999px; }
.chevron { color: var(--gray-400); transition: transform 0.3s ease; }
.month-accordion.open .chevron { transform: rotate(180deg); }
.month-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
.month-accordion.open .month-content { max-height: 1000px; }
.sessions-list { padding: 0 1.25rem 1.25rem; }
.session-row { display: flex; gap: 1rem; padding: 0.875rem; background: white; border-radius: var(--radius-sm); margin-bottom: 0.5rem; box-shadow: var(--shadow-sm); }
.session-row:last-child { margin-bottom: 0; }
.session-date { display: flex; flex-direction: column; align-items: center; min-width: 50px; padding: 0.5rem; background: var(--primary-bg); border-radius: var(--radius-sm); }
.date-day { font-size: 1.25rem; font-weight: 700; color: var(--primary); line-height: 1; }
.date-month { font-size: 0.6875rem; font-weight: 600; color: var(--primary); text-transform: uppercase; }
.date-weekday { font-size: 0.6875rem; color: var(--gray-500); margin-top: 0.25rem; }
.session-details { display: flex; flex-direction: column; gap: 0.25rem; justify-content: center; }
.session-time { display: flex; align-items: center; gap: 0.375rem; font-weight: 600; color: var(--gray-900); font-size: 0.875rem; }
.session-time svg { color: var(--primary); }
.session-group { font-size: 0.8125rem; color: var(--gray-600); }
.session-location { display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: var(--gray-500); }
.empty-planning { text-align: center; padding: 3rem 2rem; }
.empty-planning svg { color: var(--gray-300); margin-bottom: 1rem; }
.empty-planning h4 { font-size: 1rem; font-weight: 600; color: var(--gray-700); margin: 0 0 0.5rem; }
.empty-planning p { color: var(--gray-500); font-size: 0.875rem; margin: 0; }

@media (max-width: 992px) { .page-title { font-size: 1.375rem; } .header-actions { flex-wrap: wrap; } .stats-grid { grid-template-columns: 1fr; } .schedule-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) { .date-range { flex-direction: column; align-items: flex-start; gap: 0.5rem; } .date-arrow { transform: rotate(90deg); } .session-row { flex-direction: column; gap: 0.75rem; } .session-date { flex-direction: row; gap: 0.5rem; width: fit-content; } }
</style>
@endpush
@endsection
