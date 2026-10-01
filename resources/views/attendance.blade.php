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
                                    <span class="label-ar" dir="rtl">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Historique de Présence</span>
                                <span class="label-ar" dir="rtl">سجل الحضور</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-history"></i>
                        <span class="label-fr">Historique des Présences</span>
                        <span class="label-ar" dir="rtl">سجل الحضور</span>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card stat-primary h-100">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $stats['total'] }}</span>
                            <span class="stat-label">
                                <span class="label-fr">Total</span>
                                <span class="label-ar" dir="rtl">الإجمالي</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-success h-100">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $stats['present'] }}</span>
                            <span class="stat-label">
                                <span class="label-fr">Présents</span>
                                <span class="label-ar" dir="rtl">حاضرون</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-danger h-100">
                        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $stats['absent'] }}</span>
                            <span class="stat-label">
                                <span class="label-fr">Absents</span>
                                <span class="label-ar" dir="rtl">غائبون</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-warning h-100">
                        <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $stats['excused'] }}</span>
                            <span class="stat-label">
                                <span class="label-fr">Excusés</span>
                                <span class="label-ar" dir="rtl">معذورون</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="fas fa-list mr-2"></i>
                        <span class="label-fr">Liste des présences</span>
                        <span class="label-ar" dir="rtl">قائمة الحضور</span>
                    </h5>
                </div>
                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <span class="label-fr">Membre</span>
                                        <span class="label-ar" dir="rtl">العضو</span>
                                    </th>
                                    <th>
                                        <span class="label-fr">Session</span>
                                        <span class="label-ar" dir="rtl">الحصة</span>
                                    </th>
                                    <th class="text-center">
                                        <span class="label-fr">Date & Heure</span>
                                        <span class="label-ar" dir="rtl">التاريخ والوقت</span>
                                    </th>
                                    <th class="text-center">
                                        <span class="label-fr">Statut</span>
                                        <span class="label-ar" dir="rtl">الحالة</span>
                                    </th>
                                    @role('admin|cityManager')
                                    <th>
                                        <span class="label-fr">Gym</span>
                                        <span class="label-ar" dir="rtl">النادي</span>
                                    </th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm avatar-placeholder mr-3">
                                                    {{ strtoupper(substr($attendance->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $attendance->name }}</div>
                                                    <div class="small text-muted">{{ $attendance->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-primary">{{ $attendance->training_session_name }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="font-weight-bold">{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}</div>
                                            <div class="small text-muted">{{ $attendance->attendance_time }}</div>
                                        </td>
                                        <td class="text-center">
                                            @if($attendance->status == 'present')
                                                <span class="badge badge-success-soft px-3">
                                                    <span class="label-fr">Présent</span>
                                                    <span class="label-ar" dir="rtl">حاضر</span>
                                                </span>
                                            @elseif($attendance->status == 'absent')
                                                <span class="badge badge-danger-soft px-3">
                                                    <span class="label-fr">Absent</span>
                                                    <span class="label-ar" dir="rtl">غائب</span>
                                                </span>
                                            @else
                                                <span class="badge badge-warning-soft px-3">
                                                    <span class="label-fr">Excusé</span>
                                                    <span class="label-ar" dir="rtl">معذور</span>
                                                </span>
                                            @endif
                                        </td>
                                        @role('admin|cityManager')
                                        <td>
                                            <div class="small">
                                                <i class="fas fa-dumbbell mr-1 text-muted"></i> {{ $attendance->gym_name }}
                                                @role('admin')
                                                    <br><i class="fas fa-map-marker-alt mr-1 text-muted"></i> {{ $attendance->gym_city }}
                                                @endrole
                                            </div>
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="p-4 d-flex justify-content-center">
                            {{ $attendances->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    .table thead th { 
        white-space: nowrap; 
        background-color: #343a40 !important; 
        color: #ffffff !important; 
        padding: 15px;
        font-weight: 600;
    }
    .table thead th span {
        color: #ffffff !important;
    }
    .modern-card-header {
        background-color: #343a40 !important;
    }
    .modern-card-title, .modern-card-title i, .modern-card-title span {
        color: #ffffff !important;
    }
    .badge-modern {
        padding: 0.5em 1em;
        font-weight: 600;
    }
</style>
@endpush
@endsection
