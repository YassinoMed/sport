@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.roles_permissions_management') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('messages.edit_role') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.edit_role_title') }} <span class="text-capitalize">{{ $role->name }}</span></h1>
                    <p class="page-subtitle">{{ __('messages.edit_role_subtitle') }}</p>
                </div>
                <div>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-3"></i>
                        <ul class="mb-0 list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('roles.update', $role) }}" method="POST" id="editRoleForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Informations du Rôle -->
                    <div class="col-lg-4 mb-4">
                        <div class="modern-card h-100">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-info-circle text-primary me-2"></i>{{ __('messages.details') }}
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">{{ __('messages.role_name') }} <span class="text-danger">*</span></label>
                                    @if($role->name === 'admin')
                                        <input type="text" 
                                               class="form-input-modern bg-light" 
                                               value="{{ $role->name }}"
                                               readonly
                                               disabled>
                                        <p class="text-warning small mt-2 mb-0">
                                            <i class="fas fa-lock me-1"></i>
                                            {{ __('messages.role_admin_protected') }}
                                        </p>
                                    @else
                                        <input type="text" 
                                               class="form-input-modern @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $role->name) }}"
                                               placeholder="{{ __('messages.role_name_placeholder') }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">{{ __('messages.statistics') }}</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="badge-status badge-primary p-2 flex-grow-1 text-center">
                                            <i class="fas fa-key me-1"></i>{{ $role->permissions->count() }} {{ __('messages.permissions') }}
                                        </div>
                                        <div class="badge-status badge-info p-2 flex-grow-1 text-center">
                                            <i class="fas fa-users me-1"></i>{{ $role->users()->count() }} {{ __('messages.users') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-light p-3 rounded-3">
                                    <h6 class="fw-bold small text-uppercase mb-2 text-primary">{{ __('messages.quick_actions') }}</h6>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary text-start" id="selectAll">
                                            <i class="fas fa-check-double me-2"></i>{{ __('messages.select_all') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary text-start" id="deselectAll">
                                            <i class="fas fa-times me-2"></i>{{ __('messages.deselect_all') }}
                                        </button>
                                    </div>
                                </div>

                                @if(in_array($role->name, ['admin', 'cityManager', 'gymManager', 'coach', 'user']))
                                    <div class="alert alert-info mt-4 mb-0" style="border-radius: 12px; font-size: 0.85rem;">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        {{ __('messages.system_role_warning') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sélection des Permissions -->
                    <div class="col-lg-8 mb-4">
                        <div class="modern-card">
                            <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h3 class="card-title-modern">
                                        <i class="fas fa-key text-primary me-2"></i>{{ __('messages.permissions') }}
                                    </h3>
                                    <p class="text-muted small mb-0">{{ __('messages.edit_access_for_role') }}</p>
                                </div>
                                <div class="search-wrapper">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" id="permissionSearch" class="form-input-modern ps-5" placeholder="{{ __('messages.search') }}">
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                @foreach($groupedPermissions as $groupName => $groupPerms)
                                    <div class="permission-group-section mb-5" data-group-name="{{ $groupName }}">
                                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                            <h4 class="h6 fw-bold text-uppercase mb-0 d-flex align-items-center">
                                                <span class="bg-primary-soft p-2 rounded me-3">
                                                    <i class="fas fa-folder text-primary"></i>
                                                </span>
                                                {{ __($groupName) }}
                                            </h4>
                                            <button type="button" class="btn btn-link btn-sm text-decoration-none select-group" data-group="{{ Str::slug($groupName) }}" data-select-text="{{ __('messages.select_group') }}" data-deselect-text="{{ __('messages.deselect_group') }}">
                                                {{ __('messages.select_group') }}
                                            </button>
                                        </div>
                                        
                                        <div class="row">
                                            @foreach($groupPerms as $permission)
                                                <div class="col-md-6 mb-3 permission-item-wrapper">
                                                    <div class="permission-checkbox-card {{ in_array($permission->id, $rolePermissions) ? 'active' : '' }}">
                                                        <div class="form-check custom-checkbox">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}" 
                                                                   id="perm_{{ $permission->id }}"
                                                                   data-group="{{ Str::slug($groupName) }}"
                                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                            <label class="form-check-label d-block cursor-pointer" for="perm_{{ $permission->id }}">
                                                                <span class="d-block fw-bold text-dark">
                                                                    {{ \App\Http\Controllers\RolePermissionController::getPermissionLabel($permission->name) }}
                                                                </span>
                                                                <code class="text-muted small">{{ $permission->name }}</code>
                                                            </label>
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
                </div>

                <!-- Footer Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="modern-card p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-muted mb-0 d-none d-md-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ __('messages.edit_role_footer_note') }}
                                </p>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('roles.index') }}" class="btn btn-light px-4">
                                        {{ __('messages.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                        <i class="fas fa-save me-2"></i>{{ __('messages.save_changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<style>
    .permission-checkbox-card {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.2s ease;
        height: 100%;
    }
    .permission-checkbox-card:hover {
        background: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .permission-checkbox-card.active {
        border-left: 4px solid var(--primary-color);
        background: rgba(102, 126, 234, 0.02);
    }
    .custom-checkbox .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.25rem;
        cursor: pointer;
    }
    .custom-checkbox .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .bg-primary-soft { background-color: rgba(102, 126, 234, 0.1); }
    .search-wrapper { position: relative; min-width: 300px; }
    .search-icon { position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #9CA3AF; }
    .cursor-pointer { cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélection globale
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
            cb.checked = true;
            cb.closest('.permission-checkbox-card').classList.add('active');
        });
    });
    
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
            cb.checked = false;
            cb.closest('.permission-checkbox-card').classList.remove('active');
        });
    });
    
    // Sélection par groupe
    document.querySelectorAll('.select-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const group = this.dataset.group;
            const checkboxes = document.querySelectorAll(`input[data-group="${group}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                if(cb.checked) cb.closest('.permission-checkbox-card').classList.add('active');
                else cb.closest('.permission-checkbox-card').classList.remove('active');
            });
            this.textContent = allChecked ? this.dataset.selectText : this.dataset.deselectText;
        });
    });

    // Toggle active class on check
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
        cb.addEventListener('change', function() {
            if(this.checked) this.closest('.permission-checkbox-card').classList.add('active');
            else this.closest('.permission-checkbox-card').classList.remove('active');
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
            item.style.display = text.includes(term) ? 'block' : 'none';
        });

        sections.forEach(section => {
            const visibleItems = section.querySelectorAll('.permission-item-wrapper[style="display: block;"]');
            const allItems = section.querySelectorAll('.permission-item-wrapper');
            
            if (term === '') {
                section.style.display = 'block';
                return;
            }

            section.style.display = (visibleItems.length === 0 && allItems.length > 0) ? 'none' : 'block';
        });
    });
});
</script>
@endsection
