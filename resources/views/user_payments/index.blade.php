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
                                    {{ __('messages.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('PaymentPackage.stripe') }}" class="breadcrumb-link">{{ __('messages.payments') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('messages.history') }} {{ $user->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.monthly_payments') }}</h1>
                    <p class="page-subtitle">{{ $user->name }} — {{ __('messages.payment_history') }}</p>
                </div>
                <div class="d-flex gap-2">
                    @unless(auth()->user()->hasRole('coach'))
                    <a href="{{ route('user-payments.create', $user->id) }}" class="btn-primary-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                        {{ __('messages.add_payment') }}
                    </a>
                    @endunless
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(auth()->user()->hasRole('coach'))
                <div class="alert-modern alert-danger mb-4 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ __('messages.access_denied_financial_data') }}</span>
                </div>
            @endif

            <!-- Alertes -->
            @if (Session::has('success'))
                <div class="alert-modern alert-success mb-4 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ Session::get('success') }}</span>
                </div>
            @endif

            @unless(auth()->user()->hasRole('coach'))
            <!-- Status Grid -->
            <div class="modern-card mb-4">
                <div class="card-header-modern">
                    <div class="header-icon-box info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">{{ __('messages.subscription_overview') }}</h3>
                        <span class="header-subtitle">{{ __('messages.status_month_by_month') }}</span>
                    </div>
                </div>
                <div class="card-body-modern p-4">
                    @if(isset($statuses) && count($statuses) > 0)
                        <div class="status-grid">
                            @foreach($statuses as $s)
                                @php $isPaid = ($s['status'] ?? '') === 'paid'; @endphp
                                <div class="status-item {{ $isPaid ? 'paid' : 'due' }}">
                                    <span class="month">{{ \Illuminate\Support\Carbon::parse($s['month'])->translatedFormat('M Y') }}</span>
                                    <span class="status-badge-mini">
                                        @if($isPaid)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                            {{ __('messages.paid') }}
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                            {{ __('messages.due') }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <p>{{ __('messages.no_monthly_history') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon-box success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="header-title">{{ __('messages.transaction_history') }}</h3>
                        <span class="header-subtitle">{{ __('messages.transaction_details') }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.plan_period') }}</th>
                                <th>{{ __('messages.amount') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.method') }}</th>
                                <th>{{ __('messages.validity') }}</th>
                                <th class="text-end">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $p)
                                <tr>
                                    <td data-label="{{ __('messages.plan_period') }}">
                                        <div class="d-flex flex-column">
                                            <span class="month-badge-modern">
                                                {{ \Illuminate\Support\Carbon::parse($p->billing_month)->translatedFormat('F Y') }}
                                            </span>
                                            <small class="text-muted">{{ $p->trainingPackage->name ?? __('messages.standard_subscription') }}</small>
                                        </div>
                                    </td>
                                    <td data-label="{{ __('messages.amount') }}">
                                        <span class="amount-badge">{{ number_format($p->amount_cents / 100, 2) }} TND</span>
                                    </td>
                                    <td data-label="{{ __('messages.status') }}">
                                        @if($p->status === 'paid')
                                            <span class="status-badge status-paid">{{ __('messages.paid') }}</span>
                                        @elseif($p->status === 'due')
                                            <span class="status-badge status-due">{{ __('messages.pending') }}</span>
                                        @else
                                            <span class="status-badge">{{ ucfirst($p->status) }}</span>
                                        @endif
                                    </td>
                                    <td data-label="{{ __('messages.method') }}">
                                        <span class="method-badge-modern">
                                            @if($p->method === 'cash') 💵 {{ __('messages.cash') }} 
                                            @elseif($p->method === 'card') 💳 {{ __('messages.card') }}
                                            @elseif($p->method === 'transfer') 🏦 {{ __('messages.transfer') }}
                                            @elseif($p->method === 'check') 📝 {{ __('messages.check') }}
                                            @else {{ $p->method }} @endif
                                        </span>
                                    </td>
                                    <td data-label="{{ __('messages.validity') }}">
                                        @if($p->start_date && $p->end_date)
                                            <div class="date-badge-modern" style="font-size: 0.75rem;">
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="text-success">●</span> 
                                                    {{ $p->start_date->format('d/m/y') }}
                                                </div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="text-danger">●</span>
                                                    {{ $p->end_date->format('d/m/y') }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">{{ __('messages.simple_month') }}</span>
                                        @endif
                                    </td>
                                    <td data-label="{{ __('messages.actions') }}" class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($p->status === 'paid')
                                                <a href="{{ route('user-payments.invoice', $p->id) }}" class="btn-action-small invoice" title="{{ __('messages.download_invoice') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('user-payments.edit', $p->id) }}" class="btn-action-small edit" title="{{ __('messages.edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
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
            @endunless
        </div>
    </section>
</div>


@push('styles')
<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-bg: #eef2ff;
    --success: #10b981;
    --success-dark: #059669;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
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
    --radius-md: 12px;
    --radius-lg: 20px;
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

/* Header Styles */
.page-title { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); margin: 0; }
.page-subtitle { font-size: 1rem; color: var(--primary); font-weight: 600; margin-top: 0.25rem; }
.breadcrumb { background: transparent; padding: 0; margin-bottom: 0.5rem; font-size: 0.875rem; }
.breadcrumb-link { color: var(--gray-500); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; }
.breadcrumb-link:hover { color: var(--primary); }
.breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--gray-400); }

/* Buttons */
.btn-primary-modern { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none; padding: 0.75rem 1.5rem; font-weight: 700; border-radius: var(--radius-md); transition: var(--transition); cursor: pointer; text-decoration: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3); }
.btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4); color: white; }

/* Main Card */
.modern-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.card-header-modern { display: flex; align-items: center; gap: 1rem; padding: 1.5rem 2rem; border-bottom: 1px solid var(--gray-100); }
.header-icon-box { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.header-icon-box.info { background: var(--primary-bg); color: var(--primary); }
.header-icon-box.success { background: var(--success-light); color: var(--success-dark); }
.header-title { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.header-subtitle { font-size: 0.8125rem; color: var(--gray-500); }

/* Status Grid */
.status-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
.status-item { display: flex; flex-direction: column; gap: 0.5rem; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--gray-200); transition: var(--transition); }
.status-item.paid { background: var(--success-light); border-color: var(--success); }
.status-item.due { background: var(--danger-light); border-color: var(--danger); }
.status-item .month { font-size: 0.875rem; font-weight: 700; color: var(--gray-800); }
.status-badge-mini { display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; }
.status-item.paid .status-badge-mini { color: var(--success-dark); }
.status-item.due .status-badge-mini { color: #991b1b; }

/* Table Styles */
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
.modern-table td { padding: 1.25rem 1rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
.modern-table tbody tr:hover { background: var(--gray-50); }

.month-badge-modern { font-weight: 700; color: var(--gray-900); }
.amount-badge { font-weight: 800; color: var(--success-dark); font-size: 1rem; }
.status-badge { display: inline-flex; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
.status-paid { background: var(--success-light); color: var(--success-dark); }
.status-due { background: var(--warning-light); color: #92400e; }
.method-badge-modern { font-size: 0.875rem; color: var(--gray-600); font-weight: 500; }
.date-badge-modern { font-size: 0.8125rem; color: var(--gray-500); }
.notes-text { font-size: 0.8125rem; color: var(--gray-500); display: block; max-width: 250px; overflow: hidden; text-overflow: ellipsis; }

.btn-action-small { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--gray-100); color: var(--gray-600); border-radius: 8px; transition: var(--transition); text-decoration: none; }
.btn-action-small:hover { transform: translateY(-2px); }
.btn-action-small.invoice { color: var(--primary); background: var(--primary-bg); }
.btn-action-small.edit { color: var(--gray-600); background: var(--gray-100); }
.btn-action-small.invoice:hover { background: var(--primary); color: white; }
.btn-action-small.edit:hover { background: var(--gray-800); color: white; }

/* Alerts */
.alert-modern { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; }
.alert-success { background: var(--success-light); color: var(--success-dark); border-left: 5px solid var(--success); }

/* Responsive */
@media (max-width: 768px) {
    .status-grid { grid-template-columns: repeat(2, 1fr); }
    .page-title { font-size: 1.5rem; }

    /* Card view for table */
    .modern-table thead { display: none; }
    .modern-table, .modern-table tbody, .modern-table tr, .modern-table td { display: block; width: 100%; }
    .modern-table tr {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-md);
    }
    .modern-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-50);
        text-align: right;
    }
    .modern-table td:last-child { border-bottom: none; padding-top: 1rem; }
    .modern-table td::before {
        content: attr(data-label);
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-right: 1rem;
        text-align: left;
    }
}

/* Pagination Fix */
.pagination { margin-bottom: 0; gap: 4px; }
.pagination svg { width: 16px; height: 16px; }
.page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
.page-link { color: var(--primary); border-radius: 8px !important; margin: 0 2px; border: 1px solid var(--gray-200); }
</style>
@endpush
@endsection
