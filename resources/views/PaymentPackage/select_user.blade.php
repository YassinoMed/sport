@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
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
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.home', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.home', [], 'ar') }}</span>
                                    </span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="bilingual-label">
                                    <span>{{ __('messages.register_payment', [], 'fr') }}</span>
                                    <span class="separator">/</span>
                                    <span class="lang-secondary" dir="rtl">{{ __('messages.register_payment', [], 'ar') }}</span>
                                </span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title bilingual-label">
                        <span>{{ __('messages.register_payment', [], 'fr') }}</span>
                        <span class="separator">/</span>
                        <span class="lang-secondary" dir="rtl">{{ __('messages.register_payment', [], 'ar') }}</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('PaymentPackage.purchase_history') }}" class="btn-primary-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span class="bilingual-label">
                            <span>{{ __('messages.history', [], 'fr') }}</span>
                            <span class="separator">/</span>
                            <span class="lang-secondary" dir="rtl">{{ __('messages.history', [], 'ar') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon stat-icon-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label bilingual-label">
                                <span>{{ __('messages.total_athletes', [], 'fr') }}</span>
                                <span class="separator">/</span>
                                <span class="lang-secondary" dir="rtl">{{ __('messages.total_athletes', [], 'ar') }}</span>
                            </span>
                            <span class="stat-value">{{ App\Models\User::role('user')->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon stat-icon-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label bilingual-label">
                                <span>{{ __('messages.active_athletes', [], 'fr') }}</span>
                                <span class="separator">/</span>
                                <span class="lang-secondary" dir="rtl">{{ __('messages.active_athletes', [], 'ar') }}</span>
                            </span>
                            <span class="stat-value">{{ App\Models\User::role('user')->withoutBanned()->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon stat-icon-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-7"/><path d="M9 18l3 3 3-3"/><path d="M21 15V9a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6"/><path d="m21 13-3.4-3.4a2 2 0 0 0-2.8 0L12 12.2"/><path d="m15 13-3.4-3.4a2 2 0 0 0-2.8 0L6 12.2"/></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label bilingual-label">
                                <span>{{ __('messages.recent_payments', [], 'fr') }}</span>
                                <span class="separator">/</span>
                                <span class="lang-secondary" dir="rtl">{{ __('messages.recent_payments', [], 'ar') }}</span>
                            </span>
                            <span class="stat-value">{{ App\Models\UserPayment::where('created_at', '>=', now()->subDays(7))->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="header-title bilingual-label">
                            <span>{{ __('messages.select_athlete', [], 'fr') }}</span>
                            <span class="separator">/</span>
                            <span class="lang-secondary" dir="rtl">{{ __('messages.select_athlete', [], 'ar') }}</span>
                        </h3>
                        <span class="header-subtitle bilingual-label">
                            <span>{{ __('messages.select_athlete_subtitle', [], 'fr') }}</span>
                            <span class="separator">/</span>
                            <span class="lang-secondary" dir="rtl">{{ __('messages.select_athlete_subtitle', [], 'ar') }}</span>
                        </span>
                    </div>
                    <div class="header-actions">
                        <form action="{{ route('PaymentPackage.stripe') }}" method="GET" class="search-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" name="q" placeholder="{{ __('messages.search_placeholder', [], 'fr') }} / {{ __('messages.search_placeholder', [], 'ar') }}" value="{{ request('q') }}">
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.athlete', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.athlete', [], 'ar') }}</span>
                                    </span>
                                </th>
                                <th>
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.contact', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.contact', [], 'ar') }}</span>
                                    </span>
                                </th>
                                <th>
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.gym_city', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.gym_city', [], 'ar') }}</span>
                                    </span>
                                </th>
                                <th>
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.account_status', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.account_status', [], 'ar') }}</span>
                                    </span>
                                </th>
                                <th class="text-end">
                                    <span class="bilingual-label justify-content-end">
                                        <span>{{ __('messages.action', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.action', [], 'ar') }}</span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-avatar">
                                                @if($user->profile_image)
                                                    <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                @endif
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ $user->name }}</span>
                                                <span class="user-email">ID: #{{ $user->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="gym-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l2.27-2.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                                {{ $user->phone ?? '—' }}
                                            </span>
                                            <span class="date-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                                {{ $user->email ?? '—' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="gym-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                                                {{ optional($user->gym)->name ?? __('messages.not_assigned') }}
                                            </span>
                                            <span class="city-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="22" y2="22"/><polygon points="12 2 20 7 4 7"/></svg>
                                                {{ optional($user->city)->name ?? '—' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->isBanned())
                                            <span class="status-badge status-banned bilingual-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" x2="19.07" y1="4.93" y2="19.07"/></svg>
                                                <span>{{ __('messages.banned_badge', [], 'fr') }}</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">{{ __('messages.banned_badge', [], 'ar') }}</span>
                                            </span>
                                        @else
                                            <span class="status-badge status-active bilingual-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                <span>{{ __('messages.active_badge', [], 'fr') }}</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">{{ __('messages.active_badge', [], 'ar') }}</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('user-payments.create', $user->id) }}" class="btn-action-primary shadow-sm" title="{{ __('messages.register_payment') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                            <span class="bilingual-label">
                                                <span>{{ __('messages.pay', [], 'fr') }}</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">{{ __('messages.pay', [], 'ar') }}</span>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                            <p class="bilingual-label">
                                                <span>{{ __('messages.no_users_found', [], 'fr') }}</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">{{ __('messages.no_users_found', [], 'ar') }}</span>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="card-footer-modern border-top d-flex justify-content-center py-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

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
.breadcrumb { padding: 0; margin: 0; background: transparent; font-size: 0.875rem; }
.breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--gray-400); }
.breadcrumb-link { color: var(--gray-500); text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; }
.breadcrumb-link:hover { color: var(--primary); }

/* Buttons */
.btn-primary-modern { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; font-size: 0.875rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4); }
.btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.5); color: white; }

.btn-action-primary { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.5rem 1rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: var(--transition); border: 1px solid transparent; }
.btn-action-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4); color: white; }

/* Stat Cards */
.stat-card-modern { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); }
.stat-icon { width: 56px; height: 56px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon-primary { background: var(--primary-bg); color: var(--primary); }
.stat-icon-success { background: var(--success-light); color: var(--success); }
.stat-icon-info { background: var(--info-light); color: var(--info); }
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
.search-box input { padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem; border: 2px solid var(--gray-200); border-radius: var(--radius-md); width: 250px; transition: var(--transition); }
.search-box input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }

/* Table */
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
.modern-table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); }
.modern-table tbody tr:hover { background: var(--gray-50); }

/* Badges */
.gym-badge, .city-badge, .date-badge { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.8125rem; color: var(--gray-600); }
.status-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; }
.status-active { background: var(--success-light); color: var(--success-dark); }
.status-banned { background: var(--danger-light); color: var(--danger); }

/* User Cell */
.user-cell { display: flex; align-items: center; gap: 0.75rem; }
.user-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem; flex-shrink: 0; overflow: hidden; }
.user-info { display: flex; flex-direction: column; min-width: 0; }
.user-name { font-weight: 600; color: var(--gray-900); font-size: 0.875rem; }
.user-email { font-size: 0.75rem; color: var(--gray-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Empty State */
.empty-state { padding: 2rem; text-align: center; color: var(--gray-400); }
.empty-state svg { margin-bottom: 1rem; opacity: 0.5; }
.empty-state p { margin: 0; font-size: 0.9375rem; }

/* Bilingual Support */
.bilingual-label { display: inline-flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; }
.bilingual-label .separator { color: var(--gray-300); font-size: 0.85em; font-weight: 300; }
.bilingual-label .lang-secondary { font-size: 0.9em; opacity: 0.8; }
[dir="rtl"] .bilingual-label { flex-direction: row-reverse; }

/* Pagination Fix */
.pagination { margin-bottom: 0; gap: 4px; }
.pagination svg { width: 16px; height: 16px; }
.page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
.page-link { color: var(--primary); border-radius: 8px !important; margin: 0 2px; border: 1px solid var(--gray-200); }

@media (max-width: 768px) {
    .page-title { font-size: 1.375rem; }
    .search-box input { width: 100%; }
    .modern-table th, .modern-table td { padding: 0.75rem 0.5rem; font-size: 0.8125rem; }
    .user-avatar { width: 32px; height: 32px; font-size: 0.625rem; }
    .btn-action-primary { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
}
</style>
@endpush
@endsection
