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
                                <a href="{{ route('TrainingSessions.listSessions') }}" class="breadcrumb-link">
                                    <span class="label-fr">Séances</span>
                                    <span class="label-ar" dir="rtl">الحصص</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Détails</span>
                                <span class="label-ar" dir="rtl">تفاصيل</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Détails de la séance</span>
                        <span class="label-ar" dir="rtl">تفاصيل الحصة</span>
                        <span class="id-badge">#{{ $trainingSession->id }}</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('TrainingSessions.edit_training_session', $trainingSession->id) }}" class="btn-warning-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar" dir="rtl">تعديل</span>
                    </a>
                    <a href="{{ route('TrainingSessions.listSessions') }}" class="btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar" dir="rtl">عودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Session Info Card -->
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Informations de la séance</span>
                                    <span class="label-ar" dir="rtl">معلومات الحصة</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body-modern p-4">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Nom de la séance</span>
                                        <span class="label-ar" dir="rtl">اسم الحصة</span>
                                    </div>
                                    <div class="info-value text-primary fw-bold fs-5">
                                        {{ $trainingSession->name }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Jour de la semaine</span>
                                        <span class="label-ar" dir="rtl">يوم الأسبوع</span>
                                    </div>
                                    <div class="info-value">
                                        <span class="badge bg-soft-info text-info px-3 py-2 rounded-pill fs-6">
                                            <i class="far fa-calendar-alt me-2"></i>
                                            {{ $trainingSession->day }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Heure de début</span>
                                        <span class="label-ar" dir="rtl">وقت البداية</span>
                                    </div>
                                    <div class="info-value">
                                        <div class="d-flex align-items-center text-dark fw-semibold fs-5">
                                            <i class="far fa-clock me-2 text-primary"></i>
                                            {{ \Carbon\Carbon::parse($trainingSession->starts_at)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Heure de fin</span>
                                        <span class="label-ar" dir="rtl">وقت النهاية</span>
                                    </div>
                                    <div class="info-value">
                                        <div class="d-flex align-items-center text-dark fw-semibold fs-5">
                                            <i class="far fa-clock me-2 text-danger"></i>
                                            {{ \Carbon\Carbon::parse($trainingSession->finishes_at)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats/Quick Info Column -->
                <div class="col-lg-4">
                    <div class="mini-stat-card stat-sessions mb-4">
                        <div class="mini-stat-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="mini-stat-content">
                            <span class="mini-stat-value">
                                <span class="label-fr">Actif</span>
                                <span class="label-ar" dir="rtl">نشط</span>
                            </span>
                            <span class="mini-stat-label">
                                <span class="label-fr">Statut de la séance</span>
                                <span class="label-ar" dir="rtl">حالة الحصة</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="modern-card p-4">
                        <h4 class="mb-3 fw-bold">
                            <span class="label-fr">Actions rapides</span>
                            <span class="label-ar" dir="rtl">إجراءات سريعة</span>
                        </h4>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-danger w-100 py-2 d-flex align-items-center justify-content-center gap-2" onclick="showDeleteModal({{ $trainingSession->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                <span class="label-fr">Supprimer la séance</span>
                                <span class="label-ar" dir="rtl">حذف الحصة</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modern-modal">
    <div class="modal-content-modern">
        <div class="modal-icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </div>
        <h3 class="modal-title">
            <span class="label-fr">Confirmer la suppression</span>
            <span class="label-ar" dir="rtl">تأكيد الحذف</span>
        </h3>
        <p class="modal-text">
            <span class="label-fr">Voulez-vous vraiment supprimer cette séance ? Cette action est irréversible.</span>
            <span class="label-ar" dir="rtl">هل تريد حقًا حذف هذه الحصة؟ هذا الإجراء غير قابل للتراجع.</span>
        </p>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-ghost">
                <span class="label-fr">Annuler</span>
                <span class="label-ar" dir="rtl">إلغاء</span>
            </button>
            <button id="confirmDeleteBtn" class="btn-danger-modern">
                <span class="label-fr">Supprimer</span>
                <span class="label-ar" dir="rtl">حذف</span>
            </button>
        </div>
    </div>
</div>

<script>
let sessionToDelete = null;

function showDeleteModal(id) {
    sessionToDelete = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    sessionToDelete = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!sessionToDelete) return;
    
    $.ajax({
        url: '/TrainingSessions/' + sessionToDelete,
        type: 'DELETE',
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.success) {
                window.location.href = "{{ route('TrainingSessions.listSessions') }}";
            } else {
                alert("Impossible de supprimer la séance");
            }
        },
        error: function() {
            alert("Une erreur est survenue lors de la suppression.");
        }
    });
});
</script>
@endsection
