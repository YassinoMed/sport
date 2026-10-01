@extends('layouts.user-layout')

@push('styles')
<style>
/* ... existing styles ... */
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
    .actions-cell {
        justify-content: flex-end;
        width: 100%;
    }
    .id-badge {
        margin-left: auto;
    }
}
</style>
@endpush

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
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Salles</span>
                                <span class="label-ar">القاعات</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                        <span class="label-fr">Gestion des salles</span>
                        <span class="label-ar" dir="rtl">إدارة القاعات</span>
                    </h1>
                </div>
                <a href="{{ route('gym.create') }}" class="btn-primary-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    <span class="label-fr">Nouvelle salle</span>
                    <span class="label-ar">قاعة جديدة</span>
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Stats Card -->
            <div class="stats-grid mb-4">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $gyms->total() ?? $gyms->count() }}</span>
                        <span class="stat-label">
                            <span class="label-fr">Total salles</span>
                            <span class="label-ar">إجمالي القاعات</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Gyms Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">
                            <span class="label-fr">Liste des salles</span>
                            <span class="label-ar" dir="rtl">قائمة القاعات</span>
                        </h3>
                    </div>
                </div>

                <div class="table-container">
                    <table class="modern-table" id="gymsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>
                                    <span class="label-fr">Salle</span>
                                    <span class="label-ar">القاعة</span>
                                </th>
                                <th>
                                    <span class="label-fr">Ville</span>
                                    <span class="label-ar">المدينة</span>
                                </th>
                                <th>
                                    <span class="label-fr">Créée le</span>
                                    <span class="label-ar">تاريخ الإنشاء</span>
                                </th>
                                <th class="text-end">
                                    <span class="label-fr">Actions</span>
                                    <span class="label-ar">الإجراءات</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gyms as $gym)
                                <tr id="gid{{ $gym->id }}">
                                    <td data-label="ID">
                                        <span class="id-badge">#{{ $gym->id }}</span>
                                    </td>
                                    <td data-label="{{ __('messages.gym') }}">
                                        <div class="gym-cell">
                                            <div class="gym-avatar">
                                                @if($gym->cover_image)
                                                    <img src="{{ asset($gym->cover_image) }}" alt="{{ $gym->name }}">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/></svg>
                                                @endif
                                            </div>
                                            <span class="gym-name">{{ $gym->name }}</span>
                                        </div>
                                    </td>
                                    <td data-label="{{ __('messages.city') }}">
                                        @if($gym->city)
                                            <span class="location-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                {{ $gym->city->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Aucune ville</span>
                                        @endif
                                    </td>
                                    <td data-label="{{ __('messages.created_at') }}">
                                        <div class="date-cell">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ optional($gym->created_at)->format('d M Y') ?? '—' }}
                                        </div>
                                    </td>
                                    <td data-label="{{ __('messages.actions') }}">
                                        <div class="actions-cell">
                                            <a href="{{ route('gym.show', $gym->id) }}" class="action-btn action-view" title="Voir">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            <a href="{{ route('gym.edit', $gym->id) }}" class="action-btn action-edit" title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </a>
                                            <button type="button" class="action-btn action-delete" onclick="deleteGym({{ $gym->id }})" title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/></svg>
                                            <h4>Aucune salle trouvée</h4>
                                            <p>لا توجد صالات</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($gyms, 'links'))
                    <div class="pagination-container">
                        {{ $gyms->links('pagination::bootstrap-5') }}
                    </div>
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
        <h3>
            <span class="label-fr">Confirmer la suppression</span>
            <span class="label-ar" dir="rtl">تأكيد الحذف</span>
        </h3>
        <p>
            <span class="label-fr">Êtes-vous sûr de vouloir supprimer cette salle ? Cette action est irréversible.</span>
            <span class="label-ar" dir="rtl">هل أنت متأكد من رغبتك في حذف هذه القاعة؟ هذا الإجراء غير قابل للتراجع.</span>
        </p>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="closeModal()">
                <span class="label-fr">Annuler</span>
                <span class="label-ar">إلغاء</span>
            </button>
            <button class="btn-danger-modern" id="confirmDeleteBtn">
                <span class="label-fr">Supprimer</span>
                <span class="label-ar">حذف</span>
            </button>
        </div>
    </div>
</div>

<script>
let pendingDeleteId = null;

function deleteGym(id) {
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
    
    fetch('/gym/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(function(response) {
        if (!response.ok) throw new Error('Request failed');
        const row = document.getElementById('gid' + id);
        if (row) {
            row.style.animation = 'fadeOutRow 0.3s ease forwards';
            setTimeout(() => row.remove(), 300);
        }
        if (document.querySelectorAll('#gymsTable tbody tr').length === 0) {
            location.reload();
        }
    }).catch(function(err) {
        console.error(err);
        alert('Erreur lors de la suppression');
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

.page-title svg { color: var(--primary); }
.page-subtitle { font-size: 0.875rem; color: var(--gray-500); margin: 0.25rem 0 0; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.stat-card { background: white; border-radius: var(--radius-lg); padding: 1.25rem; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 1rem; transition: var(--transition); }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.stat-icon { width: 52px; height: 52px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.stat-primary .stat-icon { background: var(--primary-bg); color: var(--primary); }
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

/* ID Badge */
.id-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background: var(--gray-100); color: var(--gray-600); border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; }

/* Gym Cell */
.gym-cell { display: flex; align-items: center; gap: 0.75rem; }
.gym-avatar { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--primary-bg); color: var(--primary); border-radius: var(--radius-md); overflow: hidden; }
.gym-avatar img { width: 100%; height: 100%; object-fit: cover; }
.gym-name { font-weight: 600; color: var(--gray-900); font-size: 0.9375rem; }

/* Location Badge */
.location-badge { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.875rem; color: var(--gray-600); }
.location-badge svg { color: var(--danger); }

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
</style>
@endpush
@endsection
