@extends('layouts.user-layout')
@section('content')
<style>
    /* Mobile Responsive Table */
    @media (max-width: 768px) {
        .modern-table thead {
            display: none;
        }
        
        .modern-table, .modern-table tbody, .modern-table tr, .modern-table td {
            display: block;
            width: 100%;
        }
        
        .modern-table tr {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .modern-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0.5rem;
            border: none;
            border-bottom: 1px solid #f1f5f9;
            text-align: right;
        }
        
        .modern-table td:last-child {
            border-bottom: none;
            padding-top: 1rem;
            justify-content: center;
        }
        
        .modern-table td::before {
            content: attr(data-label);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #64748b;
            margin-right: 1rem;
            text-align: left;
        }

        .user-cell {
            justify-content: flex-end;
            width: 100%;
        }

        .actions-cell {
            justify-content: center;
            width: 100%;
        }
    }
</style>
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
                                    {{ __('messages.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('messages.payments_history') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.payments_history') }}</h1>
                    <p class="page-subtitle">{{ __('messages.payments_history') }}</p>
                </div>
                <a href="{{ route('PaymentPackage.stripe') }}" class="btn-primary-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    {{ __('messages.new_payment') }}
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon stat-icon-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">{{ __('messages.total_payments') }}</span>
                            <span class="stat-value">{{ $payments->total() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon stat-icon-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">{{ __('messages.paid_plural') }}</span>
                            <span class="stat-value">{{ $payments->where('status', 'paid')->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon stat-icon-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">{{ __('messages.due') }}</span>
                            <span class="stat-value">{{ $payments->where('status', 'due')->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon stat-icon-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">{{ __('messages.total_amount') }}</span>
                            <span class="stat-value">{{ number_format($payments->sum('amount_cents') / 100, 2, ',', ' ') }} TND</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="header-title">{{ __('messages.transactions_history') }}</h3>
                        <span class="header-subtitle">{{ __('messages.payments_list_subtitle') }}</span>
                    </div>
                    <div class="header-actions d-flex gap-3 align-items-center">
                        <form action="{{ route('PaymentPackage.purchase_history') }}" method="GET" class="d-flex gap-2 align-items-center" id="filter-form">
                            <select name="filter" class="form-select form-select-sm" style="width: auto; min-width: 140px; border-radius: 8px; border-color: var(--gray-200); padding: 0.5rem 2rem 0.5rem 1rem;" onchange="this.form.submit()">
                                <option value="">{{ __('messages.all_time') }}</option>
                                <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>{{ __('messages.today') }}</option>
                                <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>{{ __('messages.this_week') }}</option>
                                <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>{{ __('messages.this_month') }}</option>
                                <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>{{ __('messages.custom_date') }}</option>
                            </select>
                            
                            @if(request('filter') == 'custom')
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm" style="width: auto; border-radius: 8px; border-color: var(--gray-200);">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm" style="width: auto; border-radius: 8px; border-color: var(--gray-200);">
                                <button type="submit" class="btn btn-sm btn-primary-modern" style="padding: 0.4rem 0.8rem; height: 34px;">
                                    {{ __('messages.filter') }}
                                </button>
                            @endif
                        </form>

                        <div class="search-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" id="search-input" placeholder="{{ __('messages.search_placeholder') }}" onkeyup="filterTable()">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="modern-table" id="payments-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.id') }}</th>
                                <th>{{ __('messages.user') }}</th>
                                <th>{{ __('messages.month') }}</th>
                                <th>{{ __('messages.amount') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.method') }}</th>
                                @role('admin')
                                    <th>{{ __('messages.gym') }}</th>
                                    <th>{{ __('messages.city') }}</th>
                                @endrole
                                @role('cityManager')
                                    <th>{{ __('messages.gym') }}</th>
                                @endrole
                                <th>{{ __('messages.date') }}</th>
                                <th class="text-end">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td data-label="{{ __('messages.id') }}">
                                        <span class="id-badge">#{{ $payment->id }}</span>
                                    </td>
                                    <td data-label="{{ __('messages.user') }}">
                                        <div class="user-cell">
                                            <div class="user-avatar">
                                                {{ strtoupper(substr(optional($payment->user)->name ?? '?', 0, 2)) }}
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ optional($payment->user)->name ?? '—' }}</span>
                                                <span class="user-email">{{ optional($payment->user)->phone ?? optional($payment->user)->email ?? '—' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="{{ __('messages.month') }}">
                                        <span class="month-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ $payment->billing_month ? $payment->billing_month->translatedFormat('M Y') : '—' }}
                                        </span>
                                    </td>
                                    <td data-label="{{ __('messages.amount') }}">
                                        <span class="amount-badge">{{ number_format($payment->amount_cents / 100, 2) }} TND</span>
                                    </td>
                                    <td data-label="{{ __('messages.status') }}">
                                        @if($payment->status === 'paid')
                                            <span class="status-badge status-paid">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                {{ __('messages.paid_plural') }}
                                            </span>
                                        @elseif($payment->status === 'due')
                                            <span class="status-badge status-due">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                {{ __('messages.due') }}
                                            </span>
                                        @else
                                            <span class="status-badge">{{ $payment->status }}</span>
                                        @endif
                                    </td>
                                    <td data-label="{{ __('messages.method') }}">
                                        <span class="method-badge">
                                            @if($payment->method === 'cash')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                                                {{ __('messages.cash') }}
                                            @elseif($payment->method === 'card')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                                {{ __('messages.card') }}
                                            @elseif($payment->method === 'transfer')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
                                                {{ __('messages.transfer') }}
                                            @elseif($payment->method === 'check')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                {{ __('messages.check') }}
                                            @else
                                                {{ $payment->method ?? '—' }}
                                            @endif
                                        </span>
                                    </td>
                                    @role('admin')
                                        <td data-label="{{ __('messages.gym') }}">
                                            <span class="gym-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                                                {{ optional(optional($payment->user)->gym)->name ?? '—' }}
                                            </span>
                                        </td>
                                        <td data-label="{{ __('messages.city') }}">
                                            <span class="city-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="22" y2="22"/><polygon points="12 2 20 7 4 7"/></svg>
                                                {{ optional(optional($payment->user)->city)->name ?? '—' }}
                                            </span>
                                        </td>
                                    @endrole
                                    @role('cityManager')
                                        <td data-label="{{ __('messages.gym') }}">
                                            <span class="gym-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                                                {{ optional(optional($payment->user)->gym)->name ?? '—' }}
                                            </span>
                                        </td>
                                    @endrole
                                    <td data-label="{{ __('messages.date') }}">
                                        <span class="date-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : ($payment->created_at ? $payment->created_at->format('d/m/Y') : '—') }}
                                        </span>
                                    </td>
                                    <td class="text-end" data-label="{{ __('messages.actions') }}">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($payment->status === 'paid')
                                                <a href="{{ route('user-payments.invoice', $payment->id) }}" class="btn-action-small invoice" title="{{ __('messages.download_invoice') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('user-payments.edit', $payment->id) }}" class="btn-action-small edit" title="{{ __('messages.edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                            <p>{{ __('messages.no_payments_found') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->hasPages())
                    <div class="card-footer-modern border-top d-flex justify-content-center py-4">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<script>
function filterTable() {
    const input = document.getElementById('search-input').value.toLowerCase();
    const rows = document.querySelectorAll('#payments-table tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
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
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

/* Header */
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.page-subtitle { font-size: 1rem; color: var(--primary); font-weight: 600; margin: 0.25rem 0 0; }
.breadcrumb { padding: 0; margin: 0; background: transparent; font-size: 0.875rem; }
.breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--gray-400); }
.breadcrumb-link { color: var(--gray-500); text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; }
.breadcrumb-link:hover { color: var(--primary); }

/* Buttons */
.btn-primary-modern { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; font-size: 0.875rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4); }
.btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.5); color: white; }

/* Stat Cards */
.stat-card-modern { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); }
.stat-icon { width: 56px; height: 56px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon-primary { background: var(--primary-bg); color: var(--primary); }
.stat-icon-success { background: var(--success-light); color: var(--success); }
.stat-icon-info { background: var(--info-light); color: var(--info); }
.stat-icon-warning { background: var(--warning-light); color: var(--warning); }
.stat-content { display: flex; flex-direction: column; }
.stat-label { font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); }

/* Card */
.modern-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.card-header-modern { display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-100); flex-wrap: wrap; }
.header-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.header-icon-primary { background: var(--primary-bg); color: var(--primary); }
.header-title { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.header-subtitle { font-size: 0.8125rem; color: var(--gray-500); }
.header-actions { margin-left: auto; }
.card-footer-modern { padding: 1rem 1.5rem; background: var(--gray-50); border-top: 1px solid var(--gray-100); }

/* Search Box */
.search-box { position: relative; }
.search-box svg { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); }
.search-box input { padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem; border: 2px solid var(--gray-200); border-radius: var(--radius-md); width: 200px; transition: var(--transition); }
.search-box input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }

/* Table */
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
.modern-table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); }
.modern-table tbody tr:hover { background: var(--gray-50); }

/* Badges */
.id-badge { display: inline-flex; padding: 0.25rem 0.5rem; background: var(--gray-100); color: var(--gray-600); font-size: 0.75rem; font-weight: 600; border-radius: var(--radius-sm); }
.month-badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: var(--primary-bg); color: var(--primary-dark); font-size: 0.8125rem; font-weight: 600; border-radius: var(--radius-sm); }
.amount-badge { display: inline-flex; padding: 0.375rem 0.75rem; background: var(--success-light); color: var(--success-dark); font-size: 0.875rem; font-weight: 700; border-radius: var(--radius-sm); }
.status-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; }
.status-paid { background: var(--success-light); color: var(--success-dark); }
.status-due { background: var(--warning-light); color: #b45309; }
.method-badge { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; color: var(--gray-600); }
.gym-badge, .city-badge { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.8125rem; color: var(--gray-600); }
.date-badge { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; color: var(--gray-500); }

.btn-action-small { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--gray-100); color: var(--gray-600); border-radius: 8px; transition: var(--transition); text-decoration: none; }
.btn-action-small:hover { transform: translateY(-2px); }
.btn-action-small.invoice { color: var(--primary); background: var(--primary-bg); }
.btn-action-small.edit { color: var(--gray-600); background: var(--gray-100); }
.btn-action-small.invoice:hover { background: var(--primary); color: white; }
.btn-action-small.edit:hover { background: var(--gray-800); color: white; }

/* User Cell */
.user-cell { display: flex; align-items: center; gap: 0.75rem; }
.user-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem; flex-shrink: 0; }
.user-info { display: flex; flex-direction: column; min-width: 0; }
.user-name { font-weight: 600; color: var(--gray-900); font-size: 0.875rem; }
.user-email { font-size: 0.75rem; color: var(--gray-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Empty State */
.empty-state { padding: 2rem; text-align: center; color: var(--gray-400); }
.empty-state svg { margin-bottom: 1rem; opacity: 0.5; }
.empty-state p { margin: 0; font-size: 0.9375rem; }

/* Responsive */
@media (max-width: 768px) {
    .page-title { font-size: 1.375rem; }
    .search-box input { width: 150px; }
    .modern-table th, .modern-table td { padding: 0.75rem 0.5rem; font-size: 0.8125rem; }
    .user-avatar { width: 32px; height: 32px; font-size: 0.625rem; }
}

/* Pagination Fix */
.pagination { margin-bottom: 0; gap: 4px; }
.pagination svg { width: 16px; height: 16px; }
.page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
.page-link { color: var(--primary); border-radius: 8px !important; margin: 0 2px; border: 1px solid var(--gray-200); }
</style>
@endpush
@endsection
