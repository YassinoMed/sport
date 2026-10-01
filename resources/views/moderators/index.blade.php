@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">{{ __('messages.moderation_accounts') }}</h1>
                    <p class="page-subtitle">{{ __('messages.manage_moderators') }}</p>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-tag me-2"></i>{{ __('messages.roles_permissions') }}
                    </a>
                    <a href="{{ route('moderators.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>{{ __('messages.new_moderator') }}
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

            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('moderators.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">{{ __('messages.search') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0" placeholder="{{ __('messages.search_placeholder') }}">
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
                                    <th class="ps-4 py-3 border-0 text-muted small fw-bold text-uppercase">{{ __('messages.name') }}</th>
                                    <th class="py-3 border-0 text-muted small fw-bold text-uppercase">{{ __('messages.role') }}</th>
                                    <th class="py-3 border-0 text-muted small fw-bold text-uppercase">{{ __('messages.email') }}</th>
                                    <th class="py-3 border-0 text-muted small fw-bold text-uppercase text-center">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($moderators as $moderator)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-wrapper me-3">
                                                    <div class="avatar-circle bg-primary-light text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($moderator->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $moderator->name }}</h6>
                                                    <span class="text-muted small">ID: #{{ $moderator->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($moderator->roles as $role)
                                                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill small">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>{{ $moderator->email }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('moderators.edit', $moderator->id) }}" class="btn btn-sm btn-outline-info" title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('moderators.destroy', $moderator->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('messages.delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                                <p class="mb-0">{{ __('messages.no_data') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($moderators->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $moderators->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-primary-light { background-color: #e7f1ff; }
</style>
@endsection
