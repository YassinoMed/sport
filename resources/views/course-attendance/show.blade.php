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
                                <a href="{{ route('course-attendance.index', $course->id) }}" class="breadcrumb-link">
                                    <span class="label-fr">Présence</span>
                                    <span class="label-ar" dir="rtl">الحضور</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Détails de la séance</span>
                                <span class="label-ar" dir="rtl">تفاصيل الحصة</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-calendar-check"></i>
                        <span class="label-fr">Séance du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                        <span class="label-ar" dir="rtl">حصة يوم {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                    </h1>
                    <p class="page-subtitle">{{ $course->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('course-attendance.edit', [$course->id, $date]) }}" class="btn-primary-modern">
                        <i class="fas fa-edit"></i>
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar" dir="rtl">تعديل</span>
                    </a>
                    <a href="{{ route('course-attendance.index', $course->id) }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar" dir="rtl">عودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($attendances->isEmpty())
            <div class="modern-card p-5 text-center">
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                    <h4 class="font-weight-bold">
                        <span class="label-fr">Aucune présence enregistrée</span>
                        <span class="label-ar" dir="rtl">لا يوجد حضور مسجل</span>
                    </h4>
                    <p class="text-muted mb-4">
                        <span class="label-fr">Aucune présence n'a été enregistrée pour cette date.</span>
                        <span class="label-ar" dir="rtl">لم يتم تسجيل أي حضور لهذا التاريخ.</span>
                    </p>
                    <a href="{{ route('course-attendance.create', $course->id) }}" class="btn-primary-modern">
                        <i class="fas fa-plus"></i>
                        <span class="label-fr">Marquer les présences</span>
                        <span class="label-ar" dir="rtl">تسجيل الحضور</span>
                    </a>
                </div>
            </div>
        @else
            @php
                $presentCount = $attendances->where('status', 'present')->count();
                $absentCount = $attendances->where('status', 'absent')->count();
                $excusedCount = $attendances->where('status', 'excused')->count();
                $totalCount = $attendances->count();
                $presentPercentage = $totalCount > 0 ? round(($presentCount / $totalCount) * 100, 1) : 0;
            @endphp

            <!-- Statistiques Rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card stat-card-success">
                        <div class="stat-card-body">
                            <div class="stat-icon"><i class="fas fa-check"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-value">{{ $presentCount }}</h3>
                                <p class="stat-label">
                                    <span class="label-fr">Présents ({{ $presentPercentage }}%)</span>
                                    <span class="label-ar" dir="rtl">حاضرون ({{ $presentPercentage }}%)</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-danger">
                        <div class="stat-card-body">
                            <div class="stat-icon"><i class="fas fa-times"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-value">{{ $absentCount }}</h3>
                                <p class="stat-label">
                                    <span class="label-fr">Absents</span>
                                    <span class="label-ar" dir="rtl">غائبون</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-warning">
                        <div class="stat-card-body">
                            <div class="stat-icon"><i class="fas fa-exclamation"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-value">{{ $excusedCount }}</h3>
                                <p class="stat-label">
                                    <span class="label-fr">Excusés</span>
                                    <span class="label-ar" dir="rtl">معذورون</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-card-body">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-value">{{ $totalCount }}</h3>
                                <p class="stat-label">
                                    <span class="label-fr">Total</span>
                                    <span class="label-ar" dir="rtl">المجموع</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres et Recherche -->
            <div class="modern-card mb-4 bg-light border-0 shadow-none">
                <div class="modern-card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <select id="categoryFilter" class="form-control select2">
                                <option value="">
                                    🏷️ <span class="label-fr">Toutes les catégories</span>
                                    <span class="label-ar" dir="rtl">جميع الفئات</span>
                                </option>
                                @php
                                    $categories = $attendances->pluck('participation.user.category')->filter()->unique('id')->sortBy('name');
                                @endphp
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <select id="statusFilter" class="form-control select2">
                                <option value="">
                                    📊 <span class="label-fr">Tous les statuts</span>
                                    <span class="label-ar" dir="rtl">جميع الحالات</span>
                                </option>
                                <option value="present">✅ <span class="label-fr">Présents</span><span class="label-ar" dir="rtl">حاضرون</span></option>
                                <option value="absent">❌ <span class="label-fr">Absents</span><span class="label-ar" dir="rtl">غائبون</span></option>
                                <option value="excused">⚠️ <span class="label-fr">Excusés</span><span class="label-ar" dir="rtl">معذورون</span></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="participantSearch" class="form-control" placeholder="🔍 Chercher par nom... / بحث بالاسم...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des présences -->
            <div class="modern-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="attendanceTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">
                                    <span class="label-fr">Athlète</span>
                                    <span class="label-ar" dir="rtl">اللاعب</span>
                                </th>
                                <th class="d-none d-md-table-cell">
                                    <span class="label-fr">Email</span>
                                    <span class="label-ar" dir="rtl">البريد الإلكتروني</span>
                                </th>
                                <th class="text-center">
                                    <span class="label-fr">Statut</span>
                                    <span class="label-ar" dir="rtl">الحالة</span>
                                </th>
                                <th class="px-4">
                                    <span class="label-fr">Notes</span>
                                    <span class="label-ar" dir="rtl">ملاحظات</span>
                                </th>
                                <th class="px-4 text-right">
                                    <span class="label-fr">Enregistré le</span>
                                    <span class="label-ar" dir="rtl">تم التسجيل في</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances->sortBy('participation.user.name') as $attendance)
                                <tr data-name="{{ $attendance->participation->user->name }}" 
                                    data-email="{{ $attendance->participation->user->email ?? '' }}" 
                                    data-category="{{ $attendance->participation->user->category_id ?? '' }}" 
                                    data-status="{{ $attendance->status }}">
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm avatar-placeholder mr-3">
                                                {{ strtoupper(substr($attendance->participation->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $attendance->participation->user->name }}</div>
                                                <small class="text-muted">
                                                    <a href="{{ route('participations.show', $attendance->participation->id) }}" class="text-primary">
                                                        <span class="label-fr">Détails participation</span>
                                                        <span class="label-ar" dir="rtl">تفاصيل المشاركة</span>
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell text-muted small">{{ $attendance->participation->user->email }}</td>
                                    <td class="text-center">
                                        @switch($attendance->status)
                                            @case('present')
                                                <span class="badge badge-success-soft px-3 py-2">
                                                    <i class="fas fa-check mr-1"></i>
                                                    <span class="label-fr">Présent</span>
                                                    <span class="label-ar" dir="rtl">حاضر</span>
                                                </span>
                                                @break
                                            @case('absent')
                                                <span class="badge badge-danger-soft px-3 py-2">
                                                    <i class="fas fa-times mr-1"></i>
                                                    <span class="label-fr">Absent</span>
                                                    <span class="label-ar" dir="rtl">غائب</span>
                                                </span>
                                                @break
                                            @case('excused')
                                                <span class="badge badge-warning-soft px-3 py-2">
                                                    <i class="fas fa-exclamation mr-1"></i>
                                                    <span class="label-fr">Excusé</span>
                                                    <span class="label-ar" dir="rtl">معذور</span>
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-4 small">
                                        @if($attendance->notes)
                                            <span class="text-dark">{{ $attendance->notes }}</span>
                                        @else
                                            <span class="text-muted italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 text-right small text-muted">
                                        {{ $attendance->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('course-attendance.create', $course->id) }}" class="btn-primary-modern">
                                <i class="fas fa-plus"></i>
                                <span class="label-fr">Nouvelle présence</span>
                                <span class="label-ar" dir="rtl">حضور جديد</span>
                            </a>
                            <a href="{{ route('course-attendance.statistics', $course->id) }}" class="btn-ghost">
                                <i class="fas fa-chart-bar"></i>
                                <span class="label-fr">Statistiques</span>
                                <span class="label-ar" dir="rtl">الإحصائيات</span>
                            </a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash mr-2"></i>
                                <span class="label-fr">Supprimer cette séance</span>
                                <span class="label-ar" dir="rtl">حذف هذه الحصة</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de suppression caché -->
            <form id="deleteForm" action="{{ route('course-attendance.destroy', [$course->id, $date]) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action supprimera toutes les présences de cette date et est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}

$(document).ready(function() {
    // Initialiser Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Auto-hide success alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 5000);

    // Function to filter participants
    function filterParticipants() {
        var searchQuery = $('#participantSearch').val().toLowerCase();
        var selectedCategory = $('#categoryFilter').val();
        var selectedStatus = $('#statusFilter').val();
        
        $('tbody tr').each(function() {
            var $row = $(this);
            var name = (String($row.data('name')) || '').toLowerCase();
            var email = (String($row.data('email')) || '').toLowerCase();
            var category = String($row.data('category') || '');
            var status = String($row.data('status') || '');
            
            var matchesSearch = !searchQuery || name.indexOf(searchQuery) !== -1 || email.indexOf(searchQuery) !== -1;
            var matchesCategory = !selectedCategory || category === selectedCategory;
            var matchesStatus = !selectedStatus || status === selectedStatus;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    }
    
    $('#participantSearch').on('input', filterParticipants);
    $('#categoryFilter').on('change', filterParticipants);
    $('#statusFilter').on('change', filterParticipants);
});
</script>
@endpush
