@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier l'Horaire</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Planing</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Modifier le créneau</h3>
                        </div>
                        <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="group_id">Groupe <span class="text-danger">*</span></label>
                                    <select name="group_id" id="group_id" class="form-control @error('group_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un groupe</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ old('group_id', $schedule->group_id) == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('group_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="day">Jour <span class="text-danger">*</span></label>
                                    <select name="day" id="day" class="form-control @error('day') is-invalid @enderror" required>
                                        <option value="">Sélectionner un jour</option>
                                        @foreach($days as $day)
                                            <option value="{{ $day }}" {{ old('day', $schedule->day) == $day ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Heure de début <span class="text-danger">*</span></label>
                                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required>
                                            @error('start_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_time">Heure de fin <span class="text-danger">*</span></label>
                                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required>
                                            @error('end_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="location">Lieu (Optionnel)</label>
                                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $schedule->location) }}" placeholder="Ex: Salle principale">
                                    @error('location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes">Notes / Remarques (Optionnel)</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Informations complémentaires...">{{ old('notes', $schedule->notes) }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-warning float-right">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
