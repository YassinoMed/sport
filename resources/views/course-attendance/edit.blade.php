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
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar" dir="rtl">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('course.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Cours</span>
                                    <span class="label-ar" dir="rtl">الدورات</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('course-attendance.index', $course->id) }}" class="breadcrumb-link">
                                    <span class="label-fr">Présence</span>
                                    <span class="label-ar" dir="rtl">الحضور</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Modifier</span>
                                <span class="label-ar" dir="rtl">تعديل</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-edit"></i>
                        <span class="label-fr">Modifier les présences</span>
                        <span class="label-ar" dir="rtl">تعديل الحضور</span>
                    </h1>
                    <p class="page-subtitle">
                        {{ $course->name }} - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('course-attendance.show', [$course->id, $date]) }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour aux détails</span>
                        <span class="label-ar" dir="rtl">عودة للتفاصيل</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger modern-alert mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle mr-3 fa-lg"></i>
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('course-attendance.update', [$course->id, $date]) }}" method="POST" id="attendanceForm">
                @csrf
                @method('PUT')

                <!-- Informations de la séance -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="stat-card stat-card-primary h-100">
                            <div class="stat-card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-value" style="font-size: 1.25rem;">{{ $course->name }}</h3>
                                    <p class="stat-label">
                                        <span class="label-fr">Cours</span>
                                        <span class="label-ar" dir="rtl">الدورة</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card stat-card-info h-100">
                            <div class="stat-card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-value" style="font-size: 1.25rem;">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>
                                    <p class="stat-label">
                                        <span class="label-fr">{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd') }}</span>
                                        <span class="label-ar" dir="rtl">{{ \Carbon\Carbon::parse($date)->locale('ar')->isoFormat('dddd') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                        @if($participations->isEmpty())
                            <div class="modern-card p-5 text-center mb-4">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash fa-4x text-muted mb-4"></i>
                                    <h4 class="font-weight-bold">
                                        <span class="label-fr">Aucun participant inscrit</span>
                                        <span class="label-ar" dir="rtl">لا يوجد مشاركون مسجلون</span>
                                    </h4>
                                    <p class="text-muted mb-4">
                                        <span class="label-fr">Il n'y a aucun participant inscrit à ce cours pour le moment.</span>
                                        <span class="label-ar" dir="rtl">لا يوجد مشاركون مسجلون في هذه الدورة حالياً.</span>
                                    </p>
                                    <a href="{{ route('participations.create') }}" class="btn-primary-modern">
                                        <i class="fas fa-plus"></i>
                                        <span class="label-fr">Ajouter des participants</span>
                                        <span class="label-ar" dir="rtl">إضافة مشاركين</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Actions et Filtres -->
                            <div class="modern-card mb-4">
                                <div class="modern-card-body p-3">
                                    <div class="row align-items-end g-3">
                                        <div class="col-lg-4">
                                            <label class="form-label">
                                                <span class="label-fr">Actions rapides</span>
                                                <span class="label-ar" dir="rtl">إجراءات سريعة</span>
                                            </label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <button type="button" class="btn-success-soft btn-sm px-3" onclick="markAll('present')">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <span class="label-fr">Tous présents</span>
                                                    <span class="label-ar" dir="rtl">الكل حاضر</span>
                                                </button>
                                                <button type="button" class="btn-danger-soft btn-sm px-3" onclick="markAll('absent')">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    <span class="label-fr">Tous absents</span>
                                                    <span class="label-ar" dir="rtl">الكل غائب</span>
                                                </button>
                                                <button type="button" class="btn-warning-soft btn-sm px-3" onclick="markAll('excused')">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    <span class="label-fr">Tous excusés</span>
                                                    <span class="label-ar" dir="rtl">الكل معذور</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label">
                                                <span class="label-fr">Filtrer par</span>
                                                <span class="label-ar" dir="rtl">تصفية حسب</span>
                                            </label>
                                            <select id="filterType" class="form-control select2">
                                                <option value="none">
                                                    <span class="label-fr">Tous les participants</span>
                                                    <span class="label-ar" dir="rtl">جميع المشاركين</span>
                                                </option>
                                                <option value="category">
                                                    <span class="label-fr">Par Catégorie</span>
                                                    <span class="label-ar" dir="rtl">حسب الفئة</span>
                                                </option>
                                                <option value="group">
                                                    <span class="label-fr">Par Groupe</span>
                                                    <span class="label-ar" dir="rtl">حسب المجموعة</span>
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-6" id="filterValueContainer" style="display:none;">
                                            <label class="form-label invisible">Value</label>
                                            <select id="categoryFilter" class="form-control select2 filter-value" style="display:none;">
                                                <option value="">Toutes les catégories</option>
                                                @php
                                                    $categories = $participations->pluck('user.category')->filter()->unique('id')->sortBy('name');
                                                @endphp
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            
                                            <select id="groupFilter" class="form-control select2 filter-value" style="display:none;">
                                                <option value="">Tous les groupes</option>
                                                @php
                                                    $groups = $participations->pluck('user.groups')->flatten()->unique('id')->sortBy('name');
                                                @endphp
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-12">
                                            <label class="form-label">
                                                <span class="label-fr">Rechercher</span>
                                                <span class="label-ar" dir="rtl">بحث</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                                                </div>
                                                <input type="text" id="participantSearch" class="form-control border-left-0" placeholder="Chercher un athlète...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Liste des participants -->
                            <div class="modern-card">
                                <div class="modern-card-header">
                                    <h5 class="modern-card-title">
                                        <i class="fas fa-users mr-2"></i>
                                        <span class="label-fr">Liste d'appel</span>
                                        <span class="label-ar" dir="rtl">قائمة المناداة</span>
                                    </h5>
                                </div>
                                <div class="modern-card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="px-4">
                                                        <span class="label-fr">Athlète</span>
                                                        <span class="label-ar" dir="rtl">اللاعب</span>
                                                    </th>
                                                    <th class="d-none d-md-table-cell">
                                                        <span class="label-fr">Catégorie</span>
                                                        <span class="label-ar" dir="rtl">الفئة</span>
                                                    </th>
                                                    <th style="width: 250px;">
                                                        <span class="label-fr">Statut présence</span>
                                                        <span class="label-ar" dir="rtl">حالة الحضور</span>
                                                    </th>
                                                    <th class="d-none d-md-table-cell px-4">
                                                        <span class="label-fr">Notes</span>
                                                        <span class="label-ar" dir="rtl">ملاحظات</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($participations as $index => $participation)
                                                    @php
                                                        $existingAttendance = $participation->courseAttendances->first();
                                                        $currentStatus = $existingAttendance ? $existingAttendance->status : 'present';
                                                        $currentNotes = $existingAttendance ? $existingAttendance->notes : '';
                                                    @endphp
                                                    <tr data-name="{{ $participation->user->name }}" 
                                                        data-email="{{ $participation->user->email ?? '' }}" 
                                                        data-category="{{ $participation->user->category_id ?? '' }}" 
                                                        data-groups="{{ optional($participation->user)->groups ? $participation->user->groups->pluck('id')->join(',') : '' }}">
                                                        <td class="px-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm avatar-placeholder mr-3">
                                                                    {{ strtoupper(substr($participation->user->name ?? 'U', 0, 1)) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold text-dark">{{ $participation->user->name }}</div>
                                                                    <div class="small text-muted">
                                                                        <span class="label-fr">Inscrit le</span>
                                                                        <span class="label-ar" dir="rtl">مسجل في</span>
                                                                        {{ \Carbon\Carbon::parse($participation->participation_date)->format('d/m/Y') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="attendances[{{ $index }}][participation_id]" value="{{ $participation->id }}">
                                                        </td>
                                                        <td class="d-none d-md-table-cell py-3">
                                                            @if($participation->user->category)
                                                                <span class="badge badge-light border">{{ $participation->user->category->name }}</span>
                                                            @else
                                                                <span class="text-muted small">—</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                                                <label class="btn btn-outline-success btn-sm flex-fill {{ $currentStatus == 'present' ? 'active' : '' }}">
                                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                                                    <i class="fas fa-check mr-1"></i>
                                                                    <span class="label-fr">Présent</span>
                                                                    <span class="label-ar" dir="rtl">حاضر</span>
                                                                </label>
                                                                <label class="btn btn-outline-danger btn-sm flex-fill {{ $currentStatus == 'absent' ? 'active' : '' }}">
                                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                                                    <i class="fas fa-times mr-1"></i>
                                                                    <span class="label-fr">Absent</span>
                                                                    <span class="label-ar" dir="rtl">غائب</span>
                                                                </label>
                                                                <label class="btn btn-outline-warning btn-sm flex-fill {{ $currentStatus == 'excused' ? 'active' : '' }}">
                                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="excused" {{ $currentStatus == 'excused' ? 'checked' : '' }}>
                                                                    <i class="fas fa-exclamation mr-1"></i>
                                                                    <span class="label-fr">Justifié</span>
                                                                    <span class="label-ar" dir="rtl">مبرر</span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="d-none d-md-table-cell px-4 py-3">
                                                            <textarea class="form-control form-control-modern" 
                                                                      name="attendances[{{ $index }}][notes]" 
                                                                      rows="1" 
                                                                      placeholder="Notes..."
                                                                      maxlength="500">{{ old('attendances.'.$index.'.notes', $currentNotes) }}</textarea>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Résumé et Actions -->
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="modern-card bg-light border-0 shadow-none">
                                        <div class="modern-card-body py-2 px-3">
                                            <div class="d-flex justify-content-between text-center">
                                                <div class="px-2">
                                                    <div class="small text-muted mb-1">
                                                        <span class="label-fr">Présents</span>
                                                        <span class="label-ar" dir="rtl">حاضرون</span>
                                                    </div>
                                                    <div class="h5 mb-0 text-success font-weight-bold" id="present-count">0</div>
                                                </div>
                                                <div class="px-2 border-left border-right">
                                                    <div class="small text-muted mb-1">
                                                        <span class="label-fr">Absents</span>
                                                        <span class="label-ar" dir="rtl">غائبون</span>
                                                    </div>
                                                    <div class="h5 mb-0 text-danger font-weight-bold" id="absent-count">0</div>
                                                </div>
                                                <div class="px-2 border-right">
                                                    <div class="small text-muted mb-1">
                                                        <span class="label-fr">Justifiés</span>
                                                        <span class="label-ar" dir="rtl">معذورون</span>
                                                    </div>
                                                    <div class="h5 mb-0 text-warning font-weight-bold" id="excused-count">0</div>
                                                </div>
                                                <div class="px-2">
                                                    <div class="small text-muted mb-1">
                                                        <span class="label-fr">Total</span>
                                                        <span class="label-ar" dir="rtl">المجموع</span>
                                                    </div>
                                                    <div class="h5 mb-0 text-primary font-weight-bold">{{ $participations->count() }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('course-attendance.show', [$course->id, $date]) }}" class="btn-ghost">
                                            <i class="fas fa-times"></i>
                                            <span class="label-fr">Annuler</span>
                                            <span class="label-ar" dir="rtl">إلغاء</span>
                                        </a>
                                        <button type="submit" class="btn-primary-modern px-5">
                                            <i class="fas fa-save mr-2"></i>
                                            <span class="label-fr">Enregistrer les modifications</span>
                                            <span class="label-ar" dir="rtl">حفظ التعديلات</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem; }
    .avatar-placeholder { background-color: #e9ecef; color: #495057; }
    .form-control-modern { border-radius: 8px; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; transition: all 0.2s; }
    .form-control-modern:focus { border-color: #4e73df; box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1); }
    .btn-group-toggle .btn { border-radius: 6px !important; margin: 0 2px; border: 1px solid #dee2e6; color: #6c757d; background: #fff; }
    .btn-group-toggle .btn:hover { background: #f8f9fa; }
    .btn-group-toggle .btn.active { border-color: transparent !important; color: #fff !important; }
    .btn-group-toggle .btn.active.btn-outline-success { background-color: #28a745 !important; }
    .btn-group-toggle .btn.active.btn-outline-danger { background-color: #dc3545 !important; }
    .btn-group-toggle .btn.active.btn-outline-warning { background-color: #ffc107 !important; color: #212529 !important; }
    
    .btn-success-soft { background-color: #e8f5e9; color: #2e7d32; border: none; border-radius: 6px; transition: all 0.2s; }
    .btn-success-soft:hover { background-color: #2e7d32; color: #fff; }
    .btn-danger-soft { background-color: #ffebee; color: #c62828; border: none; border-radius: 6px; transition: all 0.2s; }
    .btn-danger-soft:hover { background-color: #c62828; color: #fff; }
    .btn-warning-soft { background-color: #fff8e1; color: #f9a825; border: none; border-radius: 6px; transition: all 0.2s; }
    .btn-warning-soft:hover { background-color: #f9a825; color: #fff; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialiser Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        minimumResultsForSearch: Infinity
    });

    // Mettre à jour les compteurs en temps réel
    function updateCounts() {
        let presentCount = $('input[name*="[status]"]:checked').filter(function() {
            return $(this).val() === 'present';
        }).length;
        
        let absentCount = $('input[name*="[status]"]:checked').filter(function() {
            return $(this).val() === 'absent';
        }).length;
        
        let excusedCount = $('input[name*="[status]"]:checked').filter(function() {
            return $(this).val() === 'excused';
        }).length;
        
        $('#present-count').text(presentCount);
        $('#absent-count').text(absentCount);
        $('#excused-count').text(excusedCount);
    }

    // Écouter les changements de statut
    $(document).on('change', 'input[name*="[status]"]', function() {
        updateCounts();
    });

    // Fonctions pour les actions rapides
    window.markAll = function(status) {
        $('input[name*="[status]"][value="' + status + '"]').each(function() {
            $(this).prop('checked', true).closest('label').addClass('active').siblings().removeClass('active');
        });
        updateCounts();
    };

    // Function to filter participants by search and category
    function filterParticipants() {
        var searchQuery = $('#participantSearch').val().toLowerCase();
        var filterType = $('#filterType').val();
        
        // Gérer l'affichage des filtres
        $('#filterValueContainer').toggle(filterType !== 'none');
        $('#categoryFilter').parent().toggle(filterType === 'category');
        $('#groupFilter').parent().toggle(filterType === 'group');

        var selectedCategory = $('#categoryFilter').val();
        var selectedGroup = $('#groupFilter').val();
        
        $('tbody tr').each(function() {
            var $row = $(this);
            var name = (String($row.data('name')) || '').toLowerCase();
            var email = (String($row.data('email')) || '').toLowerCase();
            var category = String($row.data('category') || '');
            var groups = String($row.data('groups') || '').split(',');
            
            var matchesSearch = !searchQuery || name.indexOf(searchQuery) !== -1 || email.indexOf(searchQuery) !== -1;
            var matchesFilter = true;

            if (filterType === 'category' && selectedCategory) {
                matchesFilter = category === selectedCategory;
            } else if (filterType === 'group' && selectedGroup) {
                matchesFilter = groups.includes(selectedGroup);
            }
            
            if (matchesSearch && matchesFilter) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    }
    
    $('#participantSearch').on('input', filterParticipants);
    $('#filterType').on('change', function() {
        // Reset specific filters when changing type
        $('#categoryFilter').val('').trigger('change');
        $('#groupFilter').val('').trigger('change');
        filterParticipants();
    });
    $('#categoryFilter, #groupFilter').on('change', filterParticipants);

    // Initialiser les compteurs
    
    // Initialiser depuis les paramètres URL si présents (venant de l'index)
    const urlParams = new URLSearchParams(window.location.search);
    const filterType = urlParams.get('filter_type');
    const filterValue = urlParams.get('filter_value');

    if (filterType && filterValue) {
        $('#filterType').val(filterType).trigger('change');
        if (filterType === 'category') {
            $('#categoryFilter').val(filterValue).trigger('change');
        } else if (filterType === 'group') {
            $('#groupFilter').val(filterValue).trigger('change');
        }
    } else {
        updateCounts();
    }
});
</script>
@endpush