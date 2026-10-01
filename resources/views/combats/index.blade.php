@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-4">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 fw-bold">Combats</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end m-0">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Combats</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-light border-0 px-4 py-3">
                            <h3 class="card-title mb-0 fw-semibold">Liste des combats</h3>
                            <a href="{{ route('combats.create') }}" class="btn btn-primary btn-sm float-end">
                                <i class="fas fa-plus me-1"></i>Ajouter
                            </a>
                        </div>
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Athlète A</th>
                                            <th>Athlète B</th>
                                            <th>Poids</th>
                                            <th>Créé par</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($combats as $combat)
                                            <tr>
                                                <td>{{ optional($combat->scheduled_at)->format('d/m/Y H:i') ?? '—' }}</td>
                                                <td>{{ optional($combat->athleteA)->name ?? '—' }}</td>
                                                <td>{{ optional($combat->athleteB)->name ?? '—' }}</td>
                                                <td>{{ $combat->weight_kg !== null ? number_format((float)$combat->weight_kg, 2, ',', ' ') . ' kg' : '—' }}</td>
                                                <td>{{ optional($combat->creator)->name ?? '—' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Aucun combat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">
                                {{ $combats->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

