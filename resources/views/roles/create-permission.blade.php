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
                            <li class="breadcrumb-item active">{{ __('messages.new_permission') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.create_new_permission') }}</h1>
                    <p class="page-subtitle">{{ __('messages.add_new_access_rule') }}</p>
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

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">
                                <i class="fas fa-plus-circle text-primary me-2"></i>{{ __('messages.permission_details') }}
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('permissions.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">{{ __('messages.permission_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-input-modern @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           placeholder="{{ __('messages.permission_name_placeholder') }}"
                                           required>
                                    <p class="text-muted small mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('messages.permission_name_hint') }}
                                    </p>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="bg-light p-4 rounded-3 mb-4">
                                    <h6 class="fw-bold small text-uppercase mb-3 text-primary">
                                        <i class="fas fa-lightbulb me-2"></i>{{ __('messages.naming_conventions') }}
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <code class="bg-white px-2 py-1 rounded">view_*</code>
                                                <span class="small text-muted">{{ __('messages.read_only') }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <code class="bg-white px-2 py-1 rounded">create_*</code>
                                                <span class="small text-muted">{{ __('messages.creation') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <code class="bg-white px-2 py-1 rounded">edit_*</code>
                                                <span class="small text-muted">{{ __('messages.modification') }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <code class="bg-white px-2 py-1 rounded">manage_*</code>
                                                <span class="small text-muted">{{ __('messages.full_access') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                                    <a href="{{ route('roles.index') }}" class="btn btn-light px-4">
                                        {{ __('messages.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                        <i class="fas fa-save me-2"></i>{{ __('messages.create_permission_button') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4" style="border-radius: 12px; border: none; background: rgba(102, 126, 234, 0.05);">
                        <div class="d-flex">
                            <i class="fas fa-info-circle text-primary me-3 mt-1"></i>
                            <div>
                                <p class="fw-bold mb-1 small text-primary">{{ __('messages.important_note') }}</p>
                                <p class="mb-0 small text-muted">{{ __('messages.permission_post_creation_note') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
