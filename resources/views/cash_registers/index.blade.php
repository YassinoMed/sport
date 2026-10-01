@extends('layouts.user-layout')

@push('styles')
<style>
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
    .actions-cell::before {
        content: none;
    }
}

/* Specific styles for this page */
.page-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.page-title i { color: var(--primary); }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.stat-card { background: white; border-radius: var(--radius-lg); padding: 1.25rem; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 1rem; transition: var(--transition); }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.stat-icon { width: 52px; height: 52px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.stat-primary .stat-icon { background: var(--primary-bg); color: var(--primary); }
.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
.stat-label { font-size: 0.8125rem; color: var(--gray-500); font-weight: 500; }

.table-container { overflow-x: auto; }
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table thead { background: var(--gray-50); }
.modern-table th { padding: 1rem; font-size: 0.75rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--gray-200); }
.modern-table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
.modern-table tbody tr { transition: var(--transition); }
.modern-table tbody tr:hover { background: var(--gray-50); }

.id-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background: var(--gray-100); color: var(--gray-600); border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; }

.gym-cell { display: flex; align-items: center; gap: 0.75rem; }
.gym-avatar { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--primary-bg); color: var(--primary); border-radius: var(--radius-md); overflow: hidden; }
.gym-name { font-weight: 600; color: var(--gray-900); font-size: 0.9375rem; }

.location-badge { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.875rem; color: var(--gray-600); }
.location-badge i { color: var(--danger); }

.actions-cell { display: flex; gap: 0.5rem; align-items: center; }
.action-btn { width: 32px; height: 32px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; transition: var(--transition); border: none; background: transparent; cursor: pointer; }
.action-edit { color: var(--primary); background: var(--primary-bg); }
.action-edit:hover { background: var(--primary); color: white; }
.action-delete { color: var(--danger); background: var(--danger-light); }
.action-delete:hover { background: var(--danger); color: white; }

.bg-success-soft { background-color: rgba(34, 197, 94, 0.1); }
.bg-danger-soft { background-color: rgba(239, 68, 68, 0.1); }
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
                                <a href="{{ route('welcome') }}" class="breadcrumb-link">
                                    <i class="fas fa-home"></i>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Caisses</span>
                                <span class="label-ar">الصناديق</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-cash-register me-2"></i>
                        <span class="label-fr">Gestion des caisses</span>
                        <span class="label-ar" dir="rtl">إدارة الصناديق</span>
                    </h1>
                </div>
                <a href="{{ route('cash-registers.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus me-2"></i>
                    <span class="label-fr">Nouvelle caisse</span>
                    <span class="label-ar">صندوق جديد</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="content mb-4">
        <div class="container-fluid">
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $cashRegisters->total() }}</span>
                        <span class="stat-label">
                            <span class="label-fr">Total caisses</span>
                            <span class="label-ar">إجمالي الصناديق</span>
                        </span>
                    </div>
                </div>
                <div class="stat-card stat-success">
                    <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ number_format($cashRegisters->sum('balance'), 2, ',', ' ') }} TND</span>
                        <span class="stat-label">
                            <span class="label-fr">Montant total</span>
                            <span class="label-ar">المبلغ الإجمالي</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Cash Registers Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <i class="fas fa-list"></i>
                    </div>
                    <div>
                        <h3 class="header-title">
                            <span class="label-fr">Liste des caisses</span>
                            <span class="label-ar" dir="rtl">قائمة الصناديق</span>
                        </h3>
                    </div>
                </div>

                <div class="table-container">
                    <table class="modern-table" id="cashRegistersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>
                                    <span class="label-fr">Nom</span>
                                    <span class="label-ar">الاسم</span>
                                </th>
                                <th>
                                    <span class="label-fr">Salle</span>
                                    <span class="label-ar">القاعة</span>
                                </th>
                                <th>
                                    <span class="label-fr">Montant</span>
                                    <span class="label-ar">المبلغ</span>
                                </th>
                                <th>
                                    <span class="label-fr">Statut</span>
                                    <span class="label-ar">الحالة</span>
                                </th>
                                <th class="text-end">
                                    <span class="label-fr">Actions</span>
                                    <span class="label-ar">الإجراءات</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cashRegisters as $register)
                            <tr id="cid{{ $register->id }}">
                                <td data-label="ID">
                                    <span class="id-badge">#{{ $register->id }}</span>
                                </td>
                                <td data-label="Nom / الاسم">
                                    <div class="gym-cell">
                                        <div class="gym-avatar">
                                            <i class="fas fa-cash-register"></i>
                                        </div>
                                        <span class="gym-name">{{ $register->name }}</span>
                                    </div>
                                </td>
                                <td data-label="Salle / القاعة">
                                    <span class="location-badge">
                                        <i class="fas fa-dumbbell me-1"></i>
                                        {{ $register->gym->name ?? '—' }}
                                    </span>
                                </td>
                                <td data-label="Montant / المبلغ">
                                    <span class="fw-bold text-success">
                                        {{ number_format($register->balance, 2, ',', ' ') }} TND
                                    </span>
                                </td>
                                <td data-label="Statut / الحالة">
                                    @if($register->status === 'active')
                                        <span class="badge bg-success-soft text-success">
                                            <span class="label-fr">Actif</span>
                                            <span class="label-ar">نشط</span>
                                        </span>
                                    @else
                                        <span class="badge bg-danger-soft text-danger">
                                            <span class="label-fr">Inactif</span>
                                            <span class="label-ar">غير نشط</span>
                                        </span>
                                    @endif
                                </td>
                                <td data-label="{{ __('messages.actions') }}">
                                    <div class="actions-cell">
                                        <a href="{{ route('cash-registers.edit', $register->id) }}" class="action-btn action-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="action-btn action-delete" onclick="deleteCashRegister({{ $register->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-cash-register fa-3x text-muted mb-3"></i>
                                        <h4>Aucune caisse trouvée</h4>
                                        <p>لا توجد صناديق</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($cashRegisters, 'links'))
                    <div class="pagination-container p-4">
                        {{ $cashRegisters->links('pagination::bootstrap-5') }}
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
            <i class="fas fa-trash"></i>
        </div>
        <h3>
            <span class="label-fr">Confirmer la suppression</span>
            <span class="label-ar" dir="rtl">تأكيد الحذف</span>
        </h3>
        <p>
            <span class="label-fr">Êtes-vous sûr de vouloir supprimer cette caisse ? Cette action est irréversible.</span>
            <span class="label-ar" dir="rtl">هل أنت متأكد من رغبتك في حذف هذا الصندوق؟ هذا الإجراء غير قابل للتراجع.</span>
        </p>
        <div class="modal-actions d-flex justify-content-center gap-3 mt-4">
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

function deleteCashRegister(id) {
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
    
    fetch('/cash-registers/' + id, {
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
        if (document.querySelectorAll('#cashRegistersTable tbody tr').length === 0) {
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
@endsection
