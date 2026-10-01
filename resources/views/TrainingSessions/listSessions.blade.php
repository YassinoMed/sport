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
                            <li class="breadcrumb-item active">
                                {{ __('messages.sessions') }}
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ __('messages.session_management') }}
                    </h1>
                </div>
                <a href="{{ route('TrainingSessions.training_session') }}" class="btn-primary-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    {{ __('messages.new_session') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert-modern alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-modern alert-danger mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <ul class="mb-0 list-unstyled">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="stats-grid mb-4">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $trainingSessions->total() ?? $trainingSessions->count() }}</span>
                        <span class="stat-label">
                            {{ __('messages.total_sessions') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sessions Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6M9 20v-10M15 20v-2M18 20v-8M21 20v-4M6 20v-14M3 20v-2"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">
                            {{ __('messages.sessions_list') }}
                        </h3>
                    </div>
                </div>

                <div class="table-container">
                    <table class="modern-table" id="sessionsTable">
                        <thead>
                            <tr>
                                <th>{{ __('messages.id') }}</th>
                                <th>
                                    {{ __('messages.session_name') }}
                                </th>
                                <th>
                                    {{ __('messages.day') }}
                                </th>
                                <th>
                                    {{ __('messages.start') }}
                                </th>
                                <th>
                                    {{ __('messages.end') }}
                                </th>
                                <th class="text-end">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trainingSessions as $trainingSession)
                                <tr id="did{{ $trainingSession->id }}">
                                    <td>
                                        <span class="id-badge">#{{ $trainingSession->id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $trainingSession->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-info text-info px-3 py-2 rounded-pill">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $trainingSession->day }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="far fa-clock me-2 text-primary"></i>
                                            {{ \Carbon\Carbon::parse($trainingSession->starts_at)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="far fa-clock me-2 text-danger"></i>
                                            {{ \Carbon\Carbon::parse($trainingSession->finishes_at)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('TrainingSessions.show_training_session', $trainingSession->id) }}" class="btn-icon btn-icon-info" title="{{ __('messages.show') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            <a href="{{ route('TrainingSessions.edit_training_session', $trainingSession->id) }}" class="btn-icon btn-icon-warning" title="{{ __('messages.edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </a>
                                            <button type="button" class="btn-icon btn-icon-danger" onclick="deleteSession({{ $trainingSession->id }})" title="{{ __('messages.delete') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state py-5">
                                            <div class="empty-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                            </div>
                                            <h4 class="empty-title">
                                                {{ __('messages.no_sessions_found') }}
                                            </h4>
                                            <p class="empty-subtitle">
                                                {{ __('messages.no_sessions_registered') }}
                                            </p>
                                            <a href="{{ route('TrainingSessions.training_session') }}" class="btn-primary-modern mt-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                                {{ __('messages.new_session') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($trainingSessions, 'links'))
                    <div class="card-footer-modern">
                        {{ $trainingSessions->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modern-modal">
    <div class="modal-content-modern">
        <div class="modal-icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </div>
        <h3 class="modal-title">
            {{ __('messages.confirm_delete_title') }}
        </h3>
        <p class="modal-description">
            {{ __('messages.confirm_delete_session') }}
        </p>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-ghost">
                {{ __('messages.cancel') }}
            </button>
            <button onclick="confirmDelete()" class="btn-danger-modern">
                {{ __('messages.delete') }}
            </button>
        </div>
    </div>
</div>

<script>
let pendingDeleteId = null;

function deleteSession(id) {
    pendingDeleteId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    pendingDeleteId = null;
}

function confirmDelete() {
    if (!pendingDeleteId) return;
    
    $.ajax({
        url: '/TrainingSessions/' + pendingDeleteId,
        type: 'DELETE',
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            closeDeleteModal();
            if (response.success) {
                $("#did" + pendingDeleteId).fadeOut(500, function() { 
                    $(this).remove();
                    if ($('#sessionsTable tbody tr').length === 0) {
                        location.reload();
                    }
                });
            } else {
                alert("{{ __('messages.participation_delete_error') }}");
            }
        },
        error: function() {
            closeDeleteModal();
            alert("{{ __('messages.error') }}");
        }
    });
}
</script>

@push('styles')
<style>
    .rounded-4 {
        border-radius: var(--bs-border-radius-lg) !important;
    }
    .rounded-top-4 {
        border-top-left-radius: var(--bs-border-radius-lg) !important;
        border-top-right-radius: var(--bs-border-radius-lg) !important;
    }
    .table th,
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn {
        border-radius: 0.375rem !important;
    }
    .btn-group .btn:first-child {
        border-top-right-radius: 0.375rem !important;
        border-bottom-right-radius: 0.375rem !important;
    }
    .btn-group .btn:last-child {
        border-top-left-radius: 0.375rem !important;
        border-bottom-left-radius: 0.375rem !important;
    }
    @media (max-width: 768px) {
        .card {
            margin: 0 1rem;
        }
        .content-header h1 {
            font-size: 1.5rem;
        }
        .breadcrumb {
            font-size: 0.875rem;
        }
        .table-responsive {
            border-radius: 0.5rem;
        }
        .btn-group {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
    }
</style>
@endpush
@endsection