@extends('layouts.user-layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        {{ __('messages.attendance_statistics') }} - {{ $course->name }}
                    </h3>
                    <div>
                        <a href="{{ route('course-attendance.create', $course->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.mark_attendance') }}
                        </a>
                        <a href="{{ route('course-attendance.index', $course->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Informations du cours -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-graduation-cap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.course') }}</span>
                                    <span class="info-box-number">{{ $course->name }}</span>
                                    @if($course->description)
                                        <span class="info-box-more">{{ $course->description }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.participants') }}</span>
                                    <span class="info-box-number">{{ $stats->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.total_sessions') }}</span>
                                    <span class="info-box-number">{{ $stats->sum('total_sessions') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($stats->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-chart-bar fa-3x mb-3"></i>
                            <h4>{{ __('messages.no_stats_available') }}</h4>
                            <p>{{ __('messages.no_attendance_recorded_course') }}</p>
                            <a href="{{ route('course-attendance.create', $course->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('messages.start_marking_attendance') }}
                            </a>
                        </div>
                    @else
                        <!-- Statistiques globales -->
                        @php
                            $totalPresents = $stats->sum('presents');
                            $totalAbsents = $stats->sum('absents');
                            $totalExcused = $stats->sum('excused');
                            $totalSessions = $stats->sum('total_sessions');
                            $globalPresenceRate = $totalSessions > 0 ? round(($totalPresents / $totalSessions) * 100, 1) : 0;
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $totalPresents }}</h3>
                                        <p>{{ __('messages.total_attendance') }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $totalAbsents }}</h3>
                                        <p>{{ __('messages.total_absences') }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $totalExcused }}</h3>
                                        <p>{{ __('messages.total_excused') }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-exclamation"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $globalPresenceRate }}%</h3>
                                        <p>{{ __('messages.global_attendance_rate') }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-percentage"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Graphique de présence (placeholder pour Chart.js) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ __('messages.attendance_breakdown') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="attendanceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtres -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <select id="categoryFilter" class="form-control">
                                    <option value="">🏷️ {{ __('messages.all_categories') }}</option>
                                    @php
                                        $categories = collect($stats)->map(function($stat) {
                                            return (object)['id' => $stat->category_id ?? null, 'name' => $stat->category_name ?? __('messages.no_category')];
                                        })->filter(fn($c) => $c->id !== null)->unique('id')->sortBy('name');
                                    @endphp
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select id="performanceFilter" class="form-control">
                                    <option value="">🎯 {{ __('messages.all_performances') }}</option>
                                    <option value="excellent">🏆 {{ __('messages.excellent') }} (≥80%)</option>
                                    <option value="average">👍 {{ __('messages.average') }} (60-79%)</option>
                                    <option value="poor">⚠️ {{ __('messages.poor') }} (<60%)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tableau détaillé des statistiques par participant -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="statisticsTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>{{ __('messages.athlete') }}</th>
                                        <th>{{ __('messages.email') }}</th>
                                        <th>{{ __('messages.category') }}</th>
                                        <th>{{ __('messages.presents') }}</th>
                                        <th>{{ __('messages.absents') }}</th>
                                        <th>{{ __('messages.excused') }}</th>
                                        <th>{{ __('messages.total_sessions') }}</th>
                                        <th>{{ __('messages.attendance_rate') }}</th>
                                        <th>{{ __('messages.performance') }}</th>
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
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar mr-2">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $stat->name }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $stat->email }}</td>
                                            <td>
                                                @if(isset($stat->category_name))
                                                    <span class="badge badge-info">{{ $stat->category_name }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-check"></i> {{ $stat->presents }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger badge-lg">
                                                    <i class="fas fa-times"></i> {{ $stat->absents }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning badge-lg">
                                                    <i class="fas fa-exclamation"></i> {{ $stat->excused }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info badge-lg">
                                                    <i class="fas fa-calendar"></i> {{ $stat->total_sessions }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-{{ $performanceClass }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $presenceRate }}%"
                                                         aria-valuenow="{{ $presenceRate }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        {{ $presenceRate }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($presenceRate >= 80)
                                                    <span class="badge badge-success badge-lg">
                                                        <i class="fas {{ $performanceIcon }}"></i> {{ __('messages.excellent') }}
                                                    </span>
                                                @elseif($presenceRate >= 60)
                                                    <span class="badge badge-warning badge-lg">
                                                        <i class="fas {{ $performanceIcon }}"></i> {{ __('messages.average') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger badge-lg">
                                                        <i class="fas {{ $performanceIcon }}"></i> {{ __('messages.poor') }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Résumé des performances -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ __('messages.performance_summary') }}</h5>
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
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="small-box bg-success">
                                                    <div class="inner">
                                                        <h3>{{ $excellentCount }}</h3>
                                                        <p>{{ __('messages.excellent_performance') }} (≥80%)</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-thumbs-up"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small-box bg-warning">
                                                    <div class="inner">
                                                        <h3>{{ $averageCount }}</h3>
                                                        <p>{{ __('messages.average_performance') }} (60-79%)</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-meh"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small-box bg-danger">
                                                    <div class="inner">
                                                        <h3>{{ $poorCount }}</h3>
                                                        <p>{{ __('messages.poor_performance') }} (<60%)</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-thumbs-down"></i>
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
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    @if($stats->isNotEmpty())
        // Données pour le graphique
        const chartData = {
            labels: ['Présents', 'Absents', 'Excusés'],
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
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $totalSessions }};
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        };

        // Créer le graphique
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, config);
    @endif

    // Initialiser DataTable pour le tableau des statistiques
    const table = $('#statisticsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "order": [[ 7, "desc" ]], // Trier par taux de présence décroissant (index ajusté)
        "pageLength": 25,
        "responsive": true
    });

    // Filter by category
    $('#categoryFilter').on('change', function() {
        const categoryId = $(this).val();
        if (categoryId) {
            table.rows().every(function() {
                const row = this.node();
                const rowCategory = $(row).data('category');
                if (String(rowCategory) === categoryId) {
                    $(row).show();
                } else {
                    $(row).hide();
                }
            });
        } else {
            table.rows().every(function() {
                $(this.node()).show();
            });
        }
        table.draw();
    });

    // Filter by performance
    $('#performanceFilter').on('change', function() {
        const performance = $(this).val();
        if (performance) {
            table.rows().every(function() {
                const row = this.node();
                const rowPerformance = $(row).data('performance');
                if (rowPerformance === performance) {
                    $(row).show();
                } else {
                    $(row).hide();
                }
            });
        } else {
            table.rows().every(function() {
                $(this.node()).show();
            });
        }
        table.draw();
    });
});
</script>
@endpush
