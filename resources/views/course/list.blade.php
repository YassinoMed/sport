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
                                    Accueil
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Cours</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                        Gestion des cours
                    </h1>
                    <p class="page-subtitle">إدارة الدورات</p>
                </div>
                <a href="{{ route('course.create') }}" class="btn-primary-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Nouveau cours
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="stats-grid mb-4">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $courses->total() ?? $courses->count() }}</span>
                        <span class="stat-label">Total cours</span>
                    </div>
                </div>
                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ count($gyms) }}</span>
                        <span class="stat-label">Salles</span>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="modern-card filter-card mb-4">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true">
                    <div class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        <span class="filter-title">Filtres de recherche</span>
                    </div>
                    <svg class="collapse-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="collapse show" id="filterCollapse">
                    <div class="filter-body">
                        <form method="GET" action="{{ route('course.list') }}">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                        <span class="label-fr">Recherche</span>
                                        <span class="label-ar" dir="rtl">بحث</span>
                                    </label>
                                    <input type="text" id="q" name="q" class="form-control-modern" value="{{ request('q') }}" placeholder="Nom ou description...">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/></svg>
                                        <span class="label-fr">Salle</span>
                                        <span class="label-ar" dir="rtl">القاعة</span>
                                    </label>
                                    <select id="gym_id" name="gym_id" class="form-select-modern select2">
                                        <option value="">Toutes les salles</option>
                                        @foreach($gyms as $gym)
                                            <option value="{{ $gym->id }}" {{ request('gym_id') == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <span class="label-fr">Date de début</span>
                                        <span class="label-ar" dir="rtl">تاريخ البدء</span>
                                    </label>
                                    <input type="date" id="from" name="from" class="form-control-modern" value="{{ request('from') }}">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <span class="label-fr">Date de fin</span>
                                        <span class="label-ar" dir="rtl">تاريخ الانتهاء</span>
                                    </label>
                                    <input type="date" id="to" name="to" class="form-control-modern" value="{{ request('to') }}">
                                </div>
                                <div class="col-12 d-flex align-items-end justify-content-end gap-2 mt-3">
                                    <a href="{{ route('course.list') }}" class="btn-ghost">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                        Réinitialiser
                                    </a>
                                    <button type="submit" class="btn-primary-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                        Appliquer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Courses Table Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">Liste des cours</h3>
                        <span class="header-subtitle">قائمة الدورات</span>
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

                <div class="table-container">
                    <table class="modern-table" id="coursesTable">
                        <thead>
                            <tr>
                                <th>
                                    <span class="label-fr">ID</span>
                                    <span class="label-ar" dir="rtl">المعرف</span>
                                </th>
                                <th>
                                    <span class="label-fr">Cours</span>
                                    <span class="label-ar" dir="rtl">الدورة</span>
                                </th>
                                <th>
                                    <span class="label-fr">Salle</span>
                                    <span class="label-ar" dir="rtl">القاعة</span>
                                </th>
                                <th>
                                    <span class="label-fr">Date début</span>
                                    <span class="label-ar" dir="rtl">تاريخ البدء</span>
                                </th>
                                <th>
                                    <span class="label-fr">Date fin</span>
                                    <span class="label-ar" dir="rtl">تاريخ الانتهاء</span>
                                </th>
                                <th class="text-end">
                                    <span class="label-fr">Actions</span>
                                    <span class="label-ar" dir="rtl">الإجراءات</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($courses as $course)
                                <tr id="did{{ $course->id }}">
                                    <td data-label="ID">
                                        <span class="id-badge">#{{ $course->id }}</span>
                                    </td>
                                    <td data-label="Cours">
                                        <div class="course-cell">
                                            <div class="course-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                                            </div>
                                            <div class="course-info">
                                                <span class="course-name">{{ $course->name }}</span>
                                                <span class="course-desc">{{ \Illuminate\Support\Str::limit($course->description ?? '', 50) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Salle">
                                        @if ($course->gym)
                                            <span class="badge-modern badge-primary">{{ $course->gym->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td data-label="Début">
                                        <div class="date-cell">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ optional($course->start_date)->format('d M Y') ?? '—' }}
                                        </div>
                                    </td>
                                    <td data-label="Fin">
                                        <div class="date-cell">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ optional($course->end_date)->format('d M Y') ?? '—' }}
                                        </div>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="actions-cell">
                                            <a href="{{ route('participation.create', ['course_id' => $course->id]) }}" class="action-btn action-add" title="Inscrire un membre | تسجيل عضو">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                            </a>
                                            <a href="{{ route('participation.list', ['course_id' => $course->id]) }}" class="action-btn action-list" title="Participations | المشاركات">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            </a>
                                            <a href="{{ route('course.show', $course->id) }}" class="action-btn action-view" title="Voir | عرض">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            <a href="{{ route('course.edit', $course->id) }}" class="action-btn action-edit" title="Modifier | تعديل">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </a>
                                            <button type="button" class="action-btn action-delete" onclick="deleteCourse({{ $course->id }})" title="Supprimer | حذف">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                                            <h4>Aucun cours trouvé</h4>
                                            <p>لا توجد دورات</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($courses, 'links'))
                    <div class="pagination-container">
                        {{ $courses->links('pagination::bootstrap-5') }}
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
        <p>Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.</p>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="closeModal()">Annuler</button>
            <button class="btn-danger-modern" id="confirmDeleteBtn">Supprimer</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Sélectionner...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush

<script>
let pendingDeleteId = null;

function deleteCourse(id) {
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
    
    fetch('/cours/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(function(response) {
        if (!response.ok) throw new Error('Request failed');
        const row = document.getElementById('did' + id);
        if (row) {
            row.style.animation = 'fadeOutRow 0.3s ease forwards';
            setTimeout(() => row.remove(), 300);
        }
        if (document.querySelectorAll('#coursesTable tbody tr').length === 0) {
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
.stat-success .stat-icon { background: var(--success-light); color: var(--success); }
.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
.stat-label { font-size: 0.8125rem; color: var(--gray-500); font-weight: 500; }

/* Filter Card */
.filter-card { overflow: visible; }
.filter-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; background: var(--gray-50); border-bottom: 1px solid var(--gray-200); cursor: pointer; transition: var(--transition); }
.filter-header:hover { background: var(--gray-100); }
.filter-title { font-weight: 600; color: var(--gray-700); }
.collapse-icon { color: var(--gray-400); transition: transform 0.3s ease; }
.filter-header[aria-expanded="false"] .collapse-icon { transform: rotate(-90deg); }
.filter-body { padding: 1.5rem; }

/* Form Elements */
.form-label-modern { display: flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem; }
.form-control-modern, .form-select-modern { width: 100%; padding: 0.625rem 0.875rem; font-size: 0.875rem; color: var(--gray-900); background: white; border: 1px solid var(--gray-300); border-radius: var(--radius-md); transition: var(--transition); }
.form-control-modern:focus, .form-select-modern:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }

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

/* Course Cell */
.course-cell { display: flex; align-items: center; gap: 0.75rem; }
.course-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: var(--primary-bg); color: var(--primary); border-radius: var(--radius-sm); }
.course-info { display: flex; flex-direction: column; }
.course-name { font-weight: 600; color: var(--gray-900); font-size: 0.875rem; }
.course-desc { font-size: 0.75rem; color: var(--gray-500); }

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
    
    .actions-cell {
        justify-content: flex-end;
        width: 100%;
    }
}
</style>
@endpush
@endsection
