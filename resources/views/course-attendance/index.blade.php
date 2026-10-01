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
                                <a href="/" class="breadcrumb-link" aria-label="Accueil">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar" dir="rtl">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <span class="label-fr">Présence</span>
                                <span class="label-ar" dir="rtl">الحضور</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-clipboard-check" aria-hidden="true"></i>
                        @if(($viewMode ?? 'groups') === 'courses' && $selectedGroup)
                            <span class="label-fr">Présence - {{ $selectedGroup->name }}</span>
                            <span class="label-ar" dir="rtl">الحضور - {{ $selectedGroup->name }}</span>
                        @else
                            <span class="label-fr">Gestion de la Présence</span>
                            <span class="label-ar" dir="rtl">إدارة الحضور</span>
                        @endif
                    </h1>
                </div>
                @if(($viewMode ?? 'groups') === 'courses')
                    <div class="d-flex gap-2">
                        <a href="{{ route('course-attendance.index') }}" class="btn-ghost" aria-label="Retour aux groupes">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i>
                            <span class="label-fr">Groupes</span>
                            <span class="label-ar" dir="rtl">المجموعات</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="modern-card shadow-sm">
                <div class="modern-card-header">
                    <h5 class="modern-card-title mb-0">
                        <i class="fas fa-layer-group mr-2" aria-hidden="true"></i>
                        @if(($viewMode ?? 'groups') === 'courses' && $selectedGroup)
                            <span class="label-fr">Cours du groupe</span>
                            <span class="label-ar" dir="rtl">دورات المجموعة</span>
                        @else
                            <span class="label-fr">Liste des Groupes</span>
                            <span class="label-ar" dir="rtl">قائمة المجموعات</span>
                        @endif
                    </h5>
                </div>
                <div class="modern-card-body p-0">
                    @if(($viewMode ?? 'groups') === 'groups')
                        {{-- Groups View --}}
                        @if($groups->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-modern mb-0" role="table" aria-label="Liste des groupes">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="px-4" scope="col">
                                                <span class="label-fr">Groupe</span>
                                                <span class="label-ar" dir="rtl">المجموعة</span>
                                            </th>
                                            <th class="text-center" scope="col">
                                                <span class="label-fr">Membres</span>
                                                <span class="label-ar" dir="rtl">الأعضاء</span>
                                            </th>
                                            <th class="text-center" scope="col">
                                                <span class="label-fr">Inscrits</span>
                                                <span class="label-ar" dir="rtl">المسجلين</span>
                                            </th>
                                            <th class="text-center px-4 actions-col" scope="col">
                                                <span class="label-fr">Actions</span>
                                                <span class="label-ar" dir="rtl">الإجراءات</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groups as $group)
                                            <tr class="align-middle">
                                                <td class="px-4">
                                                    <span class="font-weight-bold text-dark">{{ $group->name }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-info-soft badge-pill" title="Membres">
                                                        {{ (int) ($group->users_count ?? 0) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-secondary-soft badge-pill" title="Inscrits">
                                                        {{ (int) ($group->enrolled_users ?? 0) }}
                                                    </span>
                                                </td>
                                                <td class="text-center px-4">
                                                    <a href="{{ route('course-attendance.index', ['group_id' => $group->id]) }}" 
                                                       class="btn-primary-modern btn-sm"
                                                       aria-label="Voir les cours du groupe {{ $group->name }}">
                                                        <i class="fas fa-list mr-1" aria-hidden="true"></i>
                                                        <span class="label-fr">Voir cours</span>
                                                        <span class="label-ar" dir="rtl">عرض الدورات</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-5 text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted mb-3" aria-hidden="true"></i>
                                    <h5 class="text-muted mb-0">
                                        <span class="label-fr">Aucun groupe disponible</span>
                                        <span class="label-ar" dir="rtl">لا توجد مجموعات متاحة</span>
                                    </h5>
                                </div>
                            </div>
                        @endif
                    @else
                        {{-- Courses View --}}
                        @if($courses && $courses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-modern mb-0" role="table" aria-label="Liste des cours">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="px-4" scope="col">
                                                <span class="label-fr">Cours</span>
                                                <span class="label-ar" dir="rtl">الدورة</span>
                                            </th>
                                            <th class="text-center" scope="col">
                                                <span class="label-fr">Participants</span>
                                                <span class="label-ar" dir="rtl">المشاركين</span>
                                            </th>
                                            <th class="text-center" scope="col">
                                                <span class="label-fr">Présents</span>
                                                <span class="label-ar" dir="rtl">الحاضرين</span>
                                            </th>
                                            <th class="text-center" scope="col">
                                                <span class="label-fr">Absents</span>
                                                <span class="label-ar" dir="rtl">الغائبين</span>
                                            </th>
                                            <th class="text-center" scope="col">
                                                <span class="label-fr">Excusés</span>
                                                <span class="label-ar" dir="rtl">المبررين</span>
                                            </th>
                                            <th class="text-center" scope="col">
                                                <span class="label-fr">Dernière</span>
                                                <span class="label-ar" dir="rtl">آخر حضور</span>
                                            </th>
                                            <th class="text-center px-4 actions-col" scope="col">
                                                <span class="label-fr">Actions</span>
                                                <span class="label-ar" dir="rtl">الإجراءات</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($courses as $course)
                                            <tr class="align-middle">
                                                <td class="px-4">
                                                    <div class="font-weight-bold text-dark mb-1">{{ $course->name }}</div>
                                                    @if($course->description)
                                                        <div class="small text-muted">{{ Str::limit($course->description, 40) }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-info-soft badge-pill" title="Participants">
                                                        {{ $course->total_participants }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-success-soft badge-pill" title="Présents">
                                                        {{ $course->presents }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-danger-soft badge-pill" title="Absents">
                                                        {{ $course->absents }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-warning-soft badge-pill" title="Excusés">
                                                        {{ $course->excused }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($course->last_attendance_date)
                                                        <span class="small text-dark font-weight-bold" title="Dernière présence">
                                                            {{ $course->last_attendance_date->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-center px-4">
                                                    @php
                                                        $presenceQuery = http_build_query(['filter_type' => 'group', 'filter_value' => $selectedGroup->id]);
                                                    @endphp
                                                    <div class="btn-group-actions">
                                                        <a href="{{ route('course-attendance.create', $course->id) . '?' . $presenceQuery }}"
                                                           class="btn-primary-modern btn-sm"
                                                           aria-label="Marquer la présence pour {{ $course->name }}">
                                                            <i class="fas fa-check mr-1" aria-hidden="true"></i>
                                                            <span class="label-fr">Présence</span>
                                                            <span class="label-ar" dir="rtl">حضور</span>
                                                        </a>
                                                        <a href="{{ route('course-attendance.statistics', $course->id) }}"
                                                           class="btn-ghost btn-sm border"
                                                           aria-label="Voir les statistiques de {{ $course->name }}">
                                                            <i class="fas fa-chart-bar mr-1" aria-hidden="true"></i>
                                                            <span class="label-fr">Stats</span>
                                                            <span class="label-ar" dir="rtl">إحصائيات</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($courses->hasPages())
                                <div class="p-4 d-flex justify-content-center">
                                    {{ $courses->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        @else
                            <div class="p-5 text-center">
                                <div class="empty-state">
                                    <i class="fas fa-book fa-3x text-muted mb-3" aria-hidden="true"></i>
                                    <h5 class="text-muted mb-0">
                                        <span class="label-fr">Aucun cours trouvé pour ce groupe</span>
                                        <span class="label-ar" dir="rtl">لم يتم العثور على دورات لهذا المجموعة</span>
                                    </h5>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    /* Table Styling */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead.thead-dark th { 
        white-space: nowrap; 
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-modern thead.thead-dark th span {
        color: #ffffff !important;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e9ecef;
    }

    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .table-modern tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border: none;
    }

    .table-modern .align-middle {
        vertical-align: middle !important;
    }

    /* Card Styling */
    .modern-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }

    .modern-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .modern-card-title, 
    .modern-card-title i, 
    .modern-card-title span {
        color: #ffffff !important;
        font-weight: 600;
    }

    /* Badge Styling */
    .badge {
        font-size: 0.875rem;
        padding: 0.35rem 0.75rem;
        font-weight: 600;
        min-width: 45px;
        display: inline-block;
    }

    .badge-pill {
        border-radius: 50rem;
    }

    .badge-info-soft {
        background-color: #e7f5ff;
        color: #0c5ba1;
    }

    .badge-success-soft {
        background-color: #d4f4dd;
        color: #0f7725;
    }

    .badge-danger-soft {
        background-color: #ffe5e5;
        color: #c41e1e;
    }

    .badge-warning-soft {
        background-color: #fff4e5;
        color: #b54b00;
    }

    .badge-secondary-soft {
        background-color: #e9ecef;
        color: #495057;
    }

    /* Button Actions */
    .btn-group-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary-modern,
    .btn-ghost {
        transition: all 0.2s ease;
        font-weight: 500;
        border-radius: 6px;
        white-space: nowrap;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-ghost:hover {
        background-color: #f8f9fa;
    }

    /* Empty State */
    .empty-state {
        padding: 2rem;
    }

    .empty-state i {
        opacity: 0.5;
    }

    /* Responsive Table */
    .actions-col {
        min-width: 200px;
    }

    @media (max-width: 1200px) {
        .table-modern thead th {
            font-size: 0.8rem;
            padding: 0.875rem 0.5rem;
        }
        
        .table-modern tbody td {
            padding: 0.875rem 0.5rem;
        }
    }

    @media (max-width: 768px) {
        .btn-group-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-group-actions .btn {
            width: 100%;
            margin-bottom: 0.25rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .modern-card-header {
            padding: 1rem;
        }

        .table-modern thead th {
            font-size: 0.75rem;
            padding: 0.75rem 0.35rem;
        }

        .table-modern tbody td {
            padding: 0.75rem 0.35rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .actions-col {
            min-width: 150px;
        }
    }

    /* Print Styles */
    @media print {
        .btn-group-actions,
        .btn-ghost,
        .breadcrumb {
            display: none !important;
        }

        .modern-card {
            box-shadow: none !important;
        }

        .table-modern tbody tr:hover {
            background-color: transparent !important;
            transform: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection