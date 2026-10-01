@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">{{ __('messages.roles_permissions_management') }}</h1>
                    <p class="page-subtitle">{{ __('messages.manage_roles_permissions') }}</p>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>{{ __('messages.new_role') }}
                    </a>
                    <a href="{{ route('permissions.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-key me-2"></i>{{ __('messages.new_permission') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-3"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Barre de recherche pour les rôles -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card p-3">
                        <div class="search-wrapper w-100">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="roleSearch" class="form-input-modern ps-5" placeholder="{{ __('messages.search_role') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rôles existants -->
            <div class="row mb-5" id="rolesList">
                @foreach($roles as $role)
                    <div class="col-lg-4 col-md-6 mb-4 role-item-wrapper">
                        <div class="modern-card h-100 role-card">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="role-icon-wrapper {{ $role->name === 'admin' ? 'admin' : ($role->name === 'coach' ? 'coach' : 'default') }}">
                                        @if($role->name === 'admin')
                                            <i class="fas fa-crown"></i>
                                        @elseif($role->name === 'cityManager')
                                            <i class="fas fa-city"></i>
                                        @elseif($role->name === 'gymManager')
                                            <i class="fas fa-dumbbell"></i>
                                        @elseif($role->name === 'coach')
                                            <i class="fas fa-user-ninja"></i>
                                        @elseif($role->name === 'user')
                                            <i class="fas fa-user"></i>
                                        @else
                                            <i class="fas fa-users-cog"></i>
                                        @endif
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-icon" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('roles.edit', $role) }}">
                                                    <i class="fas fa-edit text-primary me-2"></i>{{ __('messages.edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('roles.users', $role) }}">
                                                    <i class="fas fa-users text-success me-2"></i>{{ __('messages.view_users') }}
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('roles.clone', $role) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2">
                                                        <i class="fas fa-copy text-info me-2"></i>{{ __('messages.clone') }}
                                                    </button>
                                                </form>
                                            </li>
                                            @if(!in_array($role->name, ['admin', 'cityManager', 'gymManager', 'coach', 'user']))
                                                <li><hr class="dropdown-divider opacity-50"></li>
                                                <li>
                                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" 
                                                          onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                                            <i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <h3 class="h5 fw-bold mb-1 text-capitalize">{{ $role->name }}</h3>
                                <p class="text-muted small mb-4">{{ __('messages.permissions_count', ['count' => $role->permissions->count()]) }}</p>

                                <div class="permissions-preview mb-4">
                                    @forelse($role->permissions->take(5) as $permission)
                                        <span class="badge-status badge-info mb-2 me-1" style="font-size: 0.75rem;">
                                            {{ \App\Http\Controllers\RolePermissionController::getPermissionLabel($permission->name) }}
                                        </span>
                                    @empty
                                        <span class="text-muted small italic">{{ __('messages.no_permissions') }}</span>
                                    @endforelse
                                    @if($role->permissions->count() > 5)
                                        <span class="badge-status badge-secondary mb-2" style="font-size: 0.75rem;">
                                            +{{ $role->permissions->count() - 5 }}
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary w-100 mt-auto">
                                    <i class="fas fa-cog me-2"></i>{{ __('messages.configure') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="modern-card">
                <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="card-title-modern">{{ __('messages.all_permissions') }}</h3>
                        <p class="text-muted small mb-0">{{ __('messages.permissions_by_category') }}</p>
                    </div>
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="permissionSearch" class="form-input-modern ps-5" placeholder="{{ __('messages.search_permission') }}">
                    </div>
                </div>
                <div class="card-body p-4">
                    @foreach($groupedPermissions as $groupName => $groupPerms)
                        <div class="permission-group-section mb-5" data-group-name="{{ $groupName }}">
                            <h4 class="h6 fw-bold text-uppercase letter-spacing-1 mb-4 d-flex align-items-center">
                                <span class="bg-primary-soft p-2 rounded me-3">
                                    <i class="fas fa-folder text-primary"></i>
                                </span>
                                {{ __($groupName) }}
                            </h4>
                            <div class="row">
                                @foreach($groupPerms as $permission)
                                    <div class="col-xl-4 col-lg-6 mb-3 permission-item-wrapper">
                                        <div class="permission-item-modern p-3 rounded border">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <span class="d-block fw-bold text-dark mb-1">{{ \App\Http\Controllers\RolePermissionController::getPermissionLabel($permission->name) }}</span>
                                                    <code class="text-muted small">{{ $permission->name }}</code>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge-status badge-light" title="{{ __('messages.used_by_roles', ['count' => $permission->roles->count()]) }}">
                                                        {{ $permission->roles->count() }} <i class="fas fa-users ms-1"></i>
                                                    </span>
                                                    @if($permission->roles->count() === 0)
                                                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" 
                                                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger p-0" title="{{ __('messages.delete') }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .role-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .role-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }
    .role-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .role-icon-wrapper.admin { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white; }
    .role-icon-wrapper.coach { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; }
    .role-icon-wrapper.default { background: var(--primary-gradient); color: white; }

    .permission-item-modern {
        background: #F9FAFB;
        border-color: #E5E7EB !important;
        transition: all 0.2s ease;
    }
    .permission-item-modern:hover {
        background: white;
        border-color: var(--primary-color) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .bg-primary-soft { background-color: rgba(102, 126, 234, 0.1); }
    .letter-spacing-1 { letter-spacing: 1px; }
    
    .search-wrapper { position: relative; min-width: 300px; }
    .search-icon { position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #9CA3AF; }

    .btn-icon {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: #6B7280;
    }
    .btn-icon:hover { background: #F3F4F6; color: var(--primary-color); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche de rôles
    const roleSearchInput = document.getElementById('roleSearch');
    const roleItems = document.querySelectorAll('.role-item-wrapper');

    roleSearchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        roleItems.forEach(item => {
            const roleName = item.querySelector('h3').textContent.toLowerCase();
            if (roleName.includes(term)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Recherche de permissions
    const searchInput = document.getElementById('permissionSearch');
    const permissionItems = document.querySelectorAll('.permission-item-wrapper');
    const sections = document.querySelectorAll('.permission-group-section');

    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();

        permissionItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(term)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        // Masquer les sections vides
        sections.forEach(section => {
            const visibleItems = section.querySelectorAll('.permission-item-wrapper[style="display: block;"]');
            const allItems = section.querySelectorAll('.permission-item-wrapper');
            
            // Si le terme est vide, on réaffiche tout
            if (term === '') {
                section.style.display = 'block';
                return;
            }

            if (visibleItems.length === 0 && allItems.length > 0) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
    });
});
</script>
@endsection
