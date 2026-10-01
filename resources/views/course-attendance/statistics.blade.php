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
                            <li class="breadcrumb-item">
                                <a href="{{ route('course.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Cours</span>
                                    <span class="label-ar" dir="rtl">الدورات</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Statistiques</span>
                                <span class="label-ar" dir="rtl">الإحصائيات</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-chart-bar"></i>
                        <span class="label-fr">Statistiques de présence</span>
                        <span class="label-ar" dir="rtl">إحصائيات الحضور</span>
                    </h1>
                    <p class="page-subtitle">
                        {{ $course->name }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('course-attendance.create', $course->id) }}" class="btn-primary-modern">
                        <i class="fas fa-plus"></i>
                        <span class="label-fr">Marquer la présence</span>
                        <span class="label-ar" dir="rtl">تسجيل الحضور</span>
                    </a>
                    <a href="{{ route('course-attendance.index', $course->id) }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar" dir="rtl">عودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if($stats->isEmpty())
                <div class="modern-card p-5 text-center">
                    <div class="empty-state">
                        <i class="fas fa-chart-bar fa-4x text-muted mb-4"></i>
                        <h4 class="font-weight-bold">
                            <span class="label-fr">Aucune statistique disponible</span>
                            <span class="label-ar" dir="rtl">لا توجد إحصائيات متاحة</span>
                        </h4>
                        <p class="text-muted mb-4">
                            <span class="label-fr">Aucune présence n'a encore été enregistrée pour ce cours.</span>
                            <span class="label-ar" dir="rtl">لم يتم تسجيل أي حضور لهذه الدورة بعد.</span>
                        </p>
                        <a href="{{ route('course-attendance.create', $course->id) }}" class="btn-primary-modern">
                            <i class="fas fa-plus"></i>
                            <span class="label-fr">Commencer à marquer la présence</span>
                            <span class="label-ar" dir="rtl">بدء تسجيل الحضور</span>
                        </a>
                    </div>
                </div>
            @else
                @php
                    $totalPresents = $stats->sum('presents');
                    $totalAbsents = $stats->sum('absents');
                    $totalExcused = $stats->sum('excused');
                    $totalSessions = $stats->sum('total_sessions');
                    $globalPresenceRate = $totalSessions > 0 ? round(($totalPresents / $totalSessions) * 100, 1) : 0;
                @endphp

                <!-- Stat Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-value">{{ $totalPresents }}</h3>
                                    <p class="stat-label">
                                        <span class="label-fr">Présences totales</span>
                                        <span class="label-ar" dir="rtl">إجمالي الحضور</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card stat-card-danger">
                            <div class="stat-card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-value">{{ $totalAbsents }}</h3>
                                    <p class="stat-label">
                                        <span class="label-fr">Absences totales</span>
                                        <span class="label-ar" dir="rtl">إجمالي الغياب</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-value">{{ $totalExcused }}</h3>
                                    <p class="stat-label">
                                        <span class="label-fr">Justifiés totaux</span>
                                        <span class="label-ar" dir="rtl">إجمالي المبررات</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-value">{{ $globalPresenceRate }}%</h3>
                                    <p class="stat-label">
                                        <span class="label-fr">Taux global</span>
                                        <span class="label-ar" dir="rtl">المعدل العام</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphique de présence -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="modern-card">
                            <div class="modern-card-header">
                                <h5 class="modern-card-title">
                                    <i class="fas fa-chart-pie mr-2"></i>
                                    <span class="label-fr">Répartition des présences</span>
                                    <span class="label-ar" dir="rtl">توزيع الحضور</span>
                                </h5>
                            </div>
                            <div class="modern-card-body">
                                <div style="height: 300px; position: relative;">
                                    <canvas id="attendanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="modern-card mb-4">
                    <div class="modern-card-body p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-md-0">
                                    <label class="form-label">
                                        <span class="label-fr">Filtrer par catégorie</span>
                                        <span class="label-ar" dir="rtl">تصفية حسب الفئة</span>
                                    </label>
                                    <select id="categoryFilter" class="form-control select2">
                                        <option value="">
                                            <span class="label-fr">Toutes les catégories</span>
                                            <span class="label-ar" dir="rtl">جميع الفئات</span>
                                        </option>
                                        @php
                                            $categories = collect($stats)->map(function($stat) {
                                                return (object)['id' => $stat->category_id ?? null, 'name' => $stat->category_name ?? 'Sans catégorie'];
                                            })->filter(fn($c) => $c->id !== null)->unique('id')->sortBy('name');
                                        @endphp
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label">
                                        <span class="label-fr">Filtrer par performance</span>
                                        <span class="label-ar" dir="rtl">تصفية حسب الأداء</span>
                                    </label>
                                    <select id="performanceFilter" class="form-control select2">
                                        <option value="">
                                            <span class="label-fr">Toutes les performances</span>
                                            <span class="label-ar" dir="rtl">جميع مستويات الأداء</span>
                                        </option>
                                        <option value="excellent">
                                            🏆 <span class="label-fr">Excellent (≥80%)</span>
                                            <span class="label-ar" dir="rtl">ممتاز (≥80%)</span>
                                        </option>
                                        <option value="average">
                                            👍 <span class="label-fr">Moyen (60-79%)</span>
                                            <span class="label-ar" dir="rtl">متوسط (60-79%)</span>
                                        </option>
                                        <option value="poor">
                                            ⚠️ <span class="label-fr">Faible (<60%)</span>
                                            <span class="label-ar" dir="rtl">ضعيف (<60%)</span>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau détaillé -->
                <div class="modern-card">
                    <div class="modern-card-header d-flex justify-content-between align-items-center">
                        <h5 class="modern-card-title">
                            <i class="fas fa-list mr-2"></i>
                            <span class="label-fr">Détails par participant</span>
                            <span class="label-ar" dir="rtl">التفاصيل حسب المشارك</span>
                        </h5>
                    </div>
                    <div class="modern-card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="statisticsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4">
                                            <span class="label-fr">Athlète</span>
                                            <span class="label-ar" dir="rtl">اللاعب</span>
                                        </th>
                                        <th>
                                            <span class="label-fr">Catégorie</span>
                                            <span class="label-ar" dir="rtl">الفئة</span>
                                        </th>
                                        <th class="text-center" title="Présents / حاضر">
                                            <span class="label-fr">Prés.</span>
                                            <span class="label-ar" dir="rtl">حضر</span>
                                        </th>
                                        <th class="text-center" title="Absents / غائب">
                                            <span class="label-fr">Abs.</span>
                                            <span class="label-ar" dir="rtl">غاب</span>
                                        </th>
                                        <th class="text-center" title="Justifiés / مبرر">
                                            <span class="label-fr">Just.</span>
                                            <span class="label-ar" dir="rtl">برر</span>
                                        </th>
                                        <th class="text-center">
                                            <span class="label-fr">Total</span>
                                            <span class="label-ar" dir="rtl">المجموع</span>
                                        </th>
                                        <th style="width: 150px;">
                                            <span class="label-fr">Taux</span>
                                            <span class="label-ar" dir="rtl">المعدل</span>
                                        </th>
                                        <th class="text-center">
                                            <span class="label-fr">Performance</span>
                                            <span class="label-ar" dir="rtl">الأداء</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats->sortByDesc(function($stat) { return $stat->total_sessions > 0 ? ($stat->presents / $stat->total_sessions) : 0; }) as $stat)
                                        @php
                                            $presenceRate = $stat->total_sessions > 0 ? round(($stat->presents / $stat->total_sessions) * 100, 1) : 0;
                                            $performanceClass = $presenceRate >= 80 ? 'success' : ($presenceRate >= 60 ? 'warning' : 'danger');
                                            $performanceIcon = $presenceRate >= 80 ? 'fa-thumbs-up' : ($presenceRate >= 60 ? 'fa-meh' : 'fa-thumbs-down');
                                        @endphp
                                        <tr data-category="{{ $stat->category_id ?? '' }}" data-performance="{{ $presenceRate >= 80 ? 'excellent' : ($presenceRate >= 60 ? 'average' : 'poor') }}">
                                            <td class="px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm avatar-placeholder mr-3">
                                                        {{ strtoupper(substr($stat->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold text-dark">{{ $stat->name }}</div>
                                                        <div class="small text-muted">{{ $stat->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(isset($stat->category_name))
                                                    <span class="badge badge-light border">{{ $stat->category_name }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center font-weight-bold text-success">{{ $stat->presents }}</td>
                                            <td class="text-center font-weight-bold text-danger">{{ $stat->absents }}</td>
                                            <td class="text-center font-weight-bold text-warning">{{ $stat->excused }}</td>
                                            <td class="text-center font-weight-bold text-primary">{{ $stat->total_sessions }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px;">
                                                        <div class="progress-bar bg-{{ $performanceClass }}" 
                                                             role="progressbar" 
                                                             style="width: {{ $presenceRate }}%"
                                                             aria-valuenow="{{ $presenceRate }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span class="ml-2 small font-weight-bold">{{ $presenceRate }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($presenceRate >= 80)
                                                    <span class="badge badge-success-soft">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        <span class="label-fr">Excellent</span>
                                                        <span class="label-ar" dir="rtl">ممتاز</span>
                                                    </span>
                                                @elseif($presenceRate >= 60)
                                                    <span class="badge badge-warning-soft">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        <span class="label-fr">Moyen</span>
                                                        <span class="label-ar" dir="rtl">متوسط</span>
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger-soft">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                        <span class="label-fr">Faible</span>
                                                        <span class="label-ar" dir="rtl">ضعيف</span>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Résumé des performances -->
                <div class="row mt-4 mb-5">
                    <div class="col-12">
                        <div class="modern-card">
                            <div class="modern-card-header">
                                <h5 class="modern-card-title">
                                    <i class="fas fa-trophy mr-2 text-warning"></i>
                                    <span class="label-fr">Résumé des performances</span>
                                    <span class="label-ar" dir="rtl">ملخص الأداء</span>
                                </h5>
                            </div>
                            <div class="modern-card-body">
                                @php
                                    $excellentCount = $stats->filter(function($stat) {
                                        $rate = $stat->total_sessions > 0 ? ($stat->presents / $stat->total_sessions) * 100 : 0;
                                        return $rate >= 80;
                                    })->count();
                                    
                                    $averageCount = $stats->filter(function($stat) {
                                        $rate = $stat->total_sessions > 0 ? ($stat->presents / $stat->total_sessions) * 100 : 0;
                                        return $rate >= 60 && $rate < 80;
                                    })->count();
                                    
                                    $poorCount = $stats->filter(function($stat) {
                                        $rate = $stat->total_sessions > 0 ? ($stat->presents / $stat->total_sessions) * 100 : 0;
                                        return $rate < 60;
                                    })->count();
                                @endphp
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="stat-card stat-card-success border-0 shadow-none bg-light">
                                            <div class="stat-card-body p-3">
                                                <div class="stat-icon bg-white" style="width: 40px; height: 40px;"><i class="fas fa-medal text-success"></i></div>
                                                <div class="stat-info">
                                                    <h3 class="stat-value text-success mb-0">{{ $excellentCount }}</h3>
                                                    <p class="stat-label mb-0">
                                                        <span class="label-fr">Excellent (≥80%)</span>
                                                        <span class="label-ar" dir="rtl">ممتاز (≥80%)</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card stat-card-warning border-0 shadow-none bg-light">
                                            <div class="stat-card-body p-3">
                                                <div class="stat-icon bg-white" style="width: 40px; height: 40px;"><i class="fas fa-user-check text-warning"></i></div>
                                                <div class="stat-info">
                                                    <h3 class="stat-value text-warning mb-0">{{ $averageCount }}</h3>
                                                    <p class="stat-label mb-0">
                                                        <span class="label-fr">Moyen (60-79%)</span>
                                                        <span class="label-ar" dir="rtl">متوسط (60-79%)</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card stat-card-danger border-0 shadow-none bg-light">
                                            <div class="stat-card-body p-3">
                                                <div class="stat-icon bg-white" style="width: 40px; height: 40px;"><i class="fas fa-exclamation-triangle text-danger"></i></div>
                                                <div class="stat-info">
                                                    <h3 class="stat-value text-danger mb-0">{{ $poorCount }}</h3>
                                                    <p class="stat-label mb-0">
                                                        <span class="label-fr">Faible (<60%)</span>
                                                        <span class="label-ar" dir="rtl">ضعيف (<60%)</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialiser Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        minimumResultsForSearch: Infinity
    });

    @if($stats->isNotEmpty())
        // Données pour le graphique
        const chartData = {
            labels: [
                'Présents / حاضرون', 
                'Absents / غائبون', 
                'Excusés / معذورون'
            ],
            datasets: [{
                data: [{{ $totalPresents }}, {{ $totalAbsents }}, {{ $totalExcused }}],
                backgroundColor: [
                    '#28a745', // Vert pour présents
                    '#dc3545', // Rouge pour absents
                    '#ffc107'  // Jaune pour excusés
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };

        // Configuration du graphique
        const config = {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $totalPresents + $totalAbsents + $totalExcused }};
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        };

        // Créer le graphique
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, config);
    @endif

    // Initialiser DataTable
    const table = $('#statisticsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "order": [[ 6, "desc" ]], // Trier par taux de présence (index 6)
        "pageLength": 25,
        "dom": 'rtip', // Masquer la barre de recherche par défaut
        "columnDefs": [
            { "orderable": false, "targets": [7] } // Performance non triable
        ]
    });

    // Filtre par catégorie (Colonne index 1)
    $('#categoryFilter').on('change', function() {
        table.column(1).search(this.value).draw();
    });

    // Filtre par performance (Custom filter)
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            const filterVal = $('#performanceFilter').val();
            if (!filterVal) return true;
            
            const rowPerformance = $(table.row(dataIndex).node()).data('performance');
            return rowPerformance === filterVal;
        }
    );

    $('#performanceFilter').on('change', function() {
        table.draw();
    });
});
</script>
@endpush
