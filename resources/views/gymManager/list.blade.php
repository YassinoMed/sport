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
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Managers</span>
                                <span class="label-ar" dir="rtl">المدراء</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span class="label-fr">Gestion des Managers</span>
                        <span class="label-ar" dir="rtl">تسيير المدراء</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('gymManager.create') }}" class="btn-primary-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span class="label-fr">Nouveau Manager</span>
                        <span class="label-ar" dir="rtl">مدير جديد</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert-modern alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="modern-card shadow-sm">
                <div class="card-header-modern">
                    <div class="header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/><path d="M12 14c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">
                            <span class="label-fr">Liste des Managers</span>
                            <span class="label-ar" dir="rtl">قائمة المدراء</span>
                        </h3>
                        <p class="header-subtitle">
                            <span class="label-fr">{{ $users->total() }} managers enregistrés</span>
                            <span class="label-ar" dir="rtl">{{ $users->total() }} مدراء مسجلين</span>
                        </p>
                    </div>
                </div>

                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px">ID</th>
                                <th>
                                    <span class="label-fr">Manager</span>
                                    <span class="label-ar" dir="rtl">المدير</span>
                                </th>
                                <th>
                                    <span class="label-fr">Téléphone</span>
                                    <span class="label-ar" dir="rtl">الهاتف</span>
                                </th>
                                <th class="text-center">
                                    <span class="label-fr">National ID</span>
                                    <span class="label-ar" dir="rtl">رقم البطاقة</span>
                                </th>
                                <th class="text-center" style="width: 200px">
                                    <span class="label-fr">Actions</span>
                                    <span class="label-ar" dir="rtl">إجراءات</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr id="did{{ $user->id }}">
                                    <td class="text-center" data-label="{{ __('messages.id') }}">
                                        <span class="user-id-badge">#{{ $user->id }}</span>
                                    </td>
                                    <td data-label="{{ __('messages.gym_manager') }}">
                                        <div class="user-info-cell">
                                            <div class="user-avatar-wrapper">
                                                <img src="{{ asset($user->profile_image) }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="user-avatar-sm"
                                                     onerror="this.src='{{ asset('dist/img/avatar5.png') }}'">
                                            </div>
                                            <div class="user-details">
                                                <span class="user-name">{{ $user->name }}</span>
                                                <span class="user-email text-muted">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="{{ __('messages.phone') }}">
                                        <div class="phone-cell">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                            {{ $user->phone }}
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="{{ __('messages.national_id') }}">
                                        <span class="badge-national">{{ $user->national_id }}</span>
                                    </td>
                                    <td class="text-center" data-label="{{ __('messages.actions') }}">
                                        <div class="action-buttons-wrapper">
                                            <a href="{{ route('gymManager.show', $user->id) }}" class="btn-action btn-view" title="Voir / عرض">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            <a href="{{ route('gymManager.edit', $user->id) }}" class="btn-action btn-edit" title="Modifier / تعديل">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </a>
                                            <button onclick="deletegymManager({{ $user->id }})" class="btn-action btn-delete" title="Supprimer / حذف">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                            <button onclick="banUser({{ $user->id }})" class="btn-action btn-ban" title="Bannir / حظر">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-table-cell">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-20"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            <p class="mb-0">
                                                <span class="label-fr">Aucun manager trouvé</span>
                                                <span class="label-ar" dir="rtl">لم يتم العثور على مدراء</span>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="card-footer-modern">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-bg: #eef2ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-900: #111827;
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    margin: 0;
}

.breadcrumb-link {
    color: var(--gray-500);
    text-decoration: none;
    transition: var(--transition);
}

.breadcrumb-link:hover {
    color: var(--primary);
}

.label-ar {
    font-family: 'Cairo', sans-serif;
    font-size: 0.85em;
    opacity: 0.9;
}

.label-fr {
    display: inline-block;
}

/* Modern Card */
.modern-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid var(--gray-200);
}

.card-header-modern {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 1px solid var(--gray-100);
}

.header-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-bg);
    color: var(--primary);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-title {
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
    color: var(--gray-900);
}

.header-subtitle {
    font-size: 0.875rem;
    color: var(--gray-500);
    margin: 0;
}

/* Table */
.table-container {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table th {
    background: var(--gray-50);
    padding: 1rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--gray-200);
    text-align: left;
}

.modern-table td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.modern-table tbody tr:hover {
    background: var(--gray-50);
}

/* User Info Cell */
.user-info-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    box-shadow: var(--shadow-sm);
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: var(--gray-900);
}

.user-email {
    font-size: 0.8125rem;
}

.user-id-badge {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--primary);
    background: var(--primary-bg);
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
}

.phone-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-600);
    font-size: 0.875rem;
}

.badge-national {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: var(--gray-100);
    color: var(--gray-700);
    font-weight: 500;
    font-size: 0.8125rem;
    border-radius: 6px;
}

/* Actions */
.action-buttons-wrapper {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.btn-action {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    transition: var(--transition);
    text-decoration: none;
}

.btn-view { background: var(--primary-bg); color: var(--primary); }
.btn-view:hover { background: var(--primary); color: white; }

.btn-edit { background: var(--warning-light); color: var(--warning); }
.btn-edit:hover { background: var(--warning); color: white; }

.btn-delete { background: var(--danger-light); color: var(--danger); }
.btn-delete:hover { background: var(--danger); color: white; }

.btn-ban { background: var(--gray-100); color: var(--gray-700); }
.btn-ban:hover { background: var(--gray-700); color: white; }

.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4);
}

.btn-primary-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.5);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--gray-400);
}

.card-footer-modern {
    padding: 1.25rem;
    border-top: 1px solid var(--gray-100);
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

    .user-info-cell {
        justify-content: flex-end;
        width: 100%;
    }

    .action-buttons-wrapper {
        justify-content: flex-end;
        width: 100%;
    }

    .phone-cell {
        justify-content: flex-end;
        width: 100%;
    }
}
</style>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
@endpush

@push('scripts')
<script>
    function banUser(id) {
        Swal.fire({
            title: '<span class="label-fr">Êtes-vous sûr ?</span><br><span class="label-ar" dir="rtl">هل أنت متأكد؟</span>',
            html: '<span class="label-fr">Voulez-vous bannir ce manager ?</span><br><span class="label-ar" dir="rtl">هل تريد حظر هذا المدير؟</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1f2937',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: '<span class="label-fr">Oui, bannir</span> / <span class="label-ar">نعم، حظر</span>',
            cancelButtonText: '<span class="label-fr">Annuler</span> / <span class="label-ar">إلغاء</span>'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/banUser/' + id,
                    type: 'GET',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        $("#did" + id).fadeOut(500, function() { $(this).remove(); });
                        Swal.fire({
                            title: '<span class="label-fr">Banni !</span>',
                            html: '<span class="label-fr">Le manager a été banni.</span>',
                            icon: 'success'
                        });
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }

    function deletegymManager(id) {
        Swal.fire({
            title: '<span class="label-fr">Êtes-vous sûr ?</span><br><span class="label-ar" dir="rtl">هل أنت متأكد؟</span>',
            html: '<span class="label-fr">Cette action est irréversible !</span><br><span class="label-ar" dir="rtl">هذا الإجراء غير قابل للتراجع!</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: '<span class="label-fr">Oui, supprimer</span> / <span class="label-ar">نعم، حذف</span>',
            cancelButtonText: '<span class="label-fr">Annuler</span> / <span class="label-ar">إلغاء</span>'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/gymManager/' + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        $("#did" + id).fadeOut(500, function() { $(this).remove(); });
                        Swal.fire({
                            title: '<span class="label-fr">Supprimé !</span>',
                            html: '<span class="label-fr">Le manager a été supprimé.</span>',
                            icon: 'success'
                        });
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
