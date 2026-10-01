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
                            <li class="breadcrumb-item active">{{ __('messages.belts') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                        {{ __('messages.belts') }}
                    </h1>
                </div>
                <a href="{{ route('belt.create') }}" class="btn-success-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    {{ __('messages.add_belt') }}
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Stats Card -->
            <div class="stats-grid mb-4">
                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $belts->total() ?? $belts->count() }}</span>
                        <span class="stat-label">{{ __('messages.total_belts') }}</span>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="modern-card mb-4 p-4">
                <form action="{{ route('belt.list') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">{{ __('messages.search') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0" placeholder="{{ __('messages.search_athlete_placeholder') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">{{ __('messages.belt') }}</label>
                        <select name="belt_name" class="form-select bg-light border-0">
                            <option value="">{{ __('messages.all_belts') }}</option>
                            @foreach($beltOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('belt_name') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-filter me-2"></i>{{ __('messages.apply_filters') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Belts Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon belt-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">{{ __('messages.belts_list') }}</h3>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert-modern alert-error mx-4 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($belts->count() === 0)
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                        <h4>{{ __('messages.no_belts_registered') }}</h4>
                        <a href="{{ route('belt.create') }}" class="btn-success-modern mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            {{ __('messages.add_belt') }}
                        </a>
                    </div>
                @else
                    <div class="table-container">
                        <table class="modern-table" id="beltsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.athlete') }}</th>
                                    <th>{{ __('messages.belt') }}</th>
                                    <th>{{ __('messages.date_obtained') }}</th>
                                    <th class="text-end">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($belts as $belt)
                                    <tr id="bid{{ $belt->id }}">
                                        <td data-label="{{ __('messages.id') }}">
                                            <span class="id-badge">#{{ $belt->id }}</span>
                                        </td>
                                        <td data-label="{{ __('messages.athlete') }}">
                                            @if($belt->user)
                                                <div class="user-cell">
                                                    <div class="user-avatar-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="user-name">{{ $belt->user->name }}</span>
                                                        <span class="user-email">{{ $belt->user->email }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('messages.no_athlete') }}</span>
                                            @endif
                                        </td>
                                        <td data-label="{{ __('messages.belt') }}">
                                            <span class="belt-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                                {{ $belt->belt_name }}
                                            </span>
                                        </td>
                                        <td data-label="{{ __('messages.date_obtained') }}">
                                            <div class="date-cell">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                {{ optional($belt->achieved_at)->format('d M Y') ?? '—' }}
                                            </div>
                                        </td>
                                        <td data-label="{{ __('messages.actions') }}">
                                            <div class="actions-cell">
                                                <a href="{{ route('belt.show', $belt->id) }}" class="action-btn action-view" title="{{ __('messages.view') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </a>
                                                <a href="{{ route('belt.edit', $belt->id) }}" class="action-btn action-edit" title="{{ __('messages.edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </a>
                                                <button type="button" class="action-btn action-delete" onclick="deleteBelt({{ $belt->id }})" title="{{ __('messages.delete') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($belts, 'links'))
                        <div class="pagination-container">
                            {{ $belts->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-modern">
        <div class="modal-icon modal-icon-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
        </div>
        <h3>{{ __('messages.confirm_deletion') }}</h3>
        <p>{{ __('messages.confirm_delete_belt') }}</p>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="closeModal()">{{ __('messages.cancel') }}</button>
            <button class="btn-danger-modern" id="confirmDeleteBtn">{{ __('messages.delete') }}</button>
        </div>
    </div>
</div>

<script>
let pendingDeleteId = null;

function deleteBelt(id) {
    pendingDeleteId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('active');
    pendingDeleteId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!pendingDeleteId) return;
    const id = pendingDeleteId;
    closeModal();
    
    fetch('/belts/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(function(response) {
        if (!response.ok) throw new Error('Request failed');
        const row = document.getElementById('bid' + id);
        if (row) {
            row.style.animation = 'fadeOutRow 0.3s ease forwards';
            setTimeout(() => row.remove(), 300);
        }
        if (document.querySelectorAll('#beltsTable tbody tr').length === 0) {
            location.reload();
        }
    }).catch(function(err) {
        console.error(err);
        alert("{{ __('messages.delete_error') }}");
    });
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

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

.page-title svg { color: var(--warning); }
.page-subtitle { font-size: 0.875rem; color: var(--gray-500); margin: 0.25rem 0 0; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.stat-card { background: white; border-radius: var(--radius-lg); padding: 1.25rem; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 1rem; transition: var(--transition); }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.stat-icon { width: 52px; height: 52px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.stat-warning .stat-icon { background: var(--warning-light); color: var(--warning); }
.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
.stat-label { font-size: 0.8125rem; color: var(--gray-500); font-weight: 500; }

/* Belt header icon */
.belt-header-icon { background: var(--warning-light); color: var(--warning); }

/* Table */
.table-container { overflow-x: auto; }
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table thead { background: var(--gray-50); }
.modern-table th { padding: 1rem; font-size: 0.75rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--gray-200); }
.modern-table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
.modern-table tbody tr { transition: var(--transition); }
.modern-table tbody tr:hover { background: var(--gray-50); }

/* ID Badge */
.id-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background: var(--gray-100); color: var(--gray-600); border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; }

/* User Cell */
.user-cell { display: flex; align-items: center; gap: 0.75rem; }
.user-avatar-sm { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: var(--primary-bg); color: var(--primary); border-radius: var(--radius-full); }
.user-info { display: flex; flex-direction: column; }
.user-name { font-weight: 600; color: var(--gray-900); font-size: 0.875rem; }
.user-email { font-size: 0.75rem; color: var(--gray-500); }

/* Belt Badge */
.belt-badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: var(--warning-light); color: #92400e; border-radius: 9999px; font-weight: 600; font-size: 0.8125rem; }
.belt-badge svg { color: var(--warning); }

/* Date Cell */
.date-cell { display: flex; align-items: center; gap: 0.5rem; color: var(--gray-600); font-size: 0.875rem; }
.date-cell svg { color: var(--gray-400); }

/* Actions */
.actions-cell { display: flex; gap: 0.375rem; justify-content: flex-end; }
.action-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); transition: var(--transition); border: none; cursor: pointer; text-decoration: none; }
.action-view { background: var(--info-light); color: var(--info); }
.action-view:hover { background: var(--info); color: white; }
.action-edit { background: var(--warning-light); color: var(--warning); }
.action-edit:hover { background: var(--warning); color: white; }
.action-delete { background: var(--danger-light); color: var(--danger); }
.action-delete:hover { background: var(--danger); color: white; }

/* Empty State */
.empty-state { text-align: center; padding: 4rem 2rem; }
.empty-state svg { color: var(--gray-300); margin-bottom: 1rem; }
.empty-state h4 { font-size: 1.25rem; font-weight: 600; color: var(--gray-700); margin: 0 0 0.5rem; }
.empty-state p { color: var(--gray-500); font-size: 0.9375rem; margin: 0; }

/* Pagination */
.pagination-container { padding: 1.5rem; display: flex; justify-content: center; }

/* Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 9999; }
.modal-overlay.active { opacity: 1; visibility: visible; }
.modal-modern { background: white; border-radius: var(--radius-lg); padding: 2rem; max-width: 400px; width: 90%; text-align: center; transform: scale(0.9); transition: transform 0.3s ease; }
.modal-overlay.active .modal-modern { transform: scale(1); }
.modal-icon { width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
.modal-icon-danger { background: var(--danger-light); color: var(--danger); }
.modal-modern h3 { font-size: 1.25rem; font-weight: 700; color: var(--gray-900); margin: 0 0 0.5rem; }
.modal-modern p { color: var(--gray-600); margin: 0 0 1.5rem; }
.modal-actions { display: flex; gap: 0.75rem; justify-content: center; }

@keyframes fadeOutRow {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(-20px); }
    }

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
            border-radius: var(--radius-lg);
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            position: relative;
        }
        
        .modern-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0.5rem;
            border: none;
            border-bottom: 1px solid var(--gray-100);
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
            color: var(--gray-500);
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
@endpush
@endsection