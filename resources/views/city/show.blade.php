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
                                <span class="label-fr">Détails de la ville</span>
                                <span class="label-ar">تفاصيل المدينة</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-city me-2 text-primary"></i>
                        <span class="label-fr">Détails de {{ $cityData->name }}</span>
                        <span class="label-ar" dir="rtl">تفاصيل {{ $cityData->name }}</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('city.edit', $cityData->id) }}" class="btn-primary-modern">
                        <i class="fas fa-edit me-2"></i>
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar">تعديل</span>
                    </a>
                    <a href="{{ route('city.list') }}" class="btn-ghost shadow-sm bg-white">
                        <i class="fas fa-arrow-left me-2"></i>
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
            <!-- Stats Grid -->
            <div class="stats-grid mb-4">
                <!-- Revenue -->
                <div class="stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $revenueInDollars }}</span>
                        <span class="stat-label">
                            <span class="label-fr">Revenu Total</span>
                            <span class="label-ar">إجمالي الإيرادات</span>
                        </span>
                    </div>
                </div>

                <!-- Gyms -->
                <div class="stat-card stat-danger">
                    <div class="stat-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $gyms }}</span>
                        <span class="stat-label">
                            <span class="label-fr">Salles</span>
                            <span class="label-ar">القاعات</span>
                        </span>
                    </div>
                </div>

                <!-- Gym Managers -->
                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $gymsManagers }}</span>
                        <span class="stat-label">
                            <span class="label-fr">Managers de Salles</span>
                            <span class="label-ar">مدراء القاعات</span>
                        </span>
                    </div>
                </div>

                <!-- Coaches -->
                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-user-ninja"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $coaches }}</span>
                        <span class="stat-label">
                            <span class="label-fr">Coachs</span>
                            <span class="label-ar">المدربين</span>
                        </span>
                    </div>
                </div>

                <!-- Users -->
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $users }}</span>
                        <span class="stat-label">
                            <span class="label-fr">Utilisateurs</span>
                            <span class="label-ar">المستخدمين</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- City Manager Detail Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon-container">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="header-text">
                                <h3 class="header-title">
                                    <span class="label-fr">Gestionnaire de la Ville</span>
                                    <span class="label-ar">مدير المدينة</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if ($citiesManagers == null)
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-user-slash text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h4 class="text-muted">
                                        <span class="label-fr">Cette ville n'a pas encore de gestionnaire</span>
                                        <span class="label-ar">هذه المدينة ليس لها مدير بعد</span>
                                    </h4>
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-4 flex-wrap flex-md-nowrap">
                                    <div class="manager-avatar-large">
                                        <img src="{{ $citiesManagers->profile_image ?? asset('imgs/avatar.png') }}" 
                                             alt="{{ $citiesManagers->name }}"
                                             class="rounded-circle shadow-sm"
                                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid var(--gray-100);">
                                    </div>
                                    <div class="manager-info">
                                        <h2 class="fw-bold mb-1">{{ $citiesManagers->name }}</h2>
                                        <p class="text-muted mb-3">
                                            <i class="fas fa-envelope me-2"></i> {{ $citiesManagers->email }}
                                        </p>
                                        <div class="d-flex gap-3">
                                            <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill">
                                                ID: #{{ $citiesManagers->id }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
