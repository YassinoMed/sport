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
                                <a href="{{ route('user-payments.index', $user->id) }}" class="breadcrumb-link">{{ __('messages.payments') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('messages.edit_payment') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.edit_payment') }}</h1>
                    <p class="page-subtitle">{{ $user->name }}</p>
                </div>
                <a href="{{ route('user-payments.index', $user->id) }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Sidebar - Info Athlète -->
                <div class="col-lg-4">
                    <div class="summary-card">
                        <div class="summary-header">
                            <div class="summary-avatar-main shadow">
                                @if($user->profile_image)
                                    <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                @endif
                            </div>
                            <h2 class="summary-title">{{ $user->name }}</h2>
                            <p class="summary-subtitle">{{ __('messages.athlete') }} #{{ $user->id }}</p>
                        </div>
                        <div class="summary-body">
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label">{{ __('messages.phone') }}</span>
                                    <span class="info-value">{{ $user->phone ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('messages.gym') }}</span>
                                    <span class="info-value">{{ optional($user->gym)->name ?? __('messages.not_assigned') }}</span>
                                </div>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="payment-info-mini">
                                <span class="mini-title">{{ __('messages.current_payment') }}</span>
                                <div class="payment-mini-status info">
                                    <span class="amount">{{ number_format($payment->amount_cents / 100, 2) }} TND</span>
                                    <span class="date">{{ \Illuminate\Support\Carbon::parse($payment->billing_month)->translatedFormat('F Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire Principal -->
                <div class="col-lg-8">
                    @if ($errors->any())
                        <div class="alert-modern alert-error mb-4 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            <div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon-box warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">{{ __('messages.update_transaction') }}</h3>
                                <span class="header-subtitle">{{ __('messages.update_payment_info') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('user-payments.update', $payment->id) }}" method="post" class="card-body-modern p-4">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="billing_month">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ __('messages.billing_month') }}
                                        </label>
                                        <input type="month" id="billing_month" name="billing_month" class="form-input-modern @error('billing_month') is-invalid @enderror" value="{{ old('billing_month', $billing_month) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="package_id">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                            {{ __('messages.training_package') }}
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="package_id" name="package_id" class="form-select-modern" onchange="updateAmount(this)">
                                                <option value="">{{ __('messages.select_package') }}</option>
                                                @foreach($packages as $package)
                                                    <option value="{{ $package->id }}" data-price="{{ $package->price }}" {{ old('package_id', $payment->package_id) == $package->id ? 'selected' : '' }}>
                                                        {{ $package->name }} ({{ number_format($package->price, 2) }} TND)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <svg class="select-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="amount">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                            {{ __('messages.amount') }} (TND)
                                        </label>
                                        <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-input-modern @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount_cents / 100) }}" placeholder="0.00" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="start_date">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ __('messages.membership_start') }}
                                        </label>
                                        <input type="date" id="start_date" name="start_date" class="form-input-modern @error('start_date') is-invalid @enderror" value="{{ old('start_date', $payment->start_date ? $payment->start_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="end_date">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ __('messages.membership_end') }}
                                        </label>
                                        <input type="date" id="end_date" name="end_date" class="form-input-modern @error('end_date') is-invalid @enderror" value="{{ old('end_date', $payment->end_date ? $payment->end_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="cash_register_id">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M7 15h0M2 9.5h20"/><path d="M12 15h0M17 15h0"/></svg>
                                            {{ __('messages.cash_register') ?? 'Caisse' }}
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="cash_register_id" name="cash_register_id" class="form-select-modern">
                                                <option value="">{{ __('messages.select_cash_register') ?? 'Choisir une caisse...' }}</option>
                                                @foreach($cashRegisters as $register)
                                                    <option value="{{ $register->id }}" {{ old('cash_register_id', $payment->cash_register_id) == $register->id ? 'selected' : '' }}>
                                                        {{ $register->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <svg class="select-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="method">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                            {{ __('messages.payment_method') }}
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="method" name="method" class="form-select-modern" required>
                                                <option value="cash" {{ old('method', $payment->method) == 'cash' ? 'selected' : '' }}>💵 {{ __('messages.cash') }}</option>
                                                <option value="card" {{ old('method', $payment->method) == 'card' ? 'selected' : '' }}>💳 {{ __('messages.card') }}</option>
                                                <option value="transfer" {{ old('method', $payment->method) == 'transfer' ? 'selected' : '' }}>🏦 {{ __('messages.transfer') }}</option>
                                                <option value="check" {{ old('method', $payment->method) == 'check' ? 'selected' : '' }}>📝 {{ __('messages.check') }}</option>
                                            </select>
                                            <svg class="select-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="status">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            {{ __('messages.payment_status') }}
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="status" name="status" class="form-select-modern" required>
                                                <option value="paid" {{ old('status', $payment->status) == 'paid' ? 'selected' : '' }}>✅ {{ __('messages.paid_status') }}</option>
                                                <option value="due" {{ old('status', $payment->status) == 'due' ? 'selected' : '' }}>⏳ {{ __('messages.due_status') }}</option>
                                                <option value="refund" {{ old('status', $payment->status) == 'refund' ? 'selected' : '' }}>↩️ {{ __('messages.refund_status') }}</option>
                                            </select>
                                            <svg class="select-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="paid_at">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            {{ __('messages.payment_date_time') }}
                                        </label>
                                        <input type="datetime-local" id="paid_at" name="paid_at" class="form-input-modern" value="{{ old('paid_at', $payment->paid_at ? $payment->paid_at->format('Y-m-d\TH:i') : '') }}">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="notes">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                            {{ __('messages.notes') }}
                                        </label>
                                        <textarea id="notes" name="notes" class="form-input-modern" rows="3" placeholder="{{ __('messages.fill_additional_info') }}">{{ old('notes', $payment->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions-modern mt-5 border-top pt-4">
                                <button type="submit" class="btn-primary-modern w-100 py-3 shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    {{ __('messages.update_payment') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Action de suppression -->
                    <div class="mt-4 text-center">
                        <form action="{{ route('user-payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_payment') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                {{ __('messages.delete_transaction_permanently') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function updateAmount(select) {
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    if (price) {
        document.getElementById('amount').value = price;
    }
}
</script>

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
}

.page-title { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); margin: 0; }
.page-subtitle { font-size: 1rem; color: var(--primary); font-weight: 600; }
.btn-ghost { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: white; color: var(--gray-600); font-weight: 600; border: 1px solid var(--gray-200); border-radius: var(--radius-md); text-decoration: none; }
.btn-ghost:hover { background: var(--gray-100); }

.summary-card { background: white; border-radius: var(--radius-lg); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); overflow: hidden; }
.summary-header { background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); padding: 2rem; text-align: center; }
.summary-avatar-main { width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.75rem; font-weight: 800; color: var(--warning); border: 4px solid rgba(255,255,255,0.3); overflow: hidden; }
.summary-avatar-main img { width: 100%; height: 100%; object-fit: cover; }
.summary-title { color: white; font-size: 1.25rem; font-weight: 700; margin: 0; }
.summary-subtitle { color: rgba(255,255,255,0.85); font-size: 0.8125rem; }

.summary-body { padding: 1.5rem; }
.info-list { display: flex; flex-direction: column; gap: 1rem; }
.info-item { display: flex; flex-direction: column; }
.info-label { font-size: 0.6875rem; text-transform: uppercase; font-weight: 700; color: var(--gray-400); }
.info-value { font-size: 0.9375rem; font-weight: 600; color: var(--gray-800); }
.summary-divider { height: 1px; background: var(--gray-100); margin: 1.5rem 0; }

.payment-mini-status { display: flex; justify-content: space-between; align-items: center; background: var(--gray-50); padding: 0.75rem; border-radius: var(--radius-md); border-left: 4px solid var(--warning); }
.payment-mini-status.info { border-left-color: var(--primary); }
.payment-mini-status .amount { font-weight: 700; color: var(--gray-900); }
.payment-mini-status .date { font-size: 0.75rem; color: var(--primary); font-weight: 600; }

.modern-card { background: white; border-radius: var(--radius-lg); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); overflow: hidden; }
.card-header-modern { display: flex; align-items: center; gap: 1rem; padding: 1.5rem 2rem; border-bottom: 1px solid var(--gray-100); }
.header-icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
.header-icon-box.warning { background: #fffbeb; color: var(--warning); }

.form-group-modern { display: flex; flex-direction: column; gap: 0.5rem; }
.form-label-modern { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 600; color: var(--gray-700); }
.form-input-modern { padding: 0.75rem 1rem; border: 2px solid var(--gray-100); border-radius: var(--radius-md); transition: all 0.2s; font-size: 0.9375rem; }
.form-input-modern:focus { outline: none; border-color: var(--primary); }

.select-wrapper-modern { position: relative; }
.form-select-modern { width: 100%; padding: 0.75rem 1rem; border: 2px solid var(--gray-100); border-radius: var(--radius-md); appearance: none; background: white; }
.select-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; }

.btn-primary-modern { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none; font-weight: 700; border-radius: var(--radius-md); cursor: pointer; }
.btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4); }

.btn-link-danger { background: none; border: none; color: var(--danger); font-size: 0.875rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-link-danger:hover { text-decoration: underline; color: #b91c1c; }

.alert-modern { display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: var(--radius-md); font-weight: 500; }
.alert-error { background: var(--danger-light); color: #991b1b; }

@media (max-width: 992px) {
    .summary-card { position: static; margin-bottom: 2rem; }
}
</style>
@endpush
@endsection
