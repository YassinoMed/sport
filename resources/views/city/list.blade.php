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
                                <a href="{{ route('welcome') }}" class="breadcrumb-link">
                                    <i class="fas fa-home me-1"></i>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Villes</span>
                                <span class="label-ar">المدن</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-city me-2 text-primary"></i>
                        <span class="label-fr">Gestion des villes</span>
                        <span class="label-ar" dir="rtl">إدارة المدن</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('city.showDeleted') }}" class="btn-ghost shadow-sm bg-white text-danger">
                        <i class="fas fa-trash-restore me-2"></i>
                        <span class="label-fr">Villes supprimées</span>
                        <span class="label-ar">المدن المحذوفة</span>
                    </a>
                    <a href="{{ route('city.create') }}" class="btn-primary-modern">
                        <i class="fas fa-plus me-2"></i>
                        <span class="label-fr">Nouvelle ville</span>
                        <span class="label-ar">إضافة مدينة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Stats Card -->
            <div class="stats-grid mb-4">
                <div class="stat-card stat-danger">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $allCities->total() ?? $allCities->count() }}</span>
                        <span class="stat-label">Total villes</span>
                    </div>
                </div>
            </div>

            <!-- Cities Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">Liste des villes</h3>
                        <span class="header-subtitle">قائمة المدن</span>
                    </div>
                </div>

                <div class="table-container">
                    <table class="modern-table" id="citiesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ville</th>
                                <th>Manager</th>
                                <th>Créée le</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allCities as $city)
                                <tr id="cid{{ $city->id }}">
                                    <td data-label="{{ __('messages.id') }}">
                                        <span class="id-badge">#{{ $city->id }}</span>
                                    </td>
                                    <td data-label="{{ __('messages.city') }}">
                                        <div class="city-cell">
                                            <div class="city-icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <span class="city-name">{{ $city->name }}</span>
                                        </div>
                                    </td>
                                    <td data-label="{{ __('messages.role_management') }}">
                                        @if ($city->manager)
                                            <div class="manager-cell">
                                                <div class="manager-avatar">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <span class="manager-name">{{ $city->manager->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <span class="label-fr">Aucun manager</span>
                                                <span class="label-ar">لا يوجد مدير</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td data-label="{{ __('messages.date') }}">
                                        <div class="date-cell">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $city->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td data-label="{{ __('messages.actions') }}">
                                        <div class="actions-cell">
                                            <a href="{{ route('city.show', $city->id) }}" class="action-btn action-view" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('city.edit', $city->id) }}" class="action-btn action-edit" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="action-btn action-delete" onclick="deleteCity({{ $city->id }}, {{ $city->manager_id ?? 0 }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            <h4>Aucune ville</h4>
                                            <p>لا توجد مدن</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($allCities, 'links'))
                    <div class="pagination-container">
                        {{ $allCities->links('pagination::bootstrap-5') }}
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
        <h3>Confirmer la suppression</h3>
        <p>Êtes-vous sûr de vouloir supprimer cette ville ? Cette action est irréversible.</p>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="closeModal()">Annuler</button>
            <button class="btn-danger-modern" id="confirmDeleteBtn">Supprimer</button>
        </div>
    </div>
</div>

<!-- Warning Modal for cities with manager -->
<div class="modal-overlay" id="warningModal">
    <div class="modal-modern">
        <div class="modal-icon modal-icon-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
        </div>
        <h3>Suppression impossible</h3>
        <p>Cette ville possède un manager et ne peut pas être supprimée. Veuillez d'abord retirer le manager.</p>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="closeWarningModal()">Compris</button>
        </div>
    </div>
</div>

<script>
let pendingDeleteId = null;

function deleteCity(id, managerId) {
    if (managerId > 0) {
        document.getElementById('warningModal').classList.add('active');
    } else {
        pendingDeleteId = id;
        document.getElementById('deleteModal').classList.add('active');
    }
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('active');
    pendingDeleteId = null;
}

function closeWarningModal() {
    document.getElementById('warningModal').classList.remove('active');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!pendingDeleteId) return;
    const id = pendingDeleteId;
    closeModal();
    
    fetch('/cities/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(function(response) {
        if (!response.ok) throw new Error('Request failed');
        const row = document.getElementById('cid' + id);
        if (row) {
            row.style.animation = 'fadeOutRow 0.3s ease forwards';
            setTimeout(() => row.remove(), 300);
        }
        if (document.querySelectorAll('#citiesTable tbody tr').length === 0) {
            location.reload();
        }
    }).catch(function(err) {
        console.error(err);
        alert('Erreur lors de la suppression');
    });
});

document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
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

.page-title svg { color: var(--danger); }
.page-subtitle { font-size: 0.875rem; color: var(--gray-500); margin: 0.25rem 0 0; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.stat-card { background: white; border-radius: var(--radius-lg); padding: 1.25rem; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 1rem; transition: var(--transition); }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.stat-icon { width: 52px; height: 52px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.stat-danger .stat-icon { background: var(--danger-light); color: var(--danger); }
.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
.stat-label { font-size: 0.8125rem; color: var(--gray-500); font-weight: 500; }

/* Header icon */
.header-icon-danger { background: var(--danger-light); color: var(--danger); }

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

/* City Cell */
.city-cell { display: flex; align-items: center; gap: 0.75rem; }
.city-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: var(--danger-light); color: var(--danger); border-radius: var(--radius-sm); }
.city-name { font-weight: 600; color: var(--gray-900); font-size: 0.9375rem; }

/* Manager Cell */
.manager-cell { display: flex; align-items: center; gap: 0.5rem; }
.manager-avatar { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: var(--primary-bg); color: var(--primary); border-radius: var(--radius-full); }
.manager-name { font-weight: 500; color: var(--gray-700); font-size: 0.875rem; }

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
.modal-icon-warning { background: var(--warning-light); color: var(--warning); }
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
        margin-top: 0.5rem;
        padding-top: 1rem;
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

    .city-cell {
        justify-content: flex-end;
        width: 100%;
    }

    .manager-cell {
        justify-content: flex-end;
        width: 100%;
    }

    .date-cell {
        justify-content: flex-end;
        width: 100%;
    }

    .actions-cell {
        justify-content: flex-end;
        width: 100%;
    }
}
</style>
@endpush
@endsection
