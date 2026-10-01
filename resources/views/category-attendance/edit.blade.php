@extends('layouts.user-layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier les présences du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} - {{ $course->name }}
                    </h3>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('course-attendance.update', [$course->id, $date]) }}" method="POST" id="attendanceForm">
                        @csrf
                        @method('PUT')

                        <!-- Informations de la séance -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-graduation-cap"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Cours</span>
                                        <span class="info-box-number">{{ $course->name }}</span>
                                        @if($course->description)
                                            <span class="info-box-more">{{ $course->description }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-calendar-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Date de présence</span>
                                        <span class="info-box-number">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                                        <span class="info-box-more">{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($participations->isEmpty())
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h4>Aucun participant inscrit</h4>
                                <p>Il n'y a aucun participant inscrit à ce cours.</p>
                                <a href="{{ route('participations.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter des participants
                                </a>
                            </div>
                        @else
                            <!-- Actions rapides -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success btn-sm" onclick="markAll('present')">
                                            <i class="fas fa-check-circle"></i> Tous présents
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="markAll('absent')">
                                            <i class="fas fa-times-circle"></i> Tous absents
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="markAll('excused')">
                                            <i class="fas fa-exclamation-circle"></i> Tous excusés
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearAll()">
                                            <i class="fas fa-eraser"></i> Effacer tout
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select id="categoryFilter" class="form-control form-control-sm">
                                        <option value="">🏷️ Toutes les catégories</option>
                                        @php
                                            $categories = $participations->pluck('user.category')->filter()->unique('id')->sortBy('name');
                                        @endphp
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="participantSearch" class="form-control form-control-sm" placeholder="🔍 Chercher par nom">
                                </div>
                            </div>

                            <!-- Liste des participants -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="thead-dark sticky-top">
                                        <tr>
                                            <th width="35%">Athlète</th>
                                            <th width="25%" class="d-none d-md-table-cell">Catégorie</th>
                                            <th width="25%">Statut présence</th>
                                            <th width="15%" class="d-none d-md-table-cell">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($participations as $index => $participation)
                                            @php
                                                $existingAttendance = $participation->courseAttendances->first();
                                                $currentStatus = $existingAttendance ? $existingAttendance->status : '';
                                                $currentNotes = $existingAttendance ? $existingAttendance->notes : '';
                                            @endphp
                                            <tr data-name="{{ $participation->user->name }}" data-email="{{ $participation->user->email ?? '' }}" data-category="{{ $participation->user->category_id ?? '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="user-avatar mr-2">
                                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $participation->user->name }}</strong>
                                                            <br>
                                                            <small class="text-muted d-none d-sm-inline">
                                                                Inscrit le {{ \Carbon\Carbon::parse($participation->participation_date)->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="attendances[{{ $index }}][participation_id]" value="{{ $participation->id }}">
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    @if($participation->user->category)
                                                        <span class="badge badge-info">{{ $participation->user->category->name }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm" name="attendances[{{ $index }}][status]" required>
                                                        <option value="present" {{ $currentStatus == 'present' ? 'selected' : '' }}>✅ Présent</option>
                                                        <option value="absent" {{ $currentStatus == 'absent' ? 'selected' : '' }}>❌ Absent</option>
                                                        <option value="excused" {{ $currentStatus == 'excused' ? 'selected' : '' }}>⚠️ Excusé</option>
                                                    </select>
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <div class="form-group mb-0">
                                                        <textarea class="form-control form-control-sm" 
                                                                  name="attendances[{{ $index }}][notes]" 
                                                                  rows="1" 
                                                                  placeholder="Notes..."
                                                                  maxlength="500">{{ old('attendances.'.$index.'.notes', $currentNotes) }}</textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Résumé -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <div class="row text-center">
                                                <div class="col-4 col-md-3">
                                                    <strong class="text-success"><i class="fas fa-check"></i> <span id="present-count">0</span></strong>
                                                </div>
                                                <div class="col-4 col-md-3">
                                                    <strong class="text-danger"><i class="fas fa-times"></i> <span id="absent-count">0</span></strong>
                                                </div>
                                                <div class="col-4 col-md-3">
                                                    <strong class="text-warning"><i class="fas fa-exclamation"></i> <span id="excused-count">0</span></strong>
                                                </div>
                                                <div class="col-12 col-md-3 mt-2 mt-md-0">
                                                    <span class="text-muted">Total: {{ $participations->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <a href="{{ route('course-attendance.show', [$course->id, $date]) }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Retour
                                            </a>
                                            <a href="{{ route('course-attendance.index', $course->id) }}" class="btn btn-outline-secondary d-none d-md-inline-block">
                                                <i class="fas fa-list"></i> Liste
                                            </a>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save"></i> Enregistrer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-box { box-shadow: none; border: 1px solid #e9ecef; border-radius: .5rem; }
    .btn-group .btn { margin-right: 0; }
    .table thead th { white-space: nowrap; vertical-align: middle; }
    .table tbody tr td { vertical-align: middle; }
    .sticky-top { position: sticky; top: 0; z-index: 2; }
    /* Custom radio button styling for better visibility and interaction */
    .btn-group-toggle label { cursor: pointer; transition: all 0.2s ease; }
    .btn-group-toggle label:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .btn-outline-success:not(:disabled):not(.disabled).active, 
    .btn-outline-success:not(:disabled):not(.disabled):active, 
    .show>.btn-outline-success.dropdown-toggle {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    .btn-outline-danger:not(:disabled):not(.disabled).active, 
    .btn-outline-danger:not(:disabled):not(.disabled):active, 
    .show>.btn-outline-danger.dropdown-toggle {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    .btn-outline-warning:not(:disabled):not(.disabled).active, 
    .btn-outline-warning:not(:disabled):not(.disabled):active, 
    .show>.btn-outline-warning.dropdown-toggle {
        background-color: #ffc107;
        border-color: #ffc107;
        color: black;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Mettre à jour les compteurs en temps réel
    function updateCounts() {
        let presentCount = $('select[name*="[status]"]').filter(function() {
            return $(this).val() === 'present';
        }).length;
        
        let absentCount = $('select[name*="[status]"]').filter(function() {
            return $(this).val() === 'absent';
        }).length;
        
        let excusedCount = $('select[name*="[status]"]').filter(function() {
            return $(this).val() === 'excused';
        }).length;
        
        $('#present-count').text(presentCount);
        $('#absent-count').text(absentCount);
        $('#excused-count').text(excusedCount);
    }

    // Écouter les changements de statut
    $(document).on('change', 'select[name*="[status]"]', function() {
        updateCounts();
    });

    // Fonctions pour les actions rapides
    window.markAll = function(status) {
        $('select[name*="[status]"]').val(status);
        updateCounts();
    };

    window.clearAll = function() {
        $('select[name*="[status]"]').val('present');
        updateCounts();
    };

    // Function to filter participants by search and category
    function filterParticipants() {
        var searchQuery = $('#participantSearch').val().toLowerCase();
        var selectedCategory = $('#categoryFilter').val();
        
        $('tbody tr').each(function() {
            var $row = $(this);
            var name = (String($row.data('name')) || '').toLowerCase();
            var email = (String($row.data('email')) || '').toLowerCase();
            var category = String($row.data('category') || '');
            
            var matchesSearch = !searchQuery || name.indexOf(searchQuery) !== -1 || email.indexOf(searchQuery) !== -1;
            var matchesCategory = !selectedCategory || category === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                $row.show();
            } else {
                $row.hide();
            }
        });
        
        updateCounts();
    }
    
    $('#participantSearch').on('input', filterParticipants);
    $('#categoryFilter').on('change', filterParticipants);

    // Initialiser les compteurs
    updateCounts();
});
</script>
@endpush