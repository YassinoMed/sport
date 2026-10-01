@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-broadcast-tower text-danger"></i> Résultats en Direct : {{ $event->name }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    @auth
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary">Retour à l'événement</a>
                    @else
                        <a href="{{ route('welcome') }}" class="btn btn-secondary">Accueil</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Matchs et Classement</h3>
                            <div class="card-tools">
                                <span class="badge badge-danger">LIVE</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Horaire</th>
                                        <th>Athlète A</th>
                                        <th class="text-center">VS</th>
                                        <th>Athlète B</th>
                                        <th>Statut</th>
                                        <th>Résultat / Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($combats as $combat)
                                        <tr>
                                            <td>{{ $combat->scheduled_at ? $combat->scheduled_at->format('H:i') : '-' }}</td>
                                            <td class="{{ $combat->winner_id == $combat->athlete_a_id ? 'text-success font-weight-bold' : '' }}">
                                                {{ $combat->athleteA->name ?? 'À définir' }}
                                                @if($combat->winner_id == $combat->athlete_a_id)
                                                    <i class="fas fa-trophy text-warning"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary">VS</span>
                                            </td>
                                            <td class="{{ $combat->winner_id == $combat->athlete_b_id ? 'text-success font-weight-bold' : '' }}">
                                                {{ $combat->athleteB->name ?? 'À définir' }}
                                                @if($combat->winner_id == $combat->athlete_b_id)
                                                    <i class="fas fa-trophy text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($combat->status == 'completed')
                                                    <span class="badge badge-success">Terminé</span>
                                                @elseif($combat->status == 'in_progress')
                                                    <span class="badge badge-warning">En cours</span>
                                                @else
                                                    <span class="badge badge-info">Programmé</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($combat->score)
                                                    <span class="badge badge-dark">{{ $combat->score }}</span>
                                                @elseif($combat->status == 'completed' && $combat->winner)
                                                    Vainqueur : {{ $combat->winner->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun combat programmé pour le moment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Auto-refresh every 60 seconds
    setTimeout(function() {
        location.reload();
    }, 60000);
</script>
@endsection
