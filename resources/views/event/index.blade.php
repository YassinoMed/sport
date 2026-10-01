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
                            <li class="breadcrumb-item active">{{ __('messages.events') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                        {{ __('messages.events') }}
                    </h1>
                </div>
                <a href="{{ route('events.create') }}" class="btn-primary-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    {{ __('messages.create_event') }}
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert-modern alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="stats-grid mb-4">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $events->total() ?? $events->count() }}</span>
                        <span class="stat-label">{{ __('messages.total_events') }}</span>
                    </div>
                </div>
                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $events->sum('registrations_count') ?? 0 }}</span>
                        <span class="stat-label">{{ __('messages.total_registrants') }}</span>
                    </div>
                </div>
            </div>

            <!-- Events Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">{{ __('messages.events_list') }}</h3>
                    </div>
                </div>

                @if($events->count() === 0)
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <h4>{{ __('messages.no_events_available') }}</h4>
                        <p>{{ __('messages.create_first_event_desc') }}</p>
                        <a href="{{ route('events.create') }}" class="btn-primary-modern mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            {{ __('messages.create_event') }}
                        </a>
                    </div>
                @else
                    <div class="table-container">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.event') }}</th>
                                    <th>{{ __('messages.dates') }}</th>
                                    <th>{{ __('messages.location') }}</th>
                                    <th>{{ __('messages.capacity') }}</th>
                                    <th>{{ __('messages.registrants') }}</th>
                                    @unless(auth()->user()->hasRole('coach'))
                                    <th>{{ __('messages.fee') }}</th>
                                    @endunless
                                    <th class="text-end">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                <tr>
                                    <td>
                                        <div class="event-cell">
                                            <div class="event-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            </div>
                                            <span class="event-name">{{ $event->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-range">
                                            <div class="date-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                {{ $event->start_at->format('d/m/Y') }} {{ __('messages.at_time') }} {{ $event->start_at->format('H:i') }}
                                            </div>
                                            @if($event->end_at)
                                                <div class="date-end">
                                                    <span class="date-separator">→</span>
                                                    {{ $event->end_at->format('d/m/Y') }} {{ __('messages.at_time') }} {{ $event->end_at->format('H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($event->location)
                                            <span class="location-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                {{ $event->location }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->capacity > 0)
                                            <span class="badge-modern badge-gray">{{ $event->capacity }}</span>
                                        @else
                                            <span class="badge-modern badge-success">{{ __('messages.unlimited') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="registrations-count">
                                            <span class="badge-modern badge-primary">{{ $event->registrations_count }}</span>
                                            @if($event->capacity > 0)
                                                <div class="capacity-bar">
                                                    <div class="capacity-fill" style="width: {{ min(100, ($event->registrations_count / $event->capacity) * 100) }}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    @unless(auth()->user()->hasRole('coach'))
                                    <td>
                                        <span class="price-badge">{{ number_format($event->registration_fee / 100, 2) }} {{ __('messages.amount_tnd') }}</span>
                                    </td>
                                    @endunless
                                    <td>
                                        <div class="actions-cell">
                                            <a href="{{ route('events.show', $event->id) }}" class="action-btn action-view" title="{{ __('messages.view_details') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            <a href="{{ route('events.register', $event->id) }}" class="action-btn action-register" title="{{ __('messages.register') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($events, 'links'))
                        <div class="pagination-container">
                            {{ $events->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
/* Page specific styles */
.page-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}

.page-title svg {
    color: var(--primary);
}

.page-subtitle {
    font-size: 0.875rem;
    color: var(--gray-500);
    margin: 0.25rem 0 0;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-primary .stat-icon { background: var(--primary-bg); color: var(--primary); }
.stat-success .stat-icon { background: var(--success-light); color: var(--success); }

.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
.stat-label { font-size: 0.8125rem; color: var(--gray-500); font-weight: 500; }

/* Table */
.table-container { overflow-x: auto; }
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table thead { background: var(--gray-50); }
.modern-table th { padding: 1rem; font-size: 0.75rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--gray-200); }
.modern-table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
.modern-table tbody tr { transition: var(--transition); }
.modern-table tbody tr:hover { background: var(--gray-50); }
.modern-table tbody tr:last-child td { border-bottom: none; }

/* Event Cell */
.event-cell { display: flex; align-items: center; gap: 0.75rem; }
.event-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: var(--primary-bg); color: var(--primary); border-radius: var(--radius-sm); }
.event-name { font-weight: 600; color: var(--gray-900); }

/* Date Range */
.date-range { display: flex; flex-direction: column; gap: 0.25rem; }
.date-start { display: flex; align-items: center; gap: 0.375rem; color: var(--gray-700); font-size: 0.875rem; }
.date-start svg { color: var(--gray-400); }
.date-end { font-size: 0.8125rem; color: var(--gray-500); padding-left: 1.125rem; }
.date-separator { color: var(--gray-400); margin-right: 0.25rem; }

/* Location Badge */
.location-badge { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.875rem; color: var(--gray-600); }
.location-badge svg { color: var(--danger); }

/* Registrations */
.registrations-count { display: flex; align-items: center; gap: 0.5rem; }
.capacity-bar { width: 40px; height: 6px; background: var(--gray-200); border-radius: 9999px; overflow: hidden; }
.capacity-fill { height: 100%; background: var(--success); border-radius: 9999px; transition: width 0.3s ease; }

/* Price Badge */
.price-badge { font-weight: 600; color: var(--success-dark); background: var(--success-light); padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.8125rem; }

/* Actions */
.actions-cell { 
    display: inline-flex; 
    gap: 0.5rem; 
    justify-content: flex-end; 
    align-items: center;
    padding: 0.375rem;
    border-radius: 9999px;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    position: relative;
    z-index: 2;
}
.action-btn { 
    width: 40px; 
    height: 40px; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 12px; 
    transition: var(--transition); 
    border: 1px solid var(--gray-200); 
    cursor: pointer; 
    text-decoration: none; 
    box-shadow: var(--shadow-sm);
    background: white;
    color: var(--gray-700);
    position: relative;
    z-index: 3;
    pointer-events: auto;
}
.action-btn:hover { 
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}
.action-btn:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}
.action-view { background: var(--info-light); color: var(--info-dark); border-color: rgba(6, 182, 212, 0.25); }
.action-view:hover { background: var(--info); color: white; border-color: var(--info); }
.action-register { background: var(--success-light); color: var(--success-dark); border-color: rgba(16, 185, 129, 0.25); }
.action-register:hover { background: var(--success); color: white; border-color: var(--success); }
.action-edit { background: var(--warning-light); color: var(--warning-dark); border-color: rgba(245, 158, 11, 0.25); }
.action-edit:hover { background: var(--warning); color: white; border-color: var(--warning); }

/* Empty State */
.empty-state { text-align: center; padding: 4rem 2rem; }
.empty-state svg { color: var(--gray-300); margin-bottom: 1rem; }
.empty-state h4 { font-size: 1.25rem; font-weight: 600; color: var(--gray-700); margin: 0 0 0.5rem; }
.empty-state p { color: var(--gray-500); font-size: 0.9375rem; margin: 0; }

/* Pagination */
.pagination-container { padding: 1.5rem; display: flex; justify-content: center; }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .actions-cell { flex-wrap: wrap; }
}
</style>
@endpush
@endsection
