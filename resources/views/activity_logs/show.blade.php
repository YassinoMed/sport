@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('activity-logs.index') }}" class="breadcrumb-link">Logs</a></li>
                        <li class="breadcrumb-item active">Détails du log</li>
                    </ol>
                </nav>
                <h1 class="page-title">Détails de l'Activité #{{ $log->id }}</h1>
                <p class="page-subtitle">تفاصيل النشاط</p>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">Informations Générales</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Utilisateur</label>
                                <span class="fw-bold">{{ $log->user_name }}</span>
                                @if($log->user)
                                    <span class="d-block text-muted small">{{ $log->user->email }}</span>
                                @endif
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Action</label>
                                <span class="fw-bold">{{ $log->action }}</span>
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Date & Heure</label>
                                <span>{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">IP Adresse</label>
                                <code>{{ $log->ip_address }}</code>
                            </div>
                            <div class="detail-group mb-0">
                                <label class="text-muted small d-block">Navigateur</label>
                                <span class="small">{{ $log->user_agent }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">Détails de la Requête</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">URL Complète</label>
                                <code class="d-block p-2 bg-light rounded">{{ $log->url }}</code>
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Méthode</label>
                                <span class="badge-status {{ in_array($log->method, ['POST', 'PUT', 'DELETE']) ? 'badge-danger' : 'badge-info' }}">
                                    {{ $log->method }}
                                </span>
                            </div>
                            <div class="detail-group mb-0">
                                <label class="text-muted small d-block">Données (Payload)</label>
                                <pre class="p-3 bg-dark text-light rounded mt-2" style="max-height: 400px; overflow-y: auto;">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
