@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <!-- Content Header -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">{{ __('messages.coach_report') }}</h1>
                    <p class="page-subtitle">{{ __('messages.coach_report') }} — {{ $coach->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <form method="GET" action="{{ route('coach.report', $coach->id) }}" class="d-flex gap-2 align-items-center bg-white p-2 rounded shadow-sm">
                        <input type="month" name="month" class="form-control-modern form-control-sm" value="{{ $month }}">
                        <select name="category_id" class="form-control-modern form-control-sm">
                            <option value="">{{ __('messages.all_categories') }}</option>
                            @foreach(($categories ?? []) as $cat)
                                <option value="{{ $cat->id }}" {{ (string)($categoryId ?? '') === (string)$cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary-modern btn-sm">
                            <i class="fas fa-filter me-1"></i>{{ __('messages.filter') }}
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>{{ __('messages.print') }}
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <style>
                @media print {
                    nav, aside, .main-footer, .btn, .content-header, .no-print {
                        display: none !important;
                    }
                    .content-wrapper {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    .modern-card {
                        box-shadow: none !important;
                        border: 1px solid #eee !important;
                    }
                }
                .report-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 1.5rem;
                }
                .salary-status-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                }
                .month-badge {
                    padding: 0.5rem 1rem;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                .month-badge-paid {
                    background-color: #e8f5e9;
                    color: #2e7d32;
                    border: 1px solid #c8e6c9;
                }
                .month-badge-unpaid {
                    background-color: #ffebee;
                    color: #c62828;
                    border: 1px solid #ffcdd2;
                }
            </style>

            <div class="row">
                <!-- Expenses Summary -->
                <div class="col-lg-4 mb-4">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon bg-primary text-white">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div>
                                <h3 class="header-title bilingual-label">
                                    <span>{{ __('messages.expenses_summary', [], 'fr') }}</span>
                                    <span class="separator">/</span>
                                    <span class="lang-secondary" dir="rtl">{{ __('messages.expenses_summary', [], 'ar') }}</span>
                                </h3>
                            </div>
                        </div>
                        <div class="table-container p-0">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.category') }}</th>
                                        <th class="text-end">{{ __('messages.amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($expensesByCategory ?? []) as $label => $amount)
                                        <tr>
                                            <td><span class="fw-medium">{{ $label }}</span></td>
                                            <td class="text-end">
                                                <span class="badge bg-light text-dark border">
                                                    {{ number_format(($amount ?? 0)/100, 2, ',', ' ') }} TND
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4">{{ __('messages.no_expenses') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th class="ps-3">{{ __('messages.total') }}</th>
                                        <th class="text-end pe-3">
                                            <span class="text-primary fw-bold">
                                                {{ number_format(($expensesTotal ?? 0)/100, 2, ',', ' ') }} TND
                                            </span>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="col-lg-8">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="mini-stat-card stat-sessions">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-cube"></i>
                                </div>
                                <div class="mini-stat-content">
                                    <span class="mini-stat-value">{{ $coursesCount }}</span>
                                    <span class="mini-stat-label">{{ __('messages.assigned_courses') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mini-stat-card stat-present">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="mini-stat-content">
                                    <span class="mini-stat-value">{{ collect($attendances)->sum('presents') }}</span>
                                    <span class="mini-stat-label">{{ __('messages.total_attendance') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mini-stat-card stat-absent">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="mini-stat-content">
                                    <span class="mini-stat-value">{{ collect($attendances)->sum('absents') }}</span>
                                    <span class="mini-stat-label">{{ __('messages.total_absences') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <!-- Attendance Details -->
            <div class="modern-card mt-4">
                <div class="card-header-modern">
                    <div class="header-icon bg-info text-white">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h3 class="header-title bilingual-label">
                            <span>{{ __('messages.attendance_details', [], 'fr') }}</span>
                            <span class="separator">/</span>
                            <span class="lang-secondary" dir="rtl">{{ __('messages.attendance_details', [], 'ar') }}</span>
                        </h3>
                    </div>
                </div>
                <div class="table-container p-0">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.day') }}</th>
                                <th class="text-center">{{ __('messages.presents') }}</th>
                                <th class="text-center">{{ __('messages.absents') }}</th>
                                <th class="text-center">{{ __('messages.global_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($attendances ?? []) as $a)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-day me-2 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($a->day)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-soft text-success px-3">
                                            {{ $a->presents }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-soft text-danger px-3">
                                            {{ $a->absents }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($a->presents > $a->absents)
                                            <span class="text-success small"><i class="fas fa-circle me-1"></i>{{ __('messages.majority_present') }}</span>
                                        @else
                                            <span class="text-warning small"><i class="fas fa-circle me-1"></i>{{ __('messages.vigilance') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">{{ __('messages.no_attendance_recorded') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @unless(auth()->user()->hasRole('coach'))
            <!-- Detailed Expenses -->
            <div class="modern-card mt-4">
                <div class="card-header-modern">
                    <div class="header-icon bg-secondary text-white">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div>
                        <h3 class="header-title bilingual-label">
                            <span>{{ __('messages.detailed_expenses', [], 'fr') }}</span>
                            <span class="separator">/</span>
                            <span class="lang-secondary" dir="rtl">{{ __('messages.detailed_expenses', [], 'ar') }}</span>
                        </h3>
                    </div>
                </div>
                <div class="table-container p-0">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.gym_gym') }}</th>
                                <th>{{ __('messages.method') }}</th>
                                <th class="text-end">{{ __('messages.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($expenses ?? []) as $e)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($e->expense_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-info-soft text-info">
                                            {{ optional($e->category)->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ optional($e->gym)->name ?? '—' }}</td>
                                    <td>
                                        <span class="text-muted small">{{ $e->method ?? '—' }}</span>
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ number_format(($e->amount_cents ?? 0)/100, 2, ',', ' ') }} TND
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">{{ __('messages.no_detailed_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endunless
        </div>
    </section>
</div>
@endsection
