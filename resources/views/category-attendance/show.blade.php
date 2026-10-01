@extends('layouts.user-layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Présences du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} - {{ $course->name }}
                    </h3>
                    <div>
                        <a href="{{ route('course-attendance.edit', [$course->id, $date]) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('course-attendance.index', $course->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Informations générales -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-graduation-cap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cours</span>
                                    <span class="info-box-number">{{ $course->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Date</span>
                                    <span class="info-box-number">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                                    <span class="info-box-more">{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Participants</span>
                                    <span class="info-box-number">{{ $attendances->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($attendances->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h4>Aucune présence enregistrée</h4>
                            <p>Aucune présence n'a été enregistrée pour cette date.</p>
                            <a href="{{ route('course-attendance.create', $course->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Marquer les présences
                            </a>
                        </div>
                    @else
                        <!-- Statistiques de présence -->
                        @php
                            $presentCount = $attendances->where('status', 'present')->count();
                            $absentCount = $attendances->where('status', 'absent')->count();
                            $excusedCount = $attendances->where('status', 'excused')->count();
                            $totalCount = $attendances->count();
                            $presentPercentage = $totalCount > 0 ? round(($presentCount / $totalCount) * 100, 1) : 0;
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $presentCount }}</h3>
                                        <p>Présents ({{ $presentPercentage }}%)</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $absentCount }}</h3>
                                        <p>Absents</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $excusedCount }}</h3>
                                        <p>Excusés</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-exclamation"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $totalCount }}</h3>
                                        <p>Total</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtres -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select id="categoryFilter" class="form-control form-control-sm">
                                    <option value="">🏷️ Toutes les catégories</option>
                                    @php
                                        $categories = $attendances->pluck('participation.user.category')->filter()->unique('id')->sortBy('name');
                                    @endphp
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="statusFilter" class="form-control form-control-sm">
                                    <option value="">📊 Tous les statuts</option>
                                    <option value="present">✅ Présents</option>
                                    <option value="absent">❌ Absents</option>
                                    <option value="excused">⚠️ Excusés</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="participantSearch" class="form-control form-control-sm" placeholder="🔍 Chercher par nom">
                            </div>
                        </div>

                        <!-- Liste détaillée des présences -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Athlète</th>
                                        <th class="d-none d-md-table-cell">Email</th>
                                        <th>Statut</th>
                                        <th>Notes</th>
                                        <th>Heure d'enregistrement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances->sortBy('participation.user.name') as $attendance)
                                        <tr class="{{ $attendance->status === 'present' ? 'table-success' : ($attendance->status === 'absent' ? 'table-danger' : 'table-warning') }}" data-name="{{ $attendance->participation->user->name }}" data-email="{{ $attendance->participation->user->email ?? '' }}" data-category="{{ $attendance->participation->user->category_id ?? '' }}" data-status="{{ $attendance->status }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar mr-2">
                                                        @switch($attendance->status)
                                                            @case('present')
                                                                <i class="fas fa-user-check fa-2x text-success"></i>
                                                                @break
                                                            @case('absent')
                                                                <i class="fas fa-user-times fa-2x text-danger"></i>
                                                                @break
                                                            @case('excused')
                                                                <i class="fas fa-user-clock fa-2x text-warning"></i>
                                                                @break
                                                        @endswitch
                                                    </div>
                                                    <div>
                                                        <strong>{{ $attendance->participation->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <a href="{{ route('participations.show', $attendance->participation->id) }}" class="text-decoration-none">
                                                                Voir participation
                                                            </a>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="d-none d-md-table-cell">{{ $attendance->participation->user->email }}</td>
                                            <td>
                                                @switch($attendance->status)
                                                    @case('present')
                                                        <span class="badge badge-success badge-lg">
                                                            <i class="fas fa-check"></i> Présent
                                                        </span>
                                                        @break
                                                    @case('absent')
                                                        <span class="badge badge-danger badge-lg">
                                                            <i class="fas fa-times"></i> Absent
                                                        </span>
                                                        @break
                                                    @case('excused')
                                                        <span class="badge badge-warning badge-lg">
                                                            <i class="fas fa-exclamation"></i> Excusé
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($attendance->notes)
                                                    <div class="text-wrap" style="max-width: 200px;">
                                                        {{ $attendance->notes }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">Aucune note</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $attendance->created_at->format('d/m/Y à H:i') }}
                                                    @if($attendance->updated_at != $attendance->created_at)
                                                        <br>
                                                        <em>Modifié le {{ $attendance->updated_at->format('d/m/Y à H:i') }}</em>
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('course-attendance.create', $course->id) }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Nouvelle présence
                                        </a>
                                        <a href="{{ route('course-attendance.statistics', $course->id) }}" class="btn btn-info">
                                            <i class="fas fa-chart-bar"></i> Statistiques
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                            <i class="fas fa-trash"></i> Supprimer cette séance
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire de suppression caché -->
                        <form id="deleteForm" action="{{ route('course-attendance.destroy', [$course->id, $date]) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer toutes les présences de cette date ?\n\nCette action est irréversible.')) {
        document.getElementById('deleteForm').submit();
    }
}

$(document).ready(function() {
    // Auto-hide success alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 5000);

    // Function to filter participants
    function filterParticipants() {
        var searchQuery = $('#participantSearch').val().toLowerCase();
        var selectedCategory = $('#categoryFilter').val();
        var selectedStatus = $('#statusFilter').val();
        
        $('tbody tr').each(function() {
            var $row = $(this);
            var name = (String($row.data('name')) || '').toLowerCase();
            var email = (String($row.data('email')) || '').toLowerCase();
            var category = String($row.data('category') || '');
            var status = String($row.data('status') || '');
            
            var matchesSearch = !searchQuery || name.indexOf(searchQuery) !== -1 || email.indexOf(searchQuery) !== -1;
            var matchesCategory = !selectedCategory || category === selectedCategory;
            var matchesStatus = !selectedStatus || status === selectedStatus;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    }
    
    $('#participantSearch').on('input', filterParticipants);
    $('#categoryFilter').on('change', filterParticipants);
    $('#statusFilter').on('change', filterParticipants);
});
</script>
@endpush