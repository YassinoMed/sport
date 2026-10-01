@extends('layouts.user-layout')

@section('title', 'Liste des Participations')

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
                                    <span class="label-ar" dir="rtl">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Participations</span>
                                <span class="label-ar" dir="rtl">المشاركات</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-users"></i>
                        <span class="label-fr">Gestion des participations</span>
                        <span class="label-ar" dir="rtl">إدارة المشاركات</span>
                    </h1>
                    <p class="page-subtitle">
                        <span class="label-fr">Liste des inscriptions aux cours</span>
                        <span class="label-ar" dir="rtl">قائمة التسجيلات في الدورات</span>
                    </p>
                </div>
                <a href="{{ route('participation.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus"></i>
                    <span class="label-fr">Nouvelle participation</span>
                    <span class="label-ar" dir="rtl">مشاركة جديدة</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert-modern alert-success mb-4">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-modern alert-error mb-4">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Stats/Filters Card -->
            <div class="modern-card mb-4">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div>
                        <h3 class="header-title">
                            <span class="label-fr">Filtres de recherche</span>
                            <span class="label-ar" dir="rtl">فلاتر البحث</span>
                        </h3>
                        <p class="header-subtitle mb-0">
                            <span class="label-fr">Affiner la liste des participations</span>
                            <span class="label-ar" dir="rtl">تصفية قائمة المشاركات</span>
                        </p>
                    </div>
                </div>
                <div class="card-body-modern">
                    <form method="GET" action="{{ route('participation.list') }}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Cours</span>
                                        <span class="label-ar" dir="rtl">الدورة</span>
                                    </label>
                                    <select name="course_id" class="form-select-modern select2">
                                        <option value="">
                                            <span class="label-fr">Tous les cours</span>
                                            <span class="label-ar" dir="rtl">كل الدورات</span>
                                        </option>
                                        @foreach(\App\Models\Course::orderBy('name')->get() as $c)
                                            <option value="{{ $c->id }}" {{ ($filters['course_id'] ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Utilisateur</span>
                                        <span class="label-ar" dir="rtl">المستخدم</span>
                                    </label>
                                    <select name="user_id" class="form-select-modern select2">
                                        <option value="">
                                            <span class="label-fr">Tous les utilisateurs</span>
                                            <span class="label-ar" dir="rtl">كل المستخدمين</span>
                                        </option>
                                        @foreach(\App\Models\User::role('user')->orderBy('name')->limit(100)->get() as $u)
                                            <option value="{{ $u->id }}" {{ ($filters['user_id'] ?? '') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2 pb-3">
                                <button type="submit" class="btn-primary-modern w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('participation.list') }}" class="btn-ghost" title="Réinitialiser">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-primary">
                        <i class="fas fa-list"></i>
                    </div>
                    <div>
                        <h3 class="header-title">
                            <span class="label-fr">Participations</span>
                            <span class="label-ar" dir="rtl">المشاركات</span>
                        </h3>
                        <span class="header-subtitle">
                            {{ $participations->total() }}
                            <span class="label-fr">participations trouvées</span>
                            <span class="label-ar" dir="rtl">مشاركات وجدت</span>
                        </span>
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>
                                    <span class="label-fr">Athlète</span>
                                    <span class="label-ar" dir="rtl">الرياضي</span>
                                </th>
                                <th>
                                    <span class="label-fr">Cours</span>
                                    <span class="label-ar" dir="rtl">الدورة</span>
                                </th>
                                <th>
                                    <span class="label-fr">Statut</span>
                                    <span class="label-ar" dir="rtl">الحالة</span>
                                </th>
                                <th>
                                    <span class="label-fr">Date</span>
                                    <span class="label-ar" dir="rtl">التاريخ</span>
                                </th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($participations as $participation)
                                <tr>
                                    <td data-label="ID"><span class="id-badge">#{{ $participation->id }}</span></td>
                                    <td data-label="Athlète">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $participation->user_name }}</span>
                                            <small class="text-muted">{{ $participation->user_email }}</small>
                                        </div>
                                    </td>
                                    <td data-label="Cours">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $participation->course_name }}</span>
                                            <small class="text-muted">{{ Str::limit($participation->course_description, 30) }}</small>
                                        </div>
                                    </td>
                                    <td data-label="Statut">
                                        @switch($participation->status)
                                            @case('registered')
                                                <span class="badge-modern badge-info">
                                                    <span class="label-fr">Inscrit</span>
                                                    <span class="label-ar" dir="rtl">مسجل</span>
                                                </span>
                                                @break
                                            @case('attended')
                                            @case('completed')
                                                <span class="badge-modern badge-success">
                                                    <span class="label-fr">Terminé</span>
                                                    <span class="label-ar" dir="rtl">مكتمل</span>
                                                </span>
                                                @break
                                            @case('absent')
                                            @case('cancelled')
                                                <span class="badge-modern badge-danger">
                                                    <span class="label-fr">Annulé</span>
                                                    <span class="label-ar" dir="rtl">ملغي</span>
                                                </span>
                                                @break
                                            @default
                                                <span class="badge-modern badge-secondary">{{ $participation->status }}</span>
                                        @endswitch
                                    </td>
                                    <td data-label="Date">
                                        <div class="date-cell">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $participation->participated_at ? \Carbon\Carbon::parse($participation->participated_at)->format('d/m/Y') : '—' }}
                                        </div>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="actions-cell">
                                            <a href="{{ route('participation.show', $participation->id) }}" class="btn-icon btn-icon-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('participation.edit', $participation->id) }}" class="btn-icon btn-icon-success" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('participation.destroy', $participation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">
                                                <span class="label-fr">Aucune participation trouvée.</span>
                                                <span class="label-ar" dir="rtl">لم يتم العثور على مشاركات.</span>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer-modern">
                    {{ $participations->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Sélectionner...",
            allowClear: true,
            width: '100%'
        });

        // Masquer automatiquement les alertes après 5 secondes
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
<style>
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
