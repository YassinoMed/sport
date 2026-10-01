@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
        <!-- Content Header -->
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
                                    <a href="{{ route('gymManager.list') }}" class="breadcrumb-link">
                                        <span class="label-fr">Managers</span>
                                        <span class="label-ar" dir="rtl">المدراء</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span class="label-fr">Détails</span>
                                    <span class="label-ar" dir="rtl">التفاصيل</span>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
                            <span class="label-fr">Détails du Manager</span>
                            <span class="label-ar" dir="rtl">تفاصيل المدير</span>
                        </h1>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('gymManager.edit', $singleUser->id) }}" class="btn-primary-modern">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            <span class="label-fr">Modifier</span>
                            <span class="label-ar" dir="rtl">تعديل</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <!-- Profile Card -->
                        <div class="modern-card shadow-sm text-center mb-4">
                            <div class="card-body p-5">
                                <div class="profile-image-container mb-4">
                                    <img src="{{ asset($singleUser->profile_image) }}" 
                                         class="profile-img shadow-lg"
                                         onerror="this.src='{{ asset('dist/img/avatar5.png') }}'"
                                         alt="{{ $singleUser->name }}">
                                </div>
                                <h2 class="profile-name mb-1">{{ $singleUser->name }}</h2>
                                <p class="profile-role text-primary font-weight-bold">
                                    <span class="label-fr">Manager de Salle</span>
                                    <span class="label-ar" dir="rtl">مدير القاعة</span>
                                </p>
                                <div class="profile-stats mt-4 py-3 border-top border-bottom d-flex justify-content-around">
                                    <div class="stat-item">
                                        <span class="stat-value">#{{ $singleUser->id }}</span>
                                        <span class="stat-label">ID</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value text-success">Active</span>
                                        <span class="stat-label">Status</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <!-- Details Card -->
                        <div class="modern-card shadow-sm">
                            <div class="card-header-modern">
                                <div class="header-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div>
                                    <h3 class="header-title">
                                        <span class="label-fr">Informations Personnelles</span>
                                        <span class="label-ar" dir="rtl">المعلومات الشخصية</span>
                                    </h3>
                                    <p class="header-subtitle">
                                        <span class="label-fr">Détails complets du profil</span>
                                        <span class="label-ar" dir="rtl">تفاصيل الملف الكاملة</span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="card-body p-0">
                                <div class="info-list">
                                    <!-- National ID -->
                                    <div class="info-item">
                                        <div class="info-icon bg-blue-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="3" x2="21" y1="10" y2="10"/><line x1="7" x2="7" y1="15" y2="15"/><line x1="12" x2="12" y1="15" y2="15"/><line x1="17" x2="17" y1="15" y2="15"/></svg>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">
                                                <span class="label-fr">ID National</span>
                                                <span class="label-ar" dir="rtl">الرقم الوطني</span>
                                            </span>
                                            <span class="info-value font-weight-bold">{{ $singleUser->national_id }}</span>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="info-item">
                                        <div class="info-icon bg-green-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">
                                                <span class="label-fr">Téléphone</span>
                                                <span class="label-ar" dir="rtl">رقم الهاتف</span>
                                            </span>
                                            <span class="info-value font-weight-bold">{{ $singleUser->phone }}</span>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="info-item">
                                        <div class="info-icon bg-yellow-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning"><path d="M22 17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10Z"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">
                                                <span class="label-fr">Email</span>
                                                <span class="label-ar" dir="rtl">البريد الإلكتروني</span>
                                            </span>
                                            <span class="info-value font-weight-bold text-muted">{{ $singleUser->email }}</span>
                                        </div>
                                    </div>

                                    <!-- Joined Date -->
                                    <div class="info-item border-0">
                                        <div class="info-icon bg-purple-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">
                                                <span class="label-fr">Date d'inscription</span>
                                                <span class="label-ar" dir="rtl">تاريخ التسجيل</span>
                                            </span>
                                            <span class="info-value font-weight-bold">{{ $singleUser->created_at ? $singleUser->created_at->format('d/m/Y') : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer-modern bg-light border-top">
                                <a href="{{ route('gymManager.list') }}" class="btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                    <span class="label-fr">Retour à la liste</span>
                                    <span class="label-ar" dir="rtl">الرجوع للقائمة</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
        }
        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .profile-role {
            font-size: 0.9375rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .stat-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
        }
        .info-list {
            display: flex;
            flex-direction: column;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            transition: var(--transition);
        }
        .info-item:hover {
            background: var(--gray-50);
        }
        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .info-content {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .info-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-500);
        }
        .info-value {
            font-size: 1rem;
            color: var(--gray-900);
        }
        /* Helper Colors */
        .bg-blue-light { background: #eff6ff; }
        .bg-green-light { background: #f0fdf4; }
        .bg-yellow-light { background: #fffbeb; }
        .bg-purple-light { background: #f5f3ff; }
        .text-purple { color: #8b5cf6; }
    </style>
    @endpush
<!-- /.content-wrapper -->
@endsection

