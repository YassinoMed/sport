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
                            <li class="breadcrumb-item active">{{ __('messages.new_role') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.create_new_role') }}</h1>
                    <p class="page-subtitle">{{ __('messages.create_role_desc') }}</p>
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

            <form action="{{ route('roles.store') }}" method="POST" id="createRoleForm">
                @csrf
                
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
                                    <input type="text" 
                                           class="form-input-modern @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           placeholder="{{ __('messages.role_name_placeholder') }}"
                                           required>
                                    <p class="text-muted small mt-2">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        {{ __('messages.role_name_hint') }}
                                    </p>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                            </div>
                        </div>
                    </div>

                    <!-- Sélection des Permissions -->
                    <div class="col-lg-8 mb-4">
                        <div class="modern-card">
                            <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h3 class="card-title-modern">
                                        <i class="fas fa-key text-primary me-2"></i>Permissions / الصلاحيات
                                    </h3>
                                    <p class="text-muted small mb-0">Sélectionnez les accès pour ce rôle</p>
                                </div>
                                <div class="search-wrapper">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" id="permissionSearch" class="form-input-modern ps-5" placeholder="Rechercher... / بحث...">
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
                                                {{ $groupName }}
                                            </h4>
                                            <button type="button" class="btn btn-link btn-sm text-decoration-none select-group" data-group="{{ Str::slug($groupName) }}">
                                                Sélectionner le groupe / تحديد المجموعة
                                            </button>
                                        </div>
                                        
                                        <div class="row">
                                            @foreach($groupPerms as $permission)
                                                <div class="col-md-6 mb-3 permission-item-wrapper">
                                                    <div class="permission-checkbox-card">
                                                        <div class="form-check custom-checkbox">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}" 
                                                                   id="perm_{{ $permission->id }}"
                                                                   data-group="{{ Str::slug($groupName) }}"
                                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
                                    Assurez-vous de vérifier les permissions avant de valider.
                                </p>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('roles.index') }}" class="btn btn-light px-4">
                                        Annuler / إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                        <i class="fas fa-save me-2"></i>Créer le Rôle / إنشاء الدور
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
        document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = true);
    });
    
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
    });
    
    // Sélection par groupe
    document.querySelectorAll('.select-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const group = this.dataset.group;
            const checkboxes = document.querySelectorAll(`input[data-group="${group}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Sélectionner le groupe' : 'Désélectionner le groupe';
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
