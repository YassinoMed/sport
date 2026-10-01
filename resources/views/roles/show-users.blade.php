@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">{{ __('messages.role_users_list', ['role' => $role->name]) }}</h1>
                    <p class="page-subtitle">{{ __('messages.all_users') }} {{ __('messages.assigned_to') }} {{ $role->name }}</p>
                </div>
                <div>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="modern-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-uppercase small fw-bold text-muted">{{ __('messages.name') }}</th>
                                    <th class="py-3 border-0 text-uppercase small fw-bold text-muted">{{ __('messages.email') }}</th>
                                    <th class="py-3 border-0 text-uppercase small fw-bold text-muted">{{ __('messages.city') }}</th>
                                    <th class="py-3 border-0 text-uppercase small fw-bold text-muted">{{ __('messages.gym') }}</th>
                                    <th class="pe-4 py-3 border-0 text-uppercase small fw-bold text-muted text-end">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted">{{ $user->email }}</td>
                                        <td class="py-3">
                                            <span class="badge-status badge-light">
                                                <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                                {{ $user->city ? $user->city->name : '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge-status badge-light">
                                                <i class="fas fa-dumbbell me-1 text-success"></i>
                                                {{ $user->gym ? $user->gym->name : '-' }}
                                            </span>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-icon" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                                    <li>
                                                        <a class="dropdown-item py-2" href="{{ route('moderators.edit', $user) }}">
                                                            <i class="fas fa-edit text-primary me-2"></i>{{ __('messages.edit') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users-slash fa-3x mb-3 opacity-20"></i>
                                                <p class="mb-0">{{ __('messages.no_users_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<style>
    .bg-primary-soft { background-color: rgba(102, 126, 234, 0.1); }
    .avatar-sm { font-size: 1rem; }
    .table-hover tbody tr:hover { background-color: #F9FAFB; }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: #6B7280;
    }
    .btn-icon:hover { background: #F3F4F6; color: var(--primary-color); }
</style>
@endsection
