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
                            <li class="breadcrumb-item">
                                <a href="{{ route('city.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Villes</span>
                                    <span class="label-ar">المدن</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Villes supprimées</span>
                                <span class="label-ar">المدن المحذوفة</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-trash-restore me-2 text-danger"></i>
                        <span class="label-fr">Villes supprimées</span>
                        <span class="label-ar" dir="rtl">المدن المحذوفة</span>
                    </h1>
                </div>
                <a href="{{ route('city.list') }}" class="btn-ghost shadow-sm bg-white">
                    <i class="fas fa-arrow-left me-2"></i>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon-container bg-danger-light text-danger">
                        <i class="fas fa-city"></i>
                    </div>
                    <div class="header-text">
                        <h2 class="header-title">
                            <span class="label-fr">Archives des villes</span>
                            <span class="label-ar">أرشيف المدن</span>
                        </h2>
                        <p class="header-subtitle">
                            <span class="label-fr">Liste des villes supprimées qui peuvent être restaurées</span>
                            <span class="label-ar">قائمة المدن المحذوفة التي يمكن استعادتها</span>
                        </p>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="modern-table table-hover" id="proj">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">ID</th>
                                    <th>
                                        <span class="label-fr">Nom de la ville</span>
                                        <span class="label-ar">اسم المدينة</span>
                                    </th>
                                    <th>
                                        <span class="label-fr">Supprimé le</span>
                                        <span class="label-ar">تاريخ الحذف</span>
                                    </th>
                                    <th class="text-end" style="width: 150px;">
                                        <span class="label-fr">Actions</span>
                                        <span class="label-ar">الإجراءات</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($deletedCity as $city)
                                    <tr id="did{{ $city->id }}">
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark rounded-pill">#{{ $city->id }}</span>
                                        </td>
                                        <td class="fw-bold">{{ $city->name }}</td>
                                        <td class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $city->deleted_at->format('d - M - Y') }}
                                        </td>
                                        <td class="text-end">
                                            <button onclick="restoredCity({{ $city->id }})" 
                                                    class="btn-icon-modern btn-success-modern" 
                                                    title="Restaurer">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                            <h5 class="text-muted">
                                                <span class="label-fr">Aucune ville supprimée trouvée</span>
                                                <span class="label-ar">لا توجد مدن محذوفة</span>
                                            </h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function restoredCity(id) {
        if (confirm("Voulez-vous vraiment restaurer cette ville ?")) {
            $.ajax({
                url: '/cities/restored/' + id,
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $("#did" + id).fadeOut(400, function() {
                        $(this).remove();
                        if ($('tbody tr').length === 0) {
                            location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    alert("Une erreur s'est produite lors de la restauration.");
                }
            });
        }
    }
</script>
@endsection
