@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Accueil</a></li>
                            <li class="breadcrumb-item active">Logs d'activité</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">Logs d'Activité du Système</h1>
                    <p class="page-subtitle">سجل نشاط النظام</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-9">
                            <label for="search" class="form-label-modern">Recherche / بحث</label>
                            <input type="text" id="search" name="search" class="form-input-modern" placeholder="Utilisateur, Action, IP, URL..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn-primary-modern flex-grow-1">Filtrer</button>
                            <a href="{{ route('activity-logs.index') }}" class="btn-ghost" title="Réinitialiser">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>IP</th>
                                    <th>Méthode</th>
                                    <th class="text-end">Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <span class="text-muted d-block small">{{ $log->created_at->format('d/m/Y') }}</span>
                                            {{ $log->created_at->format('H:i:s') }}
                                        </td>
                                        <td>
                                            <span class="fw-bold d-block">{{ $log->user_name }}</span>
                                            @if($log->user)
                                                <span class="text-muted small">ID: #{{ $log->user_id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $log->action }}">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td><code>{{ $log->ip_address }}</code></td>
                                        <td>
                                            <span class="badge-status {{ in_array($log->method, ['POST', 'PUT', 'DELETE']) ? 'badge-danger' : 'badge-info' }}">
                                                {{ $log->method }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('activity-logs.show', $log->id) }}" class="btn-icon action-view" title="Voir les détails">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Aucune activité enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($logs->hasPages())
                    <div class="card-footer-modern">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<style>
.badge-status { padding: 0.35rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 600; }
.badge-danger { background: rgba(245, 101, 101, 0.1); color: #f56565; }
.badge-info { background: rgba(66, 153, 225, 0.1); color: #4299e1; }
.action-view { background: rgba(66, 153, 225, 0.1); color: #4299e1; }
.btn-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; transition: all 0.2s; }
.btn-icon:hover { transform: scale(1.1); }
</style>
@endsection
