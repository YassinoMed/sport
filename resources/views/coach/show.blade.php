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
                                    Accueil
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('coach.list') }}" class="breadcrumb-link">Coachs</a>
                            </li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        Profil Coach
                        <span class="coach-id-badge">#{{ $singleCoach->id }}</span>
                    </h1>
                    <p class="page-subtitle">المدرب</p>
                </div>
                <a href="{{ route('coach.list') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Profile Card -->
                <div class="col-lg-4">
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-avatar-wrapper">
                                @if($singleCoach->profile_image)
                                    <img src="{{ asset($singleCoach->profile_image) }}" alt="{{ $singleCoach->name }}" class="profile-avatar">
                                @else
                                    <div class="profile-avatar d-flex align-items-center justify-content-center bg-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                @endif
                                <span class="avatar-status-indicator"></span>
                            </div>
                            <h2 class="profile-name">{{ $singleCoach->name }}</h2>
                            <div class="profile-email">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                {{ $singleCoach->email }}
                            </div>
                        </div>
                        
                        <div class="profile-details">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">Ville / المدينة</span>
                                    <span class="detail-value">{{ $singleCoach->city->name ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">Créé le / تاريخ الإنشاء</span>
                                    <span class="detail-value">{{ $singleCoach->created_at ? $singleCoach->created_at->format('d M Y') : '—' }}</span>
                                </div>
                            </div>

                            @php
                                $lastBelt = $singleCoach->belts->last();
                            @endphp

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-ribbon text-primary" style="font-size: 18px;"></i>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">Ceinture / الحزام</span>
                                    <span class="detail-value">{{ $lastBelt->belt_name ?? '—' }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-graduation-cap text-primary" style="font-size: 18px;"></i>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">Grade / رتبة التدريب</span>
                                    <span class="detail-value">{{ $lastBelt->training_grade ?? '—' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="{{ route('coach.edit', $singleCoach->id) }}" class="action-card action-edit">
                                <div class="action-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </div>
                                <div class="action-text">
                                    <span class="action-label">Modifier</span>
                                    <span class="action-label-ar">تعديل</span>
                                </div>
                            </a>
                            <a href="{{ route('coach.report', $singleCoach->id) }}" class="action-card action-report">
                                <div class="action-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                                </div>
                                <div class="action-text">
                                    <span class="action-label">Rapport</span>
                                    <span class="action-label-ar">تقرير</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="stats-grid mt-4">
                        <div class="mini-stat-card stat-sessions">
                            <div class="mini-stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            </div>
                            <div class="mini-stat-content">
                                <span class="mini-stat-value">{{ count($coachCourses ?? []) }}</span>
                                <span class="mini-stat-label">Cours</span>
                            </div>
                        </div>
                        <div class="mini-stat-card stat-present">
                            <div class="mini-stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div class="mini-stat-content">
                                <span class="mini-stat-value">{{ $singleCoach->total_presences ?? 0 }}</span>
                                <span class="mini-stat-label">Présences</span>
                            </div>
                        </div>
                        <div class="mini-stat-card stat-absent mt-3">
                            <div class="mini-stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                            </div>
                            <div class="mini-stat-content">
                                <span class="mini-stat-value text-danger">{{ $singleCoach->total_absences ?? 0 }}</span>
                                <span class="mini-stat-label">Absences</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-8">
                    <!-- Courses List -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Cours</h3>
                                <span class="header-subtitle">الدورات</span>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Nom / الاسم</th>
                                        <th>Salle / القاعة</th>
                                        <th>Début / البداية</th>
                                        <th>Fin / النهاية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($coachCourses ?? []) as $c)
                                        <tr>
                                            <td>
                                                <span class="session-name">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                    {{ $c->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="day-badge">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                    {{ optional($c->gym)->name ?: '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="time-badge time-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                    {{ $c->start_date ? $c->start_date->format('Y-m-d') : '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="time-badge time-end">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                    {{ $c->end_date ? $c->end_date->format('Y-m-d') : '—' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="empty-cell">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                Aucun cours / لا توجد دورات
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Add Attendance Form -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Ajouter une présence</h3>
                                <span class="header-subtitle">إضافة حضور</span>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <form method="POST" action="{{ route('coach.attendance.store', $singleCoach->id) }}" class="attendance-form">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            Cours / الدورات
                                        </label>
                                        <select name="course_id" class="form-select-modern" required>
                                            @if(($coachCourses ?? collect())->count() <= 0)
                                                <option value="">Aucun cours disponible</option>
                                            @else
                                                <option value="">Sélectionner un cours...</option>
                                                @foreach(($coachCourses ?? []) as $c)
                                                    <option value="{{ $c->id }}">{{ $c->name }}{{ optional($c->gym)->name ? ' — ' . optional($c->gym)->name : '' }}{{ $c->start_date ? ' (' . $c->start_date->format('Y-m-d') . ($c->end_date ? ' → ' . $c->end_date->format('Y-m-d') : '') . ')' : '' }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            Date et heure
                                        </label>
                                        <input type="datetime-local" name="attendance_at" class="form-control-modern" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            Statut
                                        </label>
                                        <select name="status" class="form-select-modern" required>
                                            <option value="present">Présent</option>
                                            <option value="absent">Absent</option>
                                            <option value="excused">Excusé</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn-submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Enregistrer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Attendance -->
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Présences récentes</h3>
                                <span class="header-subtitle">الحضور الأخير</span>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Date / التاريخ</th>
                                        <th>Cours / الدورات</th>
                                        <th>Statut / الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($recentAttendances ?? []) as $a)
                                        <tr>
                                            <td>
                                                <div class="date-cell">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                    {{ $a->attendance_date ? $a->attendance_date->format('Y-m-d') : '—' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="session-tag">{{ optional(optional($a->participation)->course)->name ?? '—' }}</span>
                                            </td>
                                            <td>
                                                @php($status = $a->status)
                                                @if($status === 'present')
                                                    <span class="status-badge status-present">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                        Présent
                                                    </span>
                                                @elseif($status === 'absent')
                                                    <span class="status-badge status-absent">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                                        Absent
                                                    </span>
                                                @elseif($status === 'excused')
                                                    <span class="status-badge status-excused">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                                        Excusé
                                                    </span>
                                                @else
                                                    <span class="status-badge status-unknown">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="empty-cell">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                Aucune présence / لا يوجد حضور
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                    </table>
                        </div>
                    </div>

                    <div class="modern-card mt-4">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Calendrier des cours</h3>
                                <span class="header-subtitle">جدول الدورات</span>
                            </div>
                            <div class="calendar-nav">
                                <a class="calendar-nav-btn" href="{{ route('coach.show', ['coach' => $singleCoach->id, 'month' => ($calendarPrevMonth ?? null)]) }}">‹</a>
                                <span class="calendar-month-label">{{ $calendarMonthLabel ?? '' }}</span>
                                <a class="calendar-nav-btn" href="{{ route('coach.show', ['coach' => $singleCoach->id, 'month' => ($calendarNextMonth ?? null)]) }}">›</a>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            @if(($calendarSessionCount ?? 0) <= 0)
                                <div class="calendar-empty">
                                    Aucun cours associé / لا توجد دورات مرتبطة
                                </div>
                            @endif
                            <div class="calendar-wrapper">
                                <table class="calendar-table">
                                    <thead>
                                        <tr>
                                            <th>Lun</th>
                                            <th>Mar</th>
                                            <th>Mer</th>
                                            <th>Jeu</th>
                                            <th>Ven</th>
                                            <th>Sam</th>
                                            <th>Dim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(($calendarWeeks ?? []) as $week)
                                            <tr>
                                                @foreach(($week ?? []) as $day)
                                                    @php($cellClasses = trim(($day['in_month'] ?? false) ? '' : 'is-outside') . ' ' . trim(($day['is_today'] ?? false) ? 'is-today' : ''))
                                                    <td class="calendar-cell {{ $cellClasses }}">
                                                        <div class="calendar-cell-inner">
                                                            <div class="calendar-day-number">{{ $day['day'] ?? '' }}</div>
                                                            <div class="calendar-events">
                                                                @foreach(($day['sessions'] ?? []) as $session)
                                                                    <a class="calendar-event" href="{{ route('course.show', $session['course_id']) }}" title="{{ $session['course_name'] ?? '' }}{{ !empty($session['gym_name']) ? ' - ' . $session['gym_name'] : '' }}{{ !empty($session['time']) ? ' | ' . $session['time'] : '' }}{{ !empty($session['group_name']) ? ' | ' . $session['group_name'] : '' }}{{ !empty($session['location']) ? ' | ' . $session['location'] : '' }}{{ !empty($session['start_date']) ? ' (' . $session['start_date'] . (!empty($session['end_date']) ? ' → ' . $session['end_date'] : '') . ')' : '' }}">
                                                                        <span class="calendar-event-title">{{ $session['course_name'] ?? '' }}</span>
                                                                        <span class="calendar-event-meta">
                                                                            {{ $session['time'] ?? '' }}{{ !empty($session['group_name']) ? ' • ' . $session['group_name'] : '' }}
                                                                        </span>
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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
    --radius-xl: 24px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

/* Page Header */
.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.coach-id-badge {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--primary);
    background: var(--primary-bg);
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
}

.page-subtitle {
    font-size: 0.875rem;
    color: var(--gray-500);
    margin: 0.25rem 0 0;
}

/* Breadcrumb */
.breadcrumb {
    padding: 0;
    margin: 0;
    background: transparent;
    font-size: 0.875rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--gray-400);
}

.breadcrumb-link {
    color: var(--gray-500);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    transition: var(--transition);
}

.breadcrumb-link:hover {
    color: var(--primary);
}

/* Buttons */
.btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: transparent;
    color: var(--gray-600);
    font-weight: 500;
    font-size: 0.875rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-ghost:hover {
    background: var(--gray-100);
    border-color: var(--gray-400);
    color: var(--gray-700);
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: linear-gradient(135deg, var(--success) 0%, var(--success-dark) 100%);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition);
    width: 100%;
    box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.4);
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.5);
}

/* Profile Card */
.profile-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.profile-header {
    background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
    padding: 2rem;
    text-align: center;
}

.profile-avatar-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.3);
    box-shadow: var(--shadow-xl);
}

.avatar-status-indicator {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    background: var(--success);
    border: 3px solid white;
    border-radius: 50%;
}

.profile-name {
    color: white;
    font-size: 1.375rem;
    font-weight: 700;
    margin: 0 0 0.75rem;
}

.profile-email {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.profile-details {
    padding: 1.25rem;
    background: var(--gray-50);
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
}

.detail-item:not(:last-child) {
    border-bottom: 1px solid var(--gray-200);
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    box-shadow: var(--shadow-sm);
}

.detail-content {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.detail-value {
    font-weight: 600;
    color: var(--gray-900);
}

.profile-actions {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: var(--transition);
}

.action-card:hover {
    transform: translateX(4px);
}

.action-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-text {
    display: flex;
    flex-direction: column;
}

.action-label {
    font-weight: 600;
    font-size: 0.9375rem;
}

.action-label-ar {
    font-size: 0.75rem;
    opacity: 0.7;
}

.action-edit {
    background: #fef3c7;
}
.action-edit .action-icon { background: #fde68a; color: #d97706; }
.action-edit .action-label { color: #92400e; }
.action-edit .action-label-ar { color: #b45309; }

.action-report {
    background: var(--gray-100);
}
.action-report .action-icon { background: var(--gray-200); color: var(--gray-600); }
.action-report .action-label { color: var(--gray-700); }
.action-report .action-label-ar { color: var(--gray-500); }

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.mini-stat-card {
    background: white;
    border-radius: var(--radius-md);
    padding: 1rem;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: var(--transition);
}

.mini-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.mini-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-sessions .mini-stat-icon {
    background: var(--primary-bg);
    color: var(--primary);
}

.stat-present .mini-stat-icon {
    background: var(--success-light);
    color: var(--success);
}

.mini-stat-content {
    display: flex;
    flex-direction: column;
}

.mini-stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-900);
}

.mini-stat-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
}

/* Modern Card */
.modern-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.card-header-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-100);
}

.header-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon-primary {
    background: var(--primary-bg);
    color: var(--primary);
}

.header-icon-success {
    background: var(--success-light);
    color: var(--success);
}

.header-icon-warning {
    background: var(--warning-light);
    color: var(--warning);
}

.header-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}

.header-subtitle {
    font-size: 0.8125rem;
    color: var(--gray-500);
}

.card-body-modern {
    padding: 1.5rem;
}

/* Form Elements */
.form-label-modern {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.form-control-modern,
.form-select-modern {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    color: var(--gray-900);
    background: white;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    transition: var(--transition);
}

.form-control-modern:focus,
.form-select-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

/* Table */
.table-container {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: var(--gray-50);
}

.modern-table th {
    padding: 0.875rem 1.25rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid var(--gray-200);
}

.modern-table td {
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: var(--transition);
}

.modern-table tbody tr:hover {
    background: var(--gray-50);
}

.calendar-nav {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
}

.calendar-nav-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    background: white;
    color: var(--gray-700);
    text-decoration: none;
    transition: var(--transition);
}

.calendar-nav-btn:hover {
    border-color: var(--primary);
    background: var(--primary-bg);
    color: var(--primary-dark);
}

.calendar-month-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    white-space: nowrap;
}

.calendar-empty {
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    color: var(--gray-500);
}

.calendar-wrapper {
    overflow-x: auto;
}

.calendar-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0.5rem;
}

.calendar-table th {
    padding: 0 0.25rem;
    text-align: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.calendar-cell {
    height: 110px;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    vertical-align: top;
    padding: 0;
}

.calendar-cell.is-outside {
    background: var(--gray-50);
    opacity: 0.55;
}

.calendar-cell.is-today {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
}

.calendar-cell-inner {
    padding: 0.625rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.calendar-day-number {
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--gray-800);
}

.calendar-events {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    overflow: hidden;
}

.calendar-event {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    padding: 0.25rem 0.5rem;
    background: var(--primary-bg);
    color: var(--primary-dark);
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 700;
    white-space: normal;
    overflow: hidden;
    transition: var(--transition);
}

.calendar-event-title {
    display: block;
    line-height: 1.1;
}

.calendar-event-meta {
    display: block;
    font-weight: 600;
    font-size: 0.6875rem;
    color: var(--gray-700);
    line-height: 1.1;
}

.calendar-event:hover {
    background: var(--primary-light);
    color: var(--gray-900);
}

/* Session Name */
.session-name {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--gray-900);
}

.session-name svg {
    color: var(--primary);
}

/* Day Badge */
.day-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: var(--info-light);
    color: #0891b2;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: 9999px;
}

/* Time Badge */
.time-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.625rem;
    font-size: 0.8125rem;
    font-weight: 500;
    border-radius: var(--radius-sm);
}

.time-start {
    background: var(--success-light);
    color: var(--success-dark);
}

.time-end {
    background: var(--gray-100);
    color: var(--gray-600);
}

/* Date Cell */
.date-cell {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.date-cell svg {
    color: var(--gray-400);
}

/* Session Tag */
.session-tag {
    display: inline-flex;
    padding: 0.25rem 0.625rem;
    background: var(--primary-bg);
    color: var(--primary);
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: var(--radius-sm);
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: 9999px;
}

.status-present {
    background: var(--success-light);
    color: var(--success-dark);
}

.status-absent {
    background: var(--danger-light);
    color: var(--danger);
}

.status-excused {
    background: var(--warning-light);
    color: #92400e;
}

.status-unknown {
    background: var(--gray-100);
    color: var(--gray-500);
}

/* Empty Cell */
.empty-cell {
    text-align: center;
    padding: 2rem !important;
    color: var(--gray-400);
}

.empty-cell svg {
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 992px) {
    .page-title {
        font-size: 1.375rem;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .card-header-modern {
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .calendar-cell {
        height: 90px;
    }
    
    .attendance-form .row {
        gap: 1rem;
    }
    
    .attendance-form .col-md-2 {
        margin-top: 0.5rem;
    }
    
    .detail-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>
@endpush
@endsection
