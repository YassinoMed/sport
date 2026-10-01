@extends('layouts.user-layout')

@section('content')
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --secondary-color: #64748b;
        --success-color: #22c55e;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --dark-color: #1e293b;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-500: #64748b;
        --gray-700: #334155;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --radius-lg: 0.75rem;
        --radius-md: 0.5rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
        background: transparent;
    }

    .modern-table thead th {
        background: white;
        padding: 1rem;
        font-weight: 600;
        color: var(--gray-700);
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.025em;
        border: none;
        border-bottom: 2px solid var(--gray-100);
    }

    .modern-table tbody tr {
        background: white;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .modern-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .modern-table td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
        background: white;
    }

    .modern-table td:first-child { border-radius: var(--radius-md) 0 0 var(--radius-md); }
    .modern-table td:last-child { border-radius: 0 var(--radius-md) var(--radius-md) 0; }

    .category-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .category-icon {
        width: 32px;        height: 32px;
        background: var(--gray-100);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
    }

    .category-name {
        font-weight: 600;
        color: var(--gray-700);
    }

    .text-description {
        color: var(--gray-500);
        font-size: 0.9rem;
    }

    .actions-cell {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        transition: all 0.2s;
        border: none;
    }

    .action-edit { background: var(--warning-color); color: white; }
    .action-delete { background: var(--danger-color); color: white; }

    .action-btn:hover {
        transform: scale(1.1);
        color: white;
        opacity: 0.9;
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
    }
</style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="font-weight-bold">Catégories</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Catégories</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-none bg-transparent">
                    <div class="card-body p-0">
                        <table class="modern-table" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $c)
                                    <tr id="cid{{ $c->id }}">
                                        <td data-label="{{ __('messages.name') }}">
                                            <div class="category-cell">
                                                <div class="category-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                                </div>
                                                <span class="category-name">{{ $c->name }}</span>
                                            </div>
                                        </td>
                                        <td data-label="{{ __('messages.description') }}">
                                            <span class="text-description">{{ $c->description ?? '—' }}</span>
                                        </td>
                                        <td class="text-end" data-label="{{ __('messages.actions') }}">
                                            <div class="actions-cell">
                                                <a href="{{ route('category.edit', $c->id) }}" class="action-btn action-edit" title="Modifier">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </a>
                                                <button type="button" class="action-btn action-delete" onclick="deleteCategory({{ $c->id }})" title="Supprimer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                            <h4>Aucune catégorie</h4>
                                            <p>لا توجد أصناف</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($categories, 'links'))
                    <div class="pagination-container">
                        {{ $categories->links('pagination::bootstrap-5') }}
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
        <p>Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.</p>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="closeModal()">Annuler</button>
            <button class="btn-danger-modern" id="confirmDeleteBtn">Supprimer</button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
let pendingDeleteId = null;

function deleteCategory(id) {
    pendingDeleteId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('active');
    pendingDeleteId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!pendingDeleteId) return;
    const form = document.getElementById('deleteForm');
    form.action = '/category/' + pendingDeleteId;
    form.submit();
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

.page-title svg { color: var(--info); }
.page-subtitle { font-size: 0.875rem; color: var(--gray-500); margin: 0.25rem 0 0; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.stat-card { background: white; border-radius: var(--radius-lg); padding: 1.25rem; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 1rem; transition: var(--transition); }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.stat-icon { width: 52px; height: 52px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.stat-info .stat-icon { background: var(--info-light); color: var(--info); }
.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
.stat-label { font-size: 0.8125rem; color: var(--gray-500); font-weight: 500; }

/* Header icon */
.header-icon-info { background: var(--info-light); color: var(--info); }

/* Table */
.table-container { overflow-x: auto; }
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table thead { background: var(--gray-50); }
.modern-table th { padding: 1rem; font-size: 0.75rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--gray-200); }
.modern-table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
.modern-table tbody tr { transition: var(--transition); }
.modern-table tbody tr:hover { background: var(--gray-50); }

/* Category Cell */
.category-cell { display: flex; align-items: center; gap: 0.75rem; }
.category-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: var(--info-light); color: var(--info); border-radius: var(--radius-sm); }
.category-name { font-weight: 600; color: var(--gray-900); font-size: 0.9375rem; }

.text-description { color: var(--gray-600); font-size: 0.875rem; }

/* Actions */
.actions-cell { display: flex; gap: 0.375rem; justify-content: flex-end; }
.action-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); transition: var(--transition); border: none; cursor: pointer; text-decoration: none; }
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
</style>
@endpush
@endsection
