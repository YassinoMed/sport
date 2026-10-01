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
                                    {{ __('messages.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('messages.expenses') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.expenses_management') }}</h1>
                    <p class="page-subtitle">إدارة المصاريف</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('expenses.create') }}" class="btn-primary-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        {{ __('messages.new_expense') }}
                    </a>
                    <a href="{{ route('expense-categories.create') }}" class="btn-secondary-modern">
                        {{ __('messages.categories') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Summary Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card stat-danger">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">{{ __('messages.total_expenses') }}</span>
                            <span class="stat-value">{{ number_format($total_cents/100, 2, ',', ' ') }} TND</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card stat-info">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">{{ __('messages.transactions') }}</span>
                            <span class="stat-value">{{ $expenses->total() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card stat-success">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6M6 20V10M18 20V4"/></svg>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">{{ __('messages.average') }}</span>
                            <span class="stat-value">{{ $expenses->total() > 0 ? number_format(($total_cents/100)/$expenses->total(), 2, ',', ' ') : '0' }} TND</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('expenses.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="month" class="form-label-modern">{{ __('messages.month') }} / الشهر</label>
                            <input id="month" type="month" name="month" class="form-input-modern" value="{{ $month }}">
                        </div>
                        <div class="col-md-3">
                            <label for="coach_id" class="form-label-modern">{{ __('messages.coach') }} / المدرب</label>
                            <select id="coach_id" name="coach_id" class="form-select-modern">
                                <option value="">{{ __('messages.all_coaches') }}</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}" {{ (string)$selectedCoachId === (string)$coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="expense_category_id" class="form-label-modern">{{ __('messages.category') }} / الصنف</label>
                            <select id="expense_category_id" name="expense_category_id" class="form-select-modern">
                                <option value="">{{ __('messages.all_categories') }}</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ request('expense_category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn-primary-modern flex-grow-1">
                                {{ __('messages.filter') }}
                            </button>
                            <a href="{{ route('expenses.index') }}" class="btn-ghost" title="{{ __('messages.reset') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <!-- Breakdown by Category -->
                <div class="col-lg-4">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">{{ __('messages.breakdown_by_category') }}</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.category') }}</th>
                                            <th class="text-end">{{ __('messages.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($by_category as $row)
                                            <tr>
                                                <td class="fw-medium">{{ $row['label'] }}</td>
                                                <td class="text-end text-danger fw-bold">{{ number_format($row['amount_cents']/100, 2, ',', ' ') }} TND</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses List -->
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern d-flex justify-content-between align-items-center">
                            <h3 class="card-title-modern">{{ __('messages.expenses_list') }}</h3>
                            @if(!empty($selectedCoachId))
                                <a href="{{ route('expenses.coach_report', ['coach' => $selectedCoachId, 'month' => $month]) }}" target="_blank" class="btn-outline-primary btn-sm">
                                    {{ __('messages.coach_report') }}
                                </a>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.details') }}</th>
                                            <th>{{ __('messages.amount') }}</th>
                                            <th>{{ __('messages.method') }}</th>
                                            <th class="text-end">{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expenses as $e)
                                            <tr>
                                                <td>
                                                    <span class="text-muted d-block small">{{ __('messages.date') }}</span>
                                                    {{ \Illuminate\Support\Carbon::parse($e->expense_date)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    <span class="fw-bold d-block">{{ $e->category->name ?? '-' }}</span>
                                                    <span class="text-muted small">
                                                        {{ $e->subcategory->name ?? '-' }} 
                                                        @if($e->coach) • {{ $e->coach->name }} @endif
                                                        @if($e->gym) • {{ $e->gym->name }} @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-danger fw-bold">{{ number_format($e->amount_cents/100, 2, ',', ' ') }} TND</span>
                                                </td>
                                                <td>
                                                    <span class="badge-status {{ strtolower($e->method) == 'cash' ? 'badge-cash' : 'badge-bank' }}">
                                                        {{ $e->method }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('expenses.show', $e->id) }}" class="btn-icon action-view" title="Voir les détails">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                        </a>
                                                        <a href="{{ route('expenses.edit', $e->id) }}" class="btn-icon action-edit" title="{{ __('messages.edit') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                        </a>
                                                        <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-icon action-delete" onclick="return confirm('{{ __('messages.confirm_delete') }}')" title="{{ __('messages.delete') }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-25"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                                        <p>{{ __('messages.no_expenses_found_period') }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($expenses->hasPages())
                            <div class="card-footer-modern">
                                {{ $expenses->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Modern UI Specific Overrides for Expenses */
.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s;
}
.stat-card:hover { transform: translateY(-4px); }
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-danger .stat-icon { background: rgba(245, 101, 101, 0.1); color: #f56565; }
.stat-info .stat-icon { background: rgba(66, 153, 225, 0.1); color: #4299e1; }
.stat-success .stat-icon { background: rgba(72, 187, 120, 0.1); color: #48bb78; }
.stat-details { display: flex; flex-direction: column; }
.stat-label { font-size: 0.875rem; color: var(--text-secondary); font-weight: 500; }
.stat-value { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); }

.badge-status {
    padding: 0.35rem 0.75rem;
    border-radius: 2rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}
.badge-cash { background: rgba(72, 187, 120, 0.1); color: #48bb78; }
.badge-bank { background: rgba(66, 153, 225, 0.1); color: #4299e1; }

.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: all 0.2s;
}
.action-view { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
.action-edit { background: rgba(66, 153, 225, 0.1); color: #4299e1; }
.action-delete { background: rgba(245, 101, 101, 0.1); color: #f56565; }
.btn-icon:hover { transform: scale(1.1); }
</style>
@endsection
