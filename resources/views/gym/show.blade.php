@extends('layouts.user-layout')
@section('content')

    <!-- Content Wrapper. Contains page content -->
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
                            <li class="breadcrumb-item">
                                <a href="{{ route('gym.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Salles</span>
                                    <span class="label-ar">القاعات</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Détails</span>
                                <span class="label-ar">التفاصيل</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Détails de la salle</span>
                        <span class="label-ar">تفاصيل الصالة</span>
                        <span class="id-badge">#{{ $singleGym->id }}</span>
                    </h1>
                    <p class="page-subtitle text-primary fw-bold">{{ $singleGym->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('gym.edit', $singleGym->id) }}" class="btn-warning-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar">تعديل</span>
                    </a>
                    <a href="{{ route('gym.list') }}" class="btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar">العودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Left Column: Image -->
                <div class="col-lg-5">
                    <div class="modern-card overflow-hidden">
                        <div class="gym-image-container">
                            @if($singleGym->cover_image)
                                <img src="{{ asset($singleGym->cover_image) }}" alt="{{ $singleGym->name }}" class="img-fluid w-100 h-100 object-fit-cover" style="min-height: 400px;">
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center bg-light h-100 py-5" style="min-height: 400px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/></svg>
                                    <p class="text-muted">
                                        <span class="label-fr">Aucune image disponible</span>
                                        <span class="label-ar">لا توجد صورة متاحة</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info -->
                <div class="col-lg-7">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Informations générales</span>
                                    <span class="label-ar">معلومات عامة</span>
                                </h3>
                            </div>
                        </div>
                        
                        <div class="card-body-modern p-4">
                            <div class="info-grid">
                                <!-- ID -->
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">ID de la salle</span>
                                        <span class="label-ar">معرف الصالة</span>
                                    </div>
                                    <div class="info-value">
                                        <span class="id-badge">#{{ $singleGym->id }}</span>
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Nom de la salle</span>
                                        <span class="label-ar">اسم الصالة</span>
                                    </div>
                                    <div class="info-value text-primary fw-bold fs-5">
                                        {{ $singleGym->name }}
                                    </div>
                                </div>

                                <!-- City -->
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Ville</span>
                                        <span class="label-ar">المدينة</span>
                                    </div>
                                    <div class="info-value">
                                        @if ($singleGym->city)
                                            <span class="location-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                {{ $singleGym->city->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">
                                                <span class="label-fr">Non assignée</span>
                                                <span class="label-ar">غير محددة</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Manager -->
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Directeur de salle</span>
                                        <span class="label-ar">مدير الصالة</span>
                                    </div>
                                    <div class="info-value">
                                        @if ($singleGym->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ strtoupper(substr($singleGym->user->name, 0, 2)) }}
                                                </div>
                                                <span>{{ $singleGym->user->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small">
                                                <span class="label-fr">Aucun directeur assigné</span>
                                                <span class="label-ar">لم يتم تعيين مدير</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Created At -->
                                <div class="info-item">
                                    <div class="info-label">
                                        <span class="label-fr">Date de création</span>
                                        <span class="label-ar">تاريخ الإنشاء</span>
                                    </div>
                                    <div class="info-value text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        {{ $singleGym->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
    <!-- /.content-wrapper -->
