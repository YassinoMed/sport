@extends('layouts.user-layout')

@section('content')
@php
$isRtl = app()->getLocale() === 'ar';
$hasFinanceAccess = auth()->user()->hasAnyRole(['admin', 'cityManager', 'gymManager']);
@endphp

<div class="content-wrapper pb-5 {{ $isRtl ? 'text-right' : '' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <!-- Print Header (Visible only when printing) -->
    <div class="print-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 style="margin:0; color:#000;">{{ __('messages.user_report') }}</h1>
                <p style="margin:0; font-size:14pt;">{{ $user->name }} — {{ $month }}</p>
            </div>
            <div class="text-right">
                <p style="margin:0;">{{ date('d/m/Y H:i') }}</p>
                <p style="margin:0;">ID: #{{ $user->id }}</p>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="/" class="breadcrumb-link">
                                    <i class="fas fa-home"></i> {{ __('messages.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('allUsers.list') }}" class="breadcrumb-link">
                                    {{ __('messages.users') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('allUsers.show', $user->id) }}" class="breadcrumb-link">
                                    {{ $user->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('messages.report') }}</li>
                        </ol>
                    </nav>

                    <h1 class="page-title">
                        <i class="fas fa-file-alt me-2"></i>{{ __('messages.user_report') }}
                        <span class="user-id-badge">#{{ $user->id }}</span>
                    </h1>
                    <p class="page-subtitle">{{ $user->name }} — {{ $month }}</p>
                </div>

                <div class="header-actions">
                    <!-- Global Search -->
                    <div class="search-group">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="reportTableSearch" class="form-control-modern border-start-0"
                                placeholder="Rechercher... (F)" aria-label="Search">
                        </div>
                    </div>

                    <!-- Quick Nav -->
                    <div class="quick-filters ms-2 d-none d-md-flex">
                        <a href="#summarySection" class="btn-preset" title="Résumé">
                            <i class="fas fa-chart-pie"></i>
                        </a>
                        <a href="#incomeSection" class="btn-preset" title="Revenus">
                            <i class="fas fa-plus-circle text-success"></i>
                        </a>
                        <a href="#expenseSection" class="btn-preset" title="Dépenses">
                            <i class="fas fa-minus-circle text-danger"></i>
                        </a>
                        <a href="#eventSection" class="btn-preset" title="Événements">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </a>
                    </div>

                    <!-- Month Presets -->
                    <div class="quick-filters me-3">
                        <a href="{{ route('allUsers.report', ['id' => $user->id, 'month' => \Carbon\Carbon::now()->format('Y-m')]) }}"
                            class="btn-preset {{ $month === \Carbon\Carbon::now()->format('Y-m') ? 'active' : '' }}"
                            title="Ce mois">
                            <i class="fas fa-calendar-day"></i> Ce mois
                        </a>

                        <a href="{{ route('allUsers.report', ['id' => $user->id, 'month' => \Carbon\Carbon::now()->subMonth()->format('Y-m')]) }}"
                            class="btn-preset {{ $month === \Carbon\Carbon::now()->subMonth()->format('Y-m') ? 'active' : '' }}"
                            title="Mois dernier">
                            <i class="fas fa-calendar-minus"></i> Mois dernier
                        </a>
                    </div>

                    <form method="GET" action="{{ route('allUsers.report', $user->id) }}" class="filter-form">
                        <div class="filter-group">
                            <input type="month" name="month" class="form-control-modern" value="{{ $month }}">
                            <button type="submit" class="btn-primary-modern">
                                <i class="fas fa-filter"></i> {{ __('messages.filter') }}
                            </button>
                        </div>
                    </form>

                    <button type="button" class="btn-ghost" onclick="window.print()">
                        <i class="fas fa-print"></i> {{ __('messages.print') }}
                    </button>

                    <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" id="btnGeneratePdf" class="btn-pdf-generate" target="_blank">
                        <i class="fas fa-file-pdf"></i> <span>Générer en PDF</span>
                    </a>

                    <a href="{{ route('allUsers.show', $user->id) }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_profile') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Executive Summary -->
            <div id="summarySection" class="executive-summary mb-4">
                <div class="summary-header">
                    <i class="fas fa-eye"></i>
                    <span>{{ __('messages.at_a_glance') ?? 'En un coup d\'œil' }}</span>
                </div>

                <div class="summary-kpis">
                    @hasanyrole('admin|cityManager|gymManager')
                    <div class="kpi-item kpi-primary">
                        <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
                        <div class="kpi-content">
                            <span class="kpi-value">{{ number_format(($summary['total_cents'] ?? 0) / 100, 2, ',', ' ')
                                }} TND</span>
                            <span class="kpi-label">{{ __('messages.total_revenue') }}</span>
                        </div>
                    </div>
                    @endhasanyrole

                    <div class="kpi-item kpi-success">
                        <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="kpi-content">
                            <span class="kpi-value">{{ $userPresenceRate ?? 0 }}%</span>
                            <span class="kpi-label">{{ __('messages.presence_rate') ?? 'Taux de présence' }}</span>
                            @if(isset($clubAverages['avg_presence_rate']))
                            <span
                                class="kpi-comparison {{ $userPresenceRate >= $clubAverages['avg_presence_rate'] ? 'positive' : 'negative' }}">
                                <i
                                    class="fas fa-{{ $userPresenceRate >= $clubAverages['avg_presence_rate'] ? 'arrow-up' : 'arrow-down' }}"></i>
                                Moyenne club: {{ $clubAverages['avg_presence_rate'] }}%
                            </span>
                            @endif
                        </div>
                    </div>

                    @hasanyrole('admin|cityManager|gymManager')
                    <div class="kpi-item kpi-danger">
                        <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="kpi-content">
                            <span class="kpi-value">{{ number_format(($unpaidTotalCents ?? 0) / 100, 2, ',', ' ') }}
                                TND</span>
                            <span class="kpi-label">{{ __('messages.total_due') ?? 'Montant dû total' }}</span>
                            @if(($unpaidTotalCents ?? 0) > 0)
                            <a href="{{ route('PaymentPackage.stripe') }}" class="kpi-action">
                                <i class="fas fa-plus-circle"></i> Régulariser
                            </a>
                            @endif
                        </div>
                    </div>
                    @endhasanyrole

                    <div class="kpi-item kpi-info">
                        <div class="kpi-icon"><i class="fas fa-medal"></i></div>
                        <div class="kpi-content">
                            <span class="kpi-value">{{ count($beltTimeline ?? []) }}</span>
                            <span class="kpi-label">{{ __('messages.belts_achieved') ?? 'Ceintures obtenues' }}</span>
                        </div>
                    </div>

                    @hasanyrole('admin|cityManager|gymManager')
                    <div class="kpi-item kpi-warning">
                        <div class="kpi-icon"><i class="fas fa-trophy"></i></div>
                        <div class="kpi-content">
                            <span class="kpi-value">{{ number_format(($eventSummary['paid_cents'] ?? 0) / 100, 2, ',', '
                                ') }} TND</span>
                            <span class="kpi-label">{{ __('messages.events_paid') ?? 'Événements payés' }}</span>
                            @if(($eventSummary['total_cents'] ?? 0) > 0)
                            <span class="kpi-comparison">
                                sur {{ number_format(($eventSummary['total_cents'] ?? 0) / 100, 2, ',', ' ') }} TND
                            </span>
                            @endif
                        </div>
                    </div>
                    @endhasanyrole
                </div>
            </div>

            <!-- Trends -->
            <div class="row g-4 mb-4">
                @hasanyrole('admin|cityManager|gymManager')
                <div class="col-lg-6">
                    <div id="expenseSection" class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary"><i class="fas fa-history"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.payment_trend') ?? 'Évolution des paiements' }}
                                </h3>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table sortable-table">
                                <thead>
                                    <tr>
                                        <th data-sort="string">{{ __('messages.period') ?? 'Période' }}</th>
                                        <th data-sort="number">{{ __('messages.paid') ?? 'Payé' }} (TND)</th>
                                        <th data-sort="number">{{ __('messages.due') ?? 'Dû' }} (TND)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($paymentHistory ?? []) as $history)
                                    <tr>
                                        <td>
                                            <span class="month-cell">
                                                <i class="fas fa-calendar-alt"></i> {{ $history['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-modern badge-success">
                                                {{ number_format(($history['paid_cents'] ?? 0) / 100, 2, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge-modern {{ ($history['due_cents'] ?? 0) > 0 ? 'badge-danger' : 'badge-gray' }}">
                                                {{ number_format(($history['due_cents'] ?? 0) / 100, 2, ',', ' ') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="empty-cell">
                                            <div class="empty-state-enhanced">
                                                <i class="fas fa-wallet"></i>
                                                <p>{{ __('messages.no_data') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endhasanyrole

                <div class="{{ $hasFinanceAccess ? 'col-lg-6' : 'col-lg-12' }}">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-success"><i class="fas fa-user-check"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.attendance_trend') ?? 'Évolution des présences'
                                    }}</h3>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table sortable-table">
                                <thead>
                                    <tr>
                                        <th data-sort="string">{{ __('messages.period') ?? 'Période' }}</th>
                                        <th data-sort="number">{{ __('messages.presence_rate') ?? 'Taux de présence' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($attendanceHistory ?? []) as $history)
                                    <tr>
                                        <td>
                                            <span class="month-cell">
                                                <i class="fas fa-calendar-check"></i> {{ $history['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="rate-cell">
                                                <div class="rate-bar">
                                                    <div class="rate-fill {{ ($history['rate'] ?? 0) >= 75 ? 'success' : (($history['rate'] ?? 0) >= 50 ? 'warning' : 'danger') }}"
                                                        style="width: {{ $history['rate'] ?? 0 }}%"></div>
                                                </div>
                                                <span class="rate-value">{{ number_format($history['rate'] ?? 0, 1)
                                                    }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="empty-cell">
                                            <div class="empty-state-enhanced">
                                                <i class="fas fa-chart-line"></i>
                                                <p>{{ __('messages.no_data') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            @hasanyrole('admin|cityManager|gymManager')
            <div class="stats-grid mb-4">
                <div class="stat-card stat-primary" title="Montant total facturé pour la période sélectionnée">
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-content">
                        <span class="stat-value">{{ number_format(($summary['total_cents'] ?? 0) / 100, 2, ',', ' ')
                            }}</span>
                        <span class="stat-label">{{ __('messages.total_revenue') }}</span>
                    </div>
                </div>

                <div class="stat-card stat-success" title="Montant effectivement payé">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-content">
                        <span class="stat-value">{{ number_format(($summary['paid_cents'] ?? 0) / 100, 2, ',', ' ')
                            }}</span>
                        <span class="stat-label">{{ __('messages.total_paid') }}</span>
                    </div>
                </div>

                <div class="stat-card stat-info" title="Nombre de paiements effectués">
                    <div class="stat-icon"><i class="fas fa-credit-card"></i></div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $summary['paid_count'] ?? 0 }}</span>
                        <span class="stat-label">{{ __('messages.paid_count_label') }}</span>
                    </div>
                </div>

                <div class="stat-card stat-danger" title="Nombre de mois en attente de paiement">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $summary['due_count'] ?? 0 }}</span>
                        <span class="stat-label">{{ __('messages.due_count_label') }}</span>
                    </div>
                </div>

                <div class="stat-card stat-warning" title="Montant total payé pour les événements ce mois-ci">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-content">
                        <span class="stat-value">{{ number_format(($eventSummary['paid_cents'] ?? 0) / 100, 2, ',', ' ')
                            }}</span>
                        <span class="stat-label">{{ __('messages.event_payments') ?? 'Paiements événements' }}</span>
                    </div>
                </div>
            </div>
            @endhasanyrole

            <!-- Belt Timeline -->
            @if(isset($beltTimeline) && count($beltTimeline) > 0)
            <div class="modern-card mb-4">
                <div class="card-header-modern">
                    <div class="header-icon belt-header-icon"><i class="fas fa-medal"></i></div>
                    <div>
                        <h3 class="header-title">{{ __('messages.belt_progression') ?? 'Progression des ceintures' }}
                        </h3>
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="belt-timeline">
                        @foreach($beltTimeline as $index => $belt)
                        <div class="timeline-item {{ $index === count($beltTimeline) - 1 ? 'current' : '' }}">
                            <div class="timeline-marker">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>{{ $belt['name'] }}</h4>
                                <span class="timeline-date">{{ $belt['date'] ?? 'N/A' }}</span>
                                @hasanyrole('admin|cityManager|gymManager')
                                @if($belt['price_cents'])
                                <span class="timeline-price">{{ number_format($belt['price_cents'] / 100, 2, ',', ' ')
                                    }} TND</span>
                                @endif
                                @endhasanyrole
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Grid -->
            <div class="row g-4">
                @hasanyrole('admin|cityManager|gymManager')
                <div class="col-lg-6">
                    <div id="incomeSection" class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary"><i class="fas fa-credit-card"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.detailed_payments') }}</h3>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table sortable-table">
                                <thead>
                                    <tr>
                                        <th data-sort="string">{{ __('messages.billing_month') }}</th>
                                        <th data-sort="number">{{ __('messages.amount') }}</th>
                                        <th data-sort="string">{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.method') }}</th>
                                        <th data-sort="string">{{ __('messages.paid_at') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($payments ?? []) as $p)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($p->billing_month)->format('Y-m') }}</td>
                                        <td>
                                            <span class="amount-value">
                                                {{ number_format(($p->amount_cents ?? 0) / 100, 2, ',', ' ') }} TND
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($p->status) }}">
                                                <i
                                                    class="fas fa-{{ $p->status === 'paid' ? 'check-circle' : 'exclamation-circle' }}"></i>
                                                {{ __('messages.' . $p->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $p->method ?? '—' }}</td>
                                        <td>{{ $p->paid_at ?? '—' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="empty-cell">
                                            <div class="empty-state-enhanced">
                                                <i class="fas fa-inbox"></i>
                                                <p>{{ __('messages.no_data') }}</p>
                                                <a href="{{ route('PaymentPackage.stripe') }}" class="btn-action-sm">
                                                    <i class="fas fa-plus"></i> Ajouter un paiement
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endhasanyrole

                <div class="{{ $hasFinanceAccess ? 'col-lg-6' : 'col-lg-12' }}">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon belt-header-icon"><i class="fas fa-medal"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.belts_and_amounts') }}</h3>
                            </div>
                            @hasanyrole('admin|cityManager|gymManager')
                            <div class="header-badge">
                                <span class="badge-modern badge-info">
                                    Total: {{ number_format(($beltsTotal ?? 0) / 100, 2, ',', ' ') }} TND
                                </span>
                            </div>
                            @endhasanyrole
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.belt') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        @hasanyrole('admin|cityManager|gymManager')
                                        <th>{{ __('messages.price_tnd') }}</th>
                                        @endhasanyrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($belts ?? []) as $b)
                                    <tr>
                                        <td>
                                            <span class="belt-name">
                                                <i class="fas fa-award"></i> {{ $b->belt_name }}
                                            </span>
                                        </td>
                                        <td>{{ $b->achieved_at ? $b->achieved_at->format('Y-m-d') : '—' }}</td>
                                        @hasanyrole('admin|cityManager|gymManager')
                                        <td>
                                            <span class="amount-value">
                                                {{ $b->price_cents !== null ? number_format($b->price_cents / 100, 2,
                                                ',', ' ') : '—' }}
                                            </span>
                                        </td>
                                        @endhasanyrole
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ $hasFinanceAccess ? 3 : 2 }}" class="empty-cell">
                                            <div class="empty-state-enhanced">
                                                <i class="fas fa-medal"></i>
                                                <p>{{ __('messages.no_belts') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Section -->
            <div class="row g-4 mt-0">
                <div class="col-lg-6">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-success"><i class="fas fa-user-check"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.attendances_sessions') }}</h3>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.day') }}</th>
                                        <th>{{ __('messages.presents') }}</th>
                                        <th>{{ __('messages.absents') }}</th>
                                        <th>{{ __('messages.excused') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($courseSessions ?? ($attendances ?? [])) as $a)
                                    <tr>
                                        <td>
                                            <div class="month-cell">
                                                <i class="fas fa-calendar"></i>
                                                {{ ($a->day ?? null) ?
                                                \Illuminate\Support\Carbon::parse($a->day)->format('Y-m-d') : '—' }}
                                            </div>
                                        </td>
                                        <td><span class="badge-modern badge-success">{{ (int)($a->presents ?? 0)
                                                }}</span></td>
                                        <td><span class="badge-modern badge-danger">{{ (int)($a->absents ?? 0) }}</span>
                                        </td>
                                        <td><span class="badge-modern badge-warning">{{ (int)($a->excused ?? 0)
                                                }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="empty-cell">
                                            <div class="empty-state-enhanced">
                                                <i class="fas fa-calendar-times"></i>
                                                <p>{{ __('messages.no_attendance') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-info"><i class="fas fa-book"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.enrolled_courses') }}</h3>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            @if(($enrolledCoursesWithPresence ?? collect())->isNotEmpty())
                            <div class="table-container">
                                <table class="modern-table sortable-table">
                                    <thead>
                                        <tr>
                                            <th data-sort="string">{{ __('messages.course') }}</th>
                                            <th data-sort="string">{{ __('messages.status') ?? 'Statut' }}</th>
                                            <th data-sort="number">{{ __('messages.sessions') }}</th>
                                            <th data-sort="number">{{ __('messages.presents') }}</th>
                                            <th data-sort="number">{{ __('messages.absents') }}</th>
                                            <th data-sort="number">{{ __('messages.excused') }}</th>
                                            <th data-sort="number">{{ __('messages.rate_percent') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enrolledCoursesWithPresence as $row)
                                        @php
                                        $total = (int)($row['total_sessions'] ?? 0);
                                        $present = (int)($row['presents'] ?? 0);
                                        $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="course-name">
                                                    <i class="fas fa-book"></i> {{ $row['course_name'] ?? '—' }}
                                                </span>
                                            </td>
                                            <td><span class="badge-modern badge-gray">{{ $row['enroll_status'] ?? '—'
                                                    }}</span></td>
                                            <td><span class="badge-modern badge-gray">{{ $total }}</span></td>
                                            <td><span class="badge-modern badge-success">{{ $present }}</span></td>
                                            <td><span class="badge-modern badge-danger">{{ (int)($row['absents'] ?? 0)
                                                    }}</span></td>
                                            <td><span class="badge-modern badge-warning">{{ (int)($row['excused'] ?? 0)
                                                    }}</span></td>
                                            <td>
                                                <div class="rate-cell">
                                                    <div class="rate-bar">
                                                        <div class="rate-fill {{ $rate >= 75 ? 'success' : ($rate >= 50 ? 'warning' : 'danger') }}"
                                                            style="width: {{ $rate }}%"></div>
                                                    </div>
                                                    <span class="rate-value {{ $rate < 50 ? 'critical' : '' }}">{{
                                                        number_format($rate, 1) }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @elseif(($enrolledCourses ?? collect())->isNotEmpty())
                            <div class="tags-wrap">
                                @foreach($enrolledCourses as $c)
                                <span class="tag tag-course">
                                    <i class="fas fa-book-open"></i> {{ $c->name }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <div class="empty-state-enhanced">
                                <i class="fas fa-book"></i>
                                <p>{{ __('messages.no_enrolled_courses') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance per course -->
            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-warning"><i class="fas fa-chart-bar"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.attendance_per_course_month') }}</h3>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table sortable-table">
                                <thead>
                                    <tr>
                                        <th data-sort="string">{{ __('messages.course') }}</th>
                                        <th data-sort="number">{{ __('messages.sessions') }}</th>
                                        <th data-sort="number">{{ __('messages.presents') }}</th>
                                        <th data-sort="number">{{ __('messages.absents') }}</th>
                                        <th data-sort="number">{{ __('messages.excused') }}</th>
                                        <th data-sort="number">{{ __('messages.rate_percent') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse((($courseAttendanceStatsFilled ?? collect())->isNotEmpty() ?
                                    $courseAttendanceStatsFilled : ($courseAttendanceStats ?? [])) as $row)
                                    @php
                                    $total = (int)($row['total_sessions'] ?? $row->total_sessions ?? 0);
                                    $present = (int)($row['presents'] ?? $row->presents ?? 0);
                                    $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="course-name">
                                                <i class="fas fa-book"></i> {{ $row['course_name'] ?? $row->course_name
                                                }}
                                            </span>
                                        </td>
                                        <td><span class="badge-modern badge-gray">{{ $total }}</span></td>
                                        <td><span class="badge-modern badge-success">{{ $present }}</span></td>
                                        <td><span class="badge-modern badge-danger">{{ (int)($row['absents'] ??
                                                $row->absents ?? 0) }}</span></td>
                                        <td><span class="badge-modern badge-warning">{{ (int)($row['excused'] ??
                                                $row->excused ?? 0) }}</span></td>
                                        <td>
                                            <div class="rate-cell">
                                                <div class="rate-bar">
                                                    <div class="rate-fill {{ $rate >= 75 ? 'success' : ($rate >= 50 ? 'warning' : 'danger') }}"
                                                        style="width: {{ $rate }}%"></div>
                                                </div>
                                                <span class="rate-value {{ $rate < 50 ? 'critical' : '' }}">{{
                                                    number_format($rate, 1) }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="empty-cell">
                                            <div class="empty-state-enhanced">
                                                <i class="fas fa-chart-bar"></i>
                                                <p>{{ __('messages.no_course_attendance_month') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance details per course -->
            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-info"><i class="fas fa-list-check"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.attendance_per_course_month') }} — Détails</h3>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            @if(($enrolledCoursesWithPresence ?? collect())->isNotEmpty())
                            @foreach($enrolledCoursesWithPresence as $row)
                            @php
                            $courseId = (int)($row['course_id'] ?? 0);
                            $details = ($courseAttendanceDetailsByCourse ?? collect())->get($courseId) ?? collect();
                            $total = (int)($row['total_sessions'] ?? 0);
                            $present = (int)($row['presents'] ?? 0);
                            $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;
                            @endphp

                            <details class="mb-3">
                                <summary class="course-name">
                                    <i class="fas fa-book me-2"></i>{{ $row['course_name'] ?? '—' }}
                                    <span class="badge-modern badge-gray ms-2">{{ $total }} {{ __('messages.sessions')
                                        }}</span>
                                    <span class="badge-modern badge-success ms-2">{{ $present }} {{
                                        __('messages.presents') }}</span>
                                    <span class="badge-modern badge-danger ms-2">{{ (int)($row['absents'] ?? 0) }} {{
                                        __('messages.absents') }}</span>
                                    <span class="badge-modern badge-warning ms-2">{{ (int)($row['excused'] ?? 0) }} {{
                                        __('messages.excused') }}</span>
                                    <span class="badge-modern badge-light ms-2">{{ number_format($rate, 1) }}%</span>
                                </summary>

                                @if($details->isNotEmpty())
                                <div class="table-container mt-3">
                                    <table class="modern-table sortable-table">
                                        <thead>
                                            <tr>
                                                <th data-sort="string">{{ __('messages.date') }}</th>
                                                <th data-sort="string">{{ __('messages.status') ?? 'Statut' }}</th>
                                                <th data-sort="string">{{ __('messages.notes') ?? 'Notes' }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($details as $d)
                                            @php
                                            $status = (string)($d->status ?? '');
                                            $badgeClass = $status === 'present'
                                            ? 'badge-success'
                                            : ($status === 'excused'
                                            ? 'badge-warning'
                                            : ($status === 'absent' ? 'badge-danger' : 'badge-gray'));
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span class="month-cell">
                                                        <i class="fas fa-calendar"></i>
                                                        {{ $d->attendance_date ?
                                                        \Illuminate\Support\Carbon::parse($d->attendance_date)->format('Y-m-d')
                                                        : '—' }}
                                                    </span>
                                                </td>
                                                <td><span class="badge-modern {{ $badgeClass }}">{{ $status ?: '—'
                                                        }}</span></td>
                                                <td>{{ $d->notes ?? '—' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="empty-state-enhanced mt-3">
                                    <i class="fas fa-calendar-times"></i>
                                    <p>{{ __('messages.no_attendance') }}</p>
                                </div>
                                @endif
                            </details>
                            @endforeach
                            @else
                            <div class="empty-state-enhanced">
                                <i class="fas fa-book"></i>
                                <p>{{ __('messages.no_enrolled_courses') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @hasanyrole('admin|cityManager|gymManager')
            <div class="row g-4 mt-0">
                <div class="col-lg-6">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-success"><i class="fas fa-check-circle"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.paid_months') }}</h3>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            @php $paid = $userPaidMonths ?? []; @endphp
                            @if(!empty($paid))
                            <div class="tags-wrap">
                                @foreach($paid as $m)
                                <span class="tag tag-success">
                                    <i class="fas fa-check"></i> {{ $m }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <div class="empty-state-enhanced">
                                <i class="fas fa-credit-card"></i>
                                <p>{{ __('messages.no_paid_months') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-danger"><i class="fas fa-times-circle"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.unpaid_months') }}</h3>
                            </div>
                            @if(!empty($userUnpaidMonths))
                            <div class="header-badge">
                                <span class="badge-modern badge-danger">
                                    Total dû: {{ number_format(($unpaidTotalCents ?? 0) / 100, 2, ',', ' ') }} TND
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="card-body-modern">
                            @php $unpaid = $userUnpaidMonths ?? []; @endphp
                            @if(!empty($unpaid))
                            <div class="tags-wrap">
                                @foreach($unpaid as $m)
                                <span class="tag tag-danger">
                                    <i class="fas fa-times"></i> {{ $m }}
                                </span>
                                @endforeach
                            </div>
                            <a href="{{ route('PaymentPackage.stripe') }}" class="btn-action mt-3">
                                <i class="fas fa-money-bill-wave"></i> Procéder au paiement
                            </a>
                            @else
                            <div class="empty-state-enhanced success">
                                <i class="fas fa-check-double"></i>
                                <p>{{ __('messages.no_unpaid_months') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole

            <!-- Events -->
            <div id="eventSection" class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-warning"><i class="fas fa-trophy"></i></div>
                            <div>
                                <h3 class="header-title">{{ __('messages.events') ?? 'Événements' }}</h3>
                            </div>
                            <div class="header-badge">
                                <span class="badge-modern badge-warning">{{ count($eventRegistrations ?? []) }}
                                    Inscriptions</span>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table sortable-table">
                                <thead>
                                    <tr>
                                        <th data-sort="string">{{ __('messages.event_name') ?? 'Nom de l\'événement' }}
                                        </th>
                                        <th data-sort="string">{{ __('messages.date') }}</th>
                                        <th data-sort="number">{{ __('messages.amount') }}</th>
                                        <th data-sort="string">{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.location') ?? 'Lieu' }}</th>
                                        <th>{{ __('messages.notes') ?? 'Notes' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($eventRegistrations ?? []) as $reg)
                                    @php
                                    $regEvent = $reg->event ?? null;
                                    $registrationFee = (int) ($regEvent->registration_fee ?? 0);
                                    $paidAmount = (int) ($reg->paid_amount ?? 0);
                                    $isPaid = $registrationFee > 0 && $paidAmount >= $registrationFee;
                                    $isPartial = $paidAmount > 0 && $paidAmount < $registrationFee; @endphp <tr>
                                        <td>
                                            @if($regEvent)
                                            <a href="{{ route('events.show', $regEvent->id) }}" class="course-name">
                                                <i class="fas fa-calendar-star text-warning me-2"></i> {{
                                                $regEvent->name }}
                                            </a>
                                            @else
                                            <span class="text-muted">
                                                <i class="fas fa-calendar-times me-2"></i>
                                                {{ __('messages.event_deleted') ?? 'Événement supprimé' }}
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ $regEvent?->start_at ? $regEvent->start_at->format('Y-m-d') : '—' }}</td>
                                        <td>
                                            <span class="amount-value">{{ number_format($paidAmount / 100, 2, ',', ' ')
                                                }} TND</span>
                                            @if($regEvent)
                                            <small class="text-muted d-block">
                                                sur {{ number_format($registrationFee / 100, 2, ',', ' ') }} TND
                                            </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="status-badge {{ $isPaid ? 'status-paid' : ($isPartial ? 'status-partial' : 'status-due') }}">
                                                <i
                                                    class="fas fa-{{ $isPaid ? 'check-circle' : ($isPartial ? 'adjust' : 'exclamation-circle') }}"></i>
                                                {{ $isPaid ? (__('messages.paid') ?? 'Payé') : ($isPartial ?
                                                (__('messages.partial') ?? 'Partiel') : (__('messages.unpaid') ?? 'Non
                                                payé')) }}
                                            </span>
                                        </td>
                                        <td><i class="fas fa-map-marker-alt text-muted me-1"></i> {{
                                            $regEvent?->location ?? '—' }}</td>
                                        <td><small class="text-muted">{{ $reg->notes ?? '—' }}</small></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="empty-cell">
                                                <div class="empty-state-enhanced">
                                                    <i class="fas fa-calendar-times"></i>
                                                    <p>{{ __('messages.no_events_registered') ?? 'Aucune inscription à
                                                        un événement pour cette période.' }}</p>
                                                    <a href="{{ route('events.index') }}" class="btn-action-sm">
                                                        <i class="fas fa-plus"></i> Voir les événements
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating PDF Bar -->
            <div id="pdfFloatingBar" class="pdf-floating-bar">
                <div class="pdf-bar-content">
                    <div class="pdf-bar-info">
                        <i class="fas fa-file-pdf"></i>
                        <span id="pdfStatusText">Rapport prêt pour export PDF</span>
                    </div>
                    <div class="pdf-bar-actions">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" id="btnGeneratePdfFloat" class="btn-pdf-generate-float" target="_blank">
                            <i class="fas fa-file-pdf"></i> Générer en PDF
                        </a>
                    </div>
                </div>
                <div id="pdfProgressBar" class="pdf-progress-bar">
                    <div id="pdfProgressFill" class="pdf-progress-fill"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    html {
        scroll-behavior: smooth;
    }

    #summarySection,
    #incomeSection,
    #expenseSection,
    #eventSection {
        scroll-margin-top: 80px;
    }

    /* Executive Summary */
    .executive-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: #fff;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .summary-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .summary-kpis {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .kpi-item {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.2s;
    }

    .kpi-item:hover {
        transform: translateY(-2px);
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
    }

    .kpi-icon i {
        font-size: 1.25rem;
    }

    .kpi-content {
        display: flex;
        flex-direction: column;
    }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .kpi-label {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .kpi-comparison {
        font-size: 0.7rem;
        margin-top: 0.25rem;
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.2);
    }

    .kpi-comparison.positive {
        color: #86efac;
    }

    .kpi-comparison.negative {
        color: #fca5a5;
    }

    .kpi-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.7rem;
        margin-top: 0.25rem;
        padding: 0.25rem 0.5rem;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        transition: background 0.2s;
    }

    .kpi-action:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #fff;
    }

    /* Quick Filters */
    .quick-filters {
        display: flex;
        gap: 0.5rem;
    }

    .search-group {
        min-width: 240px;
    }

    .btn-preset {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.2);
        border-radius: 8px;
        color: var(--primary-color);
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-preset:hover,
    .btn-preset.active {
        background: var(--primary-color);
        color: #fff;
        border-color: var(--primary-color);
    }

    /* Belt Timeline */
    .belt-timeline {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1rem 0;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        flex: 0 0 auto;
        min-width: 180px;
        transition: transform 0.2s;
    }

    .timeline-item:hover {
        transform: translateY(-2px);
    }

    .timeline-item.current {
        background: linear-gradient(135deg, #86efac 0%, #4ade80 100%);
        box-shadow: 0 4px 12px rgba(74, 222, 128, 0.3);
    }

    .timeline-marker {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline-marker i {
        color: #92400e;
        font-size: 1rem;
    }

    .timeline-item.current .timeline-marker i {
        color: #166534;
    }

    .timeline-content h4 {
        margin: 0;
        font-size: 0.875rem;
        font-weight: 600;
        color: #78350f;
    }

    .timeline-item.current .timeline-content h4 {
        color: #166534;
    }

    .timeline-date {
        font-size: 0.7rem;
        color: #92400e;
        opacity: 0.8;
    }

    .timeline-price {
        font-size: 0.7rem;
        font-weight: 600;
        color: #78350f;
        display: block;
    }

    /* Empty States */
    .empty-state-enhanced {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        text-align: center;
        color: var(--text-muted);
    }

    .empty-state-enhanced i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    .empty-state-enhanced p {
        margin: 0 0 0.75rem;
        font-weight: 500;
    }

    .empty-state-enhanced.success {
        color: var(--success-color);
    }

    .btn-action-sm {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        background: var(--primary-color);
        color: #fff;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-action-sm:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: #fff;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 101, 101, 0.4);
        color: #fff;
    }

    /* Rate Critical */
    .rate-value.critical {
        color: #dc2626;
        font-weight: 700;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    /* Existing styles */
    .user-id-badge {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--primary-color);
        background: rgba(102, 126, 234, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        margin-left: 0.5rem;
    }

    .filter-form {
        display: flex;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-group .form-control-modern {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-primary .stat-icon {
        background: rgba(102, 126, 234, 0.1);
        color: var(--primary-color);
    }

    .stat-success .stat-icon {
        background: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }

    .stat-info .stat-icon {
        background: rgba(66, 153, 225, 0.1);
        color: var(--info-color);
    }

    .stat-danger .stat-icon {
        background: rgba(245, 101, 101, 0.1);
        color: var(--danger-color);
    }

    .stat-warning .stat-icon {
        background: rgba(237, 137, 54, 0.1);
        color: var(--warning-color);
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    .modern-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .card-header-modern {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
    }

    .header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-icon-primary {
        background: rgba(102, 126, 234, 0.1);
        color: var(--primary-color);
    }

    .header-icon-success {
        background: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }

    .header-icon-info {
        background: rgba(66, 153, 225, 0.1);
        color: var(--info-color);
    }

    .header-icon-warning {
        background: rgba(237, 137, 54, 0.1);
        color: var(--warning-color);
    }

    .header-icon-danger {
        background: rgba(245, 101, 101, 0.1);
        color: var(--danger-color);
    }

    .belt-header-icon {
        background: rgba(237, 137, 54, 0.1);
        color: var(--warning-color);
    }

    .header-title {
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .header-badge {
        margin-left: auto;
    }

    .card-body-modern {
        padding: 1rem 1.25rem;
    }

    .table-container {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead {
        background: var(--light-bg);
    }

    .modern-table th {
        padding: 0.875rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.025em;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
    }

    .modern-table th:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    .modern-table td {
        padding: 1rem;
        font-size: 0.875rem;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: background 0.15s;
    }

    .modern-table tbody tr:hover {
        background: var(--light-bg);
    }

    .empty-cell {
        text-align: center;
        padding: 2rem !important;
    }

    .month-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .amount-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
    }

    .status-paid {
        background: rgba(72, 187, 120, 0.1);
        color: #059669;
    }

    .status-due {
        background: rgba(245, 101, 101, 0.1);
        color: #dc2626;
    }

    .status-partial {
        background: rgba(237, 137, 54, 0.12);
        color: #d97706;
    }

    .belt-name,
    .course-name {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .tags-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        font-size: 0.8125rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .tag-success {
        background: rgba(72, 187, 120, 0.1);
        color: #059669;
    }

    .tag-danger {
        background: rgba(245, 101, 101, 0.1);
        color: #dc2626;
    }

    .tag-course {
        background: rgba(66, 153, 225, 0.1);
        color: #2563eb;
    }

    .badge-modern {
        display: inline-flex;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 6px;
    }

    .badge-success {
        background: rgba(72, 187, 120, 0.1);
        color: #059669;
    }

    .badge-danger {
        background: rgba(245, 101, 101, 0.1);
        color: #dc2626;
    }

    .badge-warning {
        background: rgba(237, 137, 54, 0.1);
        color: #d97706;
    }

    .badge-info {
        background: rgba(66, 153, 225, 0.1);
        color: #2563eb;
    }

    .badge-gray {
        background: rgba(113, 128, 150, 0.1);
        color: #718096;
    }

    .badge-light {
        background: rgba(148, 163, 184, 0.12);
        color: #475569;
    }

    .rate-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .rate-bar {
        width: 60px;
        height: 8px;
        background: var(--border-color);
        border-radius: 9999px;
        overflow: hidden;
    }

    .rate-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.5s ease;
    }

    .rate-fill.success {
        background: var(--success-color);
    }

    .rate-fill.warning {
        background: var(--warning-color);
    }

    .rate-fill.danger {
        background: var(--danger-color);
    }

    .rate-value {
        font-size: 0.8125rem;
        font-weight: 600;
        min-width: 45px;
    }

    /* Print */
    @media print {

        nav,
        aside,
        .main-footer,
        .main-header,
        .btn-ghost,
        .btn-primary-modern,
        .filter-form,
        .quick-filters,
        .btn-action,
        .btn-action-sm,
        .kpi-action,
        .header-actions {
            display: none !important;
        }

        body {
            background: #fff !important;
            color: #000 !important;
            font-size: 12pt;
        }

        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .executive-summary {
            background: #f8fafc !important;
            color: #000 !important;
            border: 2px solid #e2e8f0 !important;
            box-shadow: none !important;
            padding: 1.5rem !important;
            margin-bottom: 2rem !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .summary-header {
            color: #000 !important;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }

        .kpi-item {
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            color: #000 !important;
            break-inside: avoid;
        }

        .kpi-value,
        .kpi-label {
            color: #000 !important;
        }

        .modern-card {
            border: 1px solid #e2e8f0 !important;
            box-shadow: none !important;
            margin-bottom: 1.5rem !important;
            break-inside: avoid;
        }

        .card-header-modern {
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .header-title {
            color: #000 !important;
            font-size: 14pt !important;
        }

        .modern-table {
            border: 1px solid #e2e8f0 !important;
        }

        .modern-table th {
            background: #f8fafc !important;
            color: #000 !important;
            border-bottom: 2px solid #e2e8f0 !important;
        }

        .modern-table td {
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .badge-modern,
        .tag,
        .status-badge {
            border: 1px solid #e2e8f0 !important;
            background: transparent !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .rate-bar {
            border: 1px solid #e2e8f0 !important;
            background: #fff !important;
        }

        .rate-fill {
            background: #667eea !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .timeline-item {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
        }

        .timeline-content h4,
        .timeline-date {
            color: #000 !important;
        }

        .row {
            display: block !important;
        }

        .col-lg-6,
        .col-12 {
            width: 100% !important;
            margin-bottom: 2rem !important;
        }

        .print-header {
            display: block !important;
            margin-bottom: 2rem;
            border-bottom: 2px solid #000;
            padding-bottom: 1rem;
        }
    }

    .print-header {
        display: none;
    }

    /* PDF Buttons */
    .btn-pdf-generate {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #e11d48, #be123c);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(225, 29, 72, 0.35);
        position: relative;
        overflow: hidden;
    }

    .btn-pdf-generate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-pdf-generate:hover::before {
        left: 100%;
    }

    .btn-pdf-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(225, 29, 72, 0.45);
    }

    .btn-pdf-generate:active {
        transform: translateY(0);
    }

    .btn-pdf-generate.generating {
        background: linear-gradient(135deg, #a3a3a3, #737373);
        pointer-events: none;
        box-shadow: none;
    }

    .btn-pdf-generate.generating i {
        animation: spin 1s linear infinite;
    }

    .btn-pdf-download {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #059669, #047857);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
        animation: fadeInScale 0.4s ease-out;
    }

    .btn-pdf-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.45);
        color: #fff;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* PDF Floating Bar */
    .pdf-floating-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1050;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.875rem 1.5rem;
        transform: translateY(0);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.3);
    }

    .pdf-bar-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .pdf-bar-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .pdf-bar-info i {
        color: #f43f5e;
        font-size: 1.25rem;
    }

    .pdf-bar-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-pdf-generate-float {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #e11d48, #be123c);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(225, 29, 72, 0.4);
    }

    .btn-pdf-generate-float:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(225, 29, 72, 0.5);
    }

    .btn-pdf-generate-float.generating {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        pointer-events: none;
    }

    .btn-pdf-generate-float.generating i {
        animation: spin 1s linear infinite;
    }

    .btn-pdf-download-float {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #059669, #047857);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.4);
        animation: fadeInScale 0.4s ease-out;
    }

    .btn-pdf-download-float:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.5);
        color: #fff;
    }

    .pdf-progress-bar {
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        margin-top: 0.5rem;
        overflow: hidden;
        display: none;
    }

    .pdf-progress-fill {
        height: 100%;
        width: 0%;
        border-radius: 2px;
        background: linear-gradient(90deg, #e11d48, #f43f5e, #fb7185);
        transition: width 0.3s ease;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    /* Dark Mode */
    .dark-mode .executive-summary {
        background: linear-gradient(135deg, #4338ca 0%, #5b21b6 100%);
    }

    .dark-mode .modern-card,
    .dark-mode .stat-card {
        background: #1e293b;
        border-color: #334155;
    }

    .dark-mode .modern-table thead {
        background: #0f172a;
    }

    .dark-mode .modern-table td,
    .dark-mode .modern-table th {
        border-color: #334155;
        color: #e2e8f0;
    }

    .dark-mode .stat-value,
    .dark-mode .header-title,
    .dark-mode .amount-value {
        color: #f1f5f9;
    }

    .dark-mode .stat-label,
    .dark-mode .month-cell {
        color: #94a3b8;
    }

    .dark-mode .btn-preset {
        background: rgba(99, 102, 241, 0.2);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .dark-mode .timeline-item {
        background: linear-gradient(135deg, #854d0e 0%, #a16207 100%);
    }

    .dark-mode .timeline-content h4,
    .dark-mode .timeline-date,
    .dark-mode .timeline-price {
        color: #fef3c7;
    }

    .dark-mode .empty-state-enhanced {
        color: #64748b;
    }

    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            gap: 0.75rem;
            width: 100%;
        }

        .quick-filters {
            width: 100%;
            justify-content: center;
        }

        .filter-form,
        .filter-group {
            width: 100%;
        }

        .search-group {
            width: 100%;
            min-width: 100%;
        }

        .summary-kpis {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .belt-timeline {
            justify-content: center;
        }

        .pdf-floating-bar {
            padding: 0.75rem 1rem;
        }

        .pdf-bar-content {
            flex-direction: column;
            gap: 0.5rem;
        }

        .pdf-bar-info {
            font-size: 0.75rem;
        }

        .btn-pdf-generate-float,
        .btn-pdf-download-float {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }
    }

    @media print {

        .pdf-floating-bar,
        .btn-pdf-generate,
        .btn-pdf-download {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sortable tables
        document.querySelectorAll('.sortable-table th[data-sort]').forEach(th => {
            th.addEventListener('click', function () {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => !row.classList.contains('search-empty-msg'));
                const idx = Array.from(this.parentNode.children).indexOf(this);
                const type = this.dataset.sort;
                const asc = !this.classList.contains('sort-asc');

                table.querySelectorAll('th').forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
                this.classList.add(asc ? 'sort-asc' : 'sort-desc');

                rows.sort((a, b) => {
                    let av = a.children[idx]?.textContent.trim() || '';
                    let bv = b.children[idx]?.textContent.trim() || '';

                    if (type === 'number') {
                        av = parseFloat(av.replace(/[^\d.-]/g, '')) || 0;
                        bv = parseFloat(bv.replace(/[^\d.-]/g, '')) || 0;
                    } else {
                        av = av.toLowerCase();
                        bv = bv.toLowerCase();
                    }

                    if (av === bv) return 0;
                    return asc ? (av > bv ? 1 : -1) : (av < bv ? 1 : -1);
                });

                rows.forEach(r => tbody.appendChild(r));
            });
        });

        // Search
        const searchInput = document.getElementById('reportTableSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase().trim();
                const tables = document.querySelectorAll('.modern-table');

                tables.forEach(table => {
                    const tbody = table.querySelector('tbody');
                    if (!tbody) return;

                    const rows = tbody.querySelectorAll('tr:not(.search-empty-msg)');
                    let hasVisibleRows = false;

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const isVisible = text.includes(searchTerm);
                        row.style.display = isVisible ? '' : 'none';
                        if (isVisible) hasVisibleRows = true;
                    });

                    let emptyMsg = tbody.querySelector('.search-empty-msg');
                    if (!hasVisibleRows && searchTerm !== '') {
                        if (!emptyMsg) {
                            emptyMsg = document.createElement('tr');
                            emptyMsg.className = 'search-empty-msg';
                            const colCount = table.querySelectorAll('thead th').length || 1;
                            emptyMsg.innerHTML = `<td colspan="${colCount}" class="text-center py-4 text-muted">
                            <i class="fas fa-search mb-2 d-block fs-4"></i>
                            Aucun résultat pour "${this.value}"
                        </td>`;
                            tbody.appendChild(emptyMsg);
                        }
                    } else if (emptyMsg) {
                        emptyMsg.remove();
                    }
                });
            });

            // Shortcut F
            document.addEventListener('keydown', function (e) {
                const isInput = ['INPUT', 'TEXTAREA', 'SELECT'].includes((document.activeElement?.tagName || '').toUpperCase());
                if (!isInput && e.key.toLowerCase() === 'f' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        }
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.2/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

@php
$pdfKpiRevenu = number_format(($summary['total_cents'] ?? 0)/100, 2, ',', ' ') . ' TND';
$pdfKpiPresence = ($userPresenceRate ?? 0) . '%';
$pdfKpiDu = number_format(($unpaidTotalCents ?? 0)/100, 2, ',', ' ') . ' TND';
$pdfKpiCeintures = (string) count($beltTimeline ?? []);
$pdfKpiEvents = number_format(($eventSummary['paid_cents'] ?? 0)/100, 2, ',', ' ') . ' TND';

$pdfStatsRevenu = number_format(($summary['total_cents'] ?? 0)/100, 2, ',', ' ') . ' TND';
$pdfStatsPaid = number_format(($summary['paid_cents'] ?? 0)/100, 2, ',', ' ') . ' TND';
$pdfStatsPaidCount = (string)($summary['paid_count'] ?? 0);
$pdfStatsDueCount = (string)($summary['due_count'] ?? 0);
$pdfStatsEvtPaid = number_format(($eventSummary['paid_cents'] ?? 0)/100, 2, ',', ' ') . ' TND';

$pdfPaymentsData = ($payments ?? collect())->map(function($p) {
return [
'billing_month' => \Carbon\Carbon::parse($p->billing_month)->format('Y-m'),
'amount' => number_format(($p->amount_cents ?? 0)/100, 2, ',', ' ') . ' TND',
'status' => $p->status,
'method' => $p->method ?? '—',
'paid_at' => $p->paid_at ?? '—',
];
})->values();

$pdfBeltsData = ($belts ?? collect())->map(function($b) {
return [
'name' => $b->belt_name,
'date' => $b->achieved_at ? $b->achieved_at->format('Y-m-d') : '—',
'price' => $b->price_cents !== null ? number_format($b->price_cents/100, 2, ',', ' ') . ' TND' : '—',
];
})->values();

$pdfSessionsData = ($courseSessions ?? collect())->map(function($a) {
return [
'day' => ($a->day ?? null) ? \Illuminate\Support\Carbon::parse($a->day)->format('Y-m-d') : '—',
'presents' => (string)(int)($a->presents ?? 0),
'absents' => (string)(int)($a->absents ?? 0),
'excused' => (string)(int)($a->excused ?? 0),
];
})->values();

$pdfEventsData = ($eventRegistrations ?? collect())->map(function($reg) {
$evt = $reg->event;
$fee = (int)($evt->registration_fee ?? 0);
$paid = (int)($reg->paid_amount ?? 0);
$isPaid = $fee > 0 && $paid >= $fee;
return [
'name' => $evt ? $evt->name : 'Événement supprimé',
'date' => $evt && $evt->start_at ? $evt->start_at->format('Y-m-d') : '—',
'amount' => number_format($paid/100, 2, ',', ' ') . ' TND',
'status' => $isPaid ? 'Payé' : ($paid > 0 ? 'Partiel' : 'Non payé'),
'location' => $evt ? ($evt->location ?? '—') : '—',
];
})->values();

$pdfUserSlug = \Illuminate\Support\Str::slug($user->name, '_');
@endphp

<script>
    let generatedPdfBlob = null;

    function generateReportPDF() {
        const { jsPDF } = window.jspdf;
        if (!jsPDF) {
            alert('Erreur: Bibliothèque PDF non chargée. Veuillez rafraîchir la page.');
            return;
        }

        setGeneratingState(true);
        updateProgress(5);

        setTimeout(() => {
            try {
                const doc = new jsPDF('p', 'mm', 'a4');
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                const margin = 15;
                const contentWidth = pageWidth - margin * 2;
                let y = margin;

                const colors = {
                    primary: [102, 126, 234],
                    success: [5, 150, 105],
                    danger: [220, 38, 38],
                    warning: [217, 119, 6],
                    dark: [15, 23, 42],
                    gray: [100, 116, 139],
                    lightBg: [248, 250, 252],
                    border: [226, 232, 240]
                };

                updateProgress(10);

                doc.setFillColor(...colors.primary);
                doc.roundedRect(margin, y, contentWidth, 38, 4, 4, 'F');
                doc.setFillColor(118, 75, 162);
                doc.roundedRect(margin + contentWidth * 0.6, y, contentWidth * 0.4, 38, 0, 4, 'F');

                doc.setTextColor(255, 255, 255);
                doc.setFontSize(20);
                doc.setFont('helvetica', 'bold');
                doc.text('RAPPORT UTILISATEUR', margin + 8, y + 14);

                doc.setFontSize(11);
                doc.setFont('helvetica', 'normal');
                doc.text(String(@json($user -> name)), margin + 8, y + 23);

                doc.setFontSize(9);
                doc.text('ID: #' + String(@json((string)$user -> id)) + ' | Période: ' + String(@json($month)), margin + 8, y + 31);

                const dateStr = new Date().toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                doc.text(dateStr, pageWidth - margin - 8, y + 14, { align: 'right' });

                y += 46;
                updateProgress(20);

                doc.setFontSize(13);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(...colors.dark);
                doc.text('● En un coup d\'œil', margin, y);
                y += 6;

                const kpis = [];
                @hasanyrole('admin|cityManager|gymManager')
                kpis.push({ label: 'Revenu total', value: String(@json($pdfKpiRevenu)), color: colors.primary });
                @endhasanyrole
                kpis.push({ label: 'Taux de présence', value: String(@json($pdfKpiPresence)), color: colors.success });
                @hasanyrole('admin|cityManager|gymManager')
                kpis.push({ label: 'Montant dû', value: String(@json($pdfKpiDu)), color: colors.danger });
                @endhasanyrole
                kpis.push({ label: 'Ceintures', value: String(@json($pdfKpiCeintures)), color: colors.warning });
                @hasanyrole('admin|cityManager|gymManager')
                kpis.push({ label: 'Évén. payés', value: String(@json($pdfKpiEvents)), color: colors.warning });
                @endhasanyrole

                const kpiWidth = (contentWidth - (kpis.length - 1) * 3) / Math.max(kpis.length, 1);
                kpis.forEach((kpi, i) => {
                    const kx = margin + i * (kpiWidth + 3);
                    doc.setFillColor(...kpi.color);
                    doc.roundedRect(kx, y, kpiWidth, 18, 3, 3, 'F');
                    doc.setTextColor(255, 255, 255);
                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'bold');
                    doc.text(kpi.value, kx + kpiWidth / 2, y + 9, { align: 'center' });
                    doc.setFontSize(7);
                    doc.setFont('helvetica', 'normal');
                    doc.text(kpi.label, kx + kpiWidth / 2, y + 15, { align: 'center' });
                });

                y += 26;
                updateProgress(30);

                function sectionTitle(title) {
                    if (y > pageHeight - 40) {
                        doc.addPage();
                        y = margin;
                    }

                    doc.setFillColor(...colors.lightBg);
                    doc.roundedRect(margin, y, contentWidth, 10, 2, 2, 'F');
                    doc.setDrawColor(...colors.border);
                    doc.roundedRect(margin, y, contentWidth, 10, 2, 2, 'S');
                    doc.setFillColor(...colors.primary);
                    doc.roundedRect(margin, y, 3, 10, 1.5, 0, 'F');

                    doc.setTextColor(...colors.dark);
                    doc.setFontSize(11);
                    doc.setFont('helvetica', 'bold');
                    doc.text(title, margin + 7, y + 7);
                    y += 14;
                }

                function addTable(headers, rows, options = {}) {
                    if (rows.length === 0) {
                        doc.setTextColor(...colors.gray);
                        doc.setFontSize(9);
                        doc.setFont('helvetica', 'italic');
                        doc.text('Aucune donnée disponible.', margin + 5, y + 4);
                        y += 10;
                        return;
                    }

                    doc.autoTable({
                        startY: y,
                        head: [headers],
                        body: rows,
                        margin: { left: margin, right: margin },
                        styles: {
                            fontSize: 8,
                            cellPadding: 3,
                            lineColor: [226, 232, 240],
                            lineWidth: 0.3,
                            textColor: [30, 41, 59]
                        },
                        headStyles: {
                            fillColor: [248, 250, 252],
                            textColor: [71, 85, 105],
                            fontStyle: 'bold',
                            fontSize: 7.5
                        },
                        alternateRowStyles: { fillColor: [252, 252, 253] },
                        ...options
                    });

                    y = doc.lastAutoTable.finalY + 8;
                }

                @hasanyrole('admin|cityManager|gymManager')
                sectionTitle('Résumé financier');
                const statsData = [
                    ['Revenu total', String(@json($pdfStatsRevenu))],
                    ['Total payé', String(@json($pdfStatsPaid))],
                    ['Paiements effectués', String(@json($pdfStatsPaidCount))],
                    ['Mois en attente', String(@json($pdfStatsDueCount))],
                    ['Paiements événements', String(@json($pdfStatsEvtPaid))]
                ];
                addTable(['Indicateur', 'Valeur'], statsData);
                @endhasanyrole

                updateProgress(40);

                @hasanyrole('admin|cityManager|gymManager')
                sectionTitle('Évolution des paiements (6 derniers mois)');
                const paymentHistoryData = @json($paymentHistory ?? []);
                const phRows = paymentHistoryData.map(h => [
                    String(h.label),
                    String((h.paid_cents / 100).toFixed(2).replace('.', ',') + ' TND'),
                    String((h.due_cents / 100).toFixed(2).replace('.', ',') + ' TND')
                ]);
                addTable(['Période', 'Payé (TND)', 'Dû (TND)'], phRows);
                @endhasanyrole

                updateProgress(50);

                sectionTitle('Évolution des présences (6 derniers mois)');
                const attendanceHistoryData = @json($attendanceHistory ?? []);
                const ahRows = attendanceHistoryData.map(h => [
                    String(h.label),
                    String(Number(h.rate || 0).toFixed(1) + '%')
                ]);
                addTable(['Période', 'Taux de présence'], ahRows);

                updateProgress(55);

                const beltData = @json($beltTimeline ?? []);
                if (beltData.length > 0) {
                    sectionTitle('Progression des ceintures');
                    const beltRows = beltData.map(b => {
                        const row = [String(b.name), String(b.date || '---')];
                        @hasanyrole('admin|cityManager|gymManager')
                        row.push(String(b.price_cents ? (b.price_cents / 100).toFixed(2).replace('.', ',') + ' TND' : '---'));
                        @endhasanyrole
                        return row;
                    });
                    const beltHeaders = ['Ceinture', 'Date'];
                    @hasanyrole('admin|cityManager|gymManager')
                    beltHeaders.push('Prix (TND)');
                    @endhasanyrole
                    addTable(beltHeaders, beltRows);
                }

                updateProgress(60);

                @hasanyrole('admin|cityManager|gymManager')
                sectionTitle('Paiements détaillés');
                const paymentsData = @json($pdfPaymentsData);
                const payRows = paymentsData.map(p => [
                    String(p.billing_month),
                    String(p.amount),
                    String(p.status === 'paid' ? 'Payé' : 'Dû'),
                    String(p.method),
                    String(p.paid_at)
                ]);
                addTable(['Mois', 'Montant', 'Statut', 'Méthode', 'Payé le'], payRows);
                @endhasanyrole

                updateProgress(70);

                @hasanyrole('admin|cityManager|gymManager')
                sectionTitle('Ceintures et montants');
                const beltsDetailData = @json($pdfBeltsData);
                const bdRows = beltsDetailData.map(b => [String(b.name), String(b.date), String(b.price)]);
                addTable(['Ceinture', 'Date', 'Prix (TND)'], bdRows);
                @endhasanyrole

                updateProgress(75);

                sectionTitle('Présences par jour');
                const sessionsData = @json($pdfSessionsData);
                const sessRows = sessionsData.map(s => [String(s.day), String(s.presents), String(s.absents), String(s.excused)]);
                addTable(['Jour', 'Présents', 'Absents', 'Excusés'], sessRows);

                updateProgress(80);

                sectionTitle('Cours inscrits - Statistiques');
                const coursesData = @json(($enrolledCoursesWithPresence ?? collect()) -> values());
                const courseRows = coursesData.map(c => {
                    const total = parseInt(c.total_sessions) || 0;
                    const present = parseInt(c.presents) || 0;
                    const rate = total > 0 ? ((present / total) * 100).toFixed(1) + '%' : '0%';
                    return [
                        String(c.course_name),
                        String(total),
                        String(present),
                        String(parseInt(c.absents) || 0),
                        String(parseInt(c.excused) || 0),
                        String(rate)
                    ];
                });
                addTable(['Cours', 'Sessions', 'Présents', 'Absents', 'Excusés', 'Taux'], courseRows);

                updateProgress(85);

                sectionTitle('Événements');
                const eventsData = @json($pdfEventsData);
                const eventRows = eventsData.map(e => [
                    String(e.name),
                    String(e.date),
                    String(e.amount),
                    String(e.status),
                    String(e.location)
                ]);
                addTable(['Événement', 'Date', 'Montant', 'Statut', 'Lieu'], eventRows);

                updateProgress(90);

                @hasanyrole('admin|cityManager|gymManager')
                sectionTitle('Mois payés / Non payés');
                const paidMonths = @json($userPaidMonths ?? []);
                const unpaidMonths = @json($userUnpaidMonths ?? []);
                if (paidMonths.length > 0 || unpaidMonths.length > 0) {
                    const maxLen = Math.max(paidMonths.length, unpaidMonths.length);
                    const monthRows = [];
                    for (let i = 0; i < maxLen; i++) {
                        monthRows.push([
                            String(paidMonths[i] ? paidMonths[i] : ''),
                            String(unpaidMonths[i] ? unpaidMonths[i] : '')
                        ]);
                    }
                    addTable(['Mois payés', 'Mois non payés'], monthRows, {
                        columnStyles: {
                            0: { textColor: [5, 150, 105] },
                            1: { textColor: [220, 38, 38] }
                        }
                    });
                }
                @endhasanyrole

                updateProgress(95);

                const totalPages = doc.internal.getNumberOfPages();
                const footerUserName = String(@json($user -> name));
                const footerUserId = String(@json((string)$user -> id));

                for (let i = 1; i <= totalPages; i++) {
                    doc.setPage(i);
                    doc.setDrawColor(...colors.border);
                    doc.line(margin, pageHeight - 12, pageWidth - margin, pageHeight - 12);
                    doc.setFontSize(7);
                    doc.setTextColor(...colors.gray);
                    doc.setFont('helvetica', 'normal');
                    doc.text('Rapport généré le ' + dateStr + ' - ' + footerUserName + ' (#' + footerUserId + ')', margin, pageHeight - 7);
                    doc.text('Page ' + i + ' / ' + totalPages, pageWidth - margin, pageHeight - 7, { align: 'right' });
                }

                updateProgress(100);

                const userName = String(@json($pdfUserSlug));
                const dateForFile = new Date().toISOString().slice(0, 10).replace(/-/g, '');
                const filename = 'rapport_' + userName + '_' + dateForFile + '.pdf';

                generatedPdfBlob = doc.output('blob');
                const blobUrl = URL.createObjectURL(generatedPdfBlob);

                document.querySelectorAll('#btnDownloadPdf, #btnDownloadPdfFloat').forEach(btn => {
                    btn.href = blobUrl;
                    btn.download = filename;
                    btn.style.display = 'inline-flex';
                });

                const statusEl = document.getElementById('pdfStatusText');
                if (statusEl) statusEl.textContent = '✓ PDF généré avec succès — ' + filename;

                setGeneratingState(false);

            } catch (error) {
                console.error('PDF Generation Error:', error);
                alert('Erreur lors de la génération du PDF: ' + error.message);
                setGeneratingState(false);
                updateProgress(0);
            }
        }, 100);
    }

    function setGeneratingState(isGenerating) {
        document.querySelectorAll('#btnGeneratePdf, #btnGeneratePdfFloat').forEach(btn => {
            if (isGenerating) {
                btn.classList.add('generating');
                const iconEl = btn.querySelector('i');
                if (iconEl) iconEl.className = 'fas fa-spinner';
                const spanEl = btn.querySelector('span');
                if (spanEl) spanEl.textContent = 'Génération...';
            } else {
                btn.classList.remove('generating');
                const iconEl = btn.querySelector('i');
                if (iconEl) iconEl.className = 'fas fa-file-pdf';
                const spanEl = btn.querySelector('span');
                if (spanEl) spanEl.textContent = 'Générer en PDF';
            }
        });

        const progressBar = document.getElementById('pdfProgressBar');
        if (progressBar) progressBar.style.display = isGenerating ? 'block' : 'none';
    }

    function updateProgress(percent) {
        const fill = document.getElementById('pdfProgressFill');
        if (fill) fill.style.width = percent + '%';
    }
</script>
@endpush