@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">{{ __('messages.staff_management') }}</h1>
                    <p class="page-subtitle">{{ __('messages.manage_moderators_admins') }}</p>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-tag me-2"></i>{{ __('messages.roles_permissions') }}
                    </a>
                    <a href="{{ route('staff.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>{{ __('messages.new_staff') }}
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

            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('staff.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">{{ __('messages.search') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0" placeholder="{{ __('messages.search_staff_placeholder') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">{{ __('messages.role') }}</label>
                            <select name="role" class="form-select bg-light border-0">
                                <option value="">{{ __('messages.all_roles') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-filter me-2"></i>{{ __('messages.apply_filters') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small fw-bold text-uppercase">{{ __('messages.staff_member') }}</th>
                                    <th class="py-3 border-0 text-muted small fw-bold text-uppercase">{{ __('messages.role') }}</th>
                                    <th class="py-3 border-0 text-muted small fw-bold text-uppercase">{{ __('messages.location') }}</th>
                                    <th class="py-3 border-0 text-muted small fw-bold text-uppercase text-center">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staff as $member)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-wrapper me-3">
                                                    <img src="{{ asset($member->profile_image ?: 'imgs/defaultImg.jpg') }}" alt="" class="rounded-circle shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $member->name }}</h6>
                                                    <span class="text-muted small">{{ $member->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($member->roles as $role)
                                                <span class="badge rounded-pill bg-primary-soft text-primary px-3 py-1 fw-600">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($member->hasRole('cityManager'))
                                                <span class="text-muted small"><i class="fas fa-city me-1"></i>{{ $member->city?->name ?: 'N/A' }}</span>
                                            @elseif($member->hasRole('gymManager'))
                                                <span class="text-muted small"><i class="fas fa-dumbbell me-1"></i>{{ $member->gym?->name ?: 'N/A' }}</span>
                                            @else
                                                <span class="text-muted small text-uppercase">Global</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3 pe-4">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('staff.edit', $member) }}" class="btn btn-icon btn-sm btn-soft-primary" title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($member->id !== auth()->id())
                                                    <form action="{{ route('staff.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-sm btn-soft-danger" title="{{ __('messages.delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                                                <p class="mb-0">{{ __('messages.no_staff_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($staff->hasPages())
                    <div class="card-footer bg-white border-0 p-4">
                        {{ $staff->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<style>
    .btn-soft-primary {
        background-color: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }
    .btn-soft-primary:hover {
        background-color: #6366f1;
        color: white;
    }
    .btn-soft-danger {
        background-color: rgba(244, 63, 94, 0.1);
        color: #f43f5e;
    }
    .btn-soft-danger:hover {
        background-color: #f43f5e;
        color: white;
    }
    .bg-primary-soft {
        background-color: rgba(99, 102, 241, 0.1);
    }
</style>
@endsection
