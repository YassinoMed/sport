@forelse ($coaches as $coach)
    <tr id="cid{{ $coach->id }}">
        <td data-label="Coach">
            <div class="coach-cell d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="coach-avatar {{ $coach->profile_image != 'avatar.png' ? '' : 'empty-avatar' }}" 
                         style="{{ $coach->profile_image != 'avatar.png' ? 'background-image: url('.asset($coach->profile_image).'); background-size: cover;' : '' }}">
                        @if($coach->profile_image == 'avatar.png')
                            {{ strtoupper(substr($coach->name, 0, 1)) }}
                        @endif
                    </div>
                    <span class="status-indicator {{ $coach->sessions_count > 0 ? 'status-active' : 'status-inactive' }}" 
                          data-bs-toggle="tooltip" 
                          title="{{ $coach->sessions_count > 0 ? 'Actif' : 'Inactif' }}"></span>
                </div>
                <div class="coach-info d-flex flex-column">
                    <span class="coach-name fw-bold text-gray-900">{{ $coach->name }}</span>
                    <span class="coach-meta text-gray-500 small">
                        #{{ $coach->id }}
                        @if($coach->phone)
                            <span class="ms-1">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $coach->phone) }}" target="_blank" class="text-success" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="tel:{{ $coach->phone }}" class="text-secondary ms-1" title="Appeler">
                                    <i class="fas fa-phone"></i>
                                </a>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </td>
        <td data-label="Ville">
            @if ($coach->city)
                <span class="tag tag-info">
                    <i class="fas fa-map-marker-alt me-1 text-info"></i>
                    {{ $coach->city->name }}
                </span>
            @else
                <span class="text-muted small">—</span>
            @endif
        </td>
        <td data-label="Ceinture">
            @php $lastBelt = $coach->belts->last(); @endphp
            @if($lastBelt)
                <div class="d-flex flex-column belt-info">
                    <span class="belt-badge">{{ $lastBelt->belt_name }}</span>
                    @if($lastBelt->training_grade)
                        <span class="text-muted extra-small mt-1 grade-text">{{ $lastBelt->training_grade }}</span>
                    @endif
                </div>
            @else
                <span class="text-muted small">—</span>
            @endif
        </td>
        <td data-label="Sessions" class="text-center">
            <span class="metric-badge metric-sessions">
                {{ $coach->sessions_count ?? 0 }}
            </span>
        </td>
        <td data-label="Présences" class="text-center">
            <div class="d-flex flex-column align-items-center gap-1">
                <span class="metric-badge metric-presences">
                    {{ $coach->presences_count ?? 0 }}
                </span>
                @php
                    $total = ($coach->presences_count ?? 0) + ($coach->absences_count ?? 0);
                    $rate = $total > 0 ? round(($coach->presences_count / $total) * 100) : 0;
                    $color = $rate >= 80 ? 'bg-success' : ($rate >= 50 ? 'bg-warning' : 'bg-danger');
                @endphp
                @if($total > 0)
                <div class="progress" style="width: 40px; height: 4px;" data-bs-toggle="tooltip" title="{{ $rate }}% de présence">
                    <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ $rate }}%"></div>
                </div>
                @endif
            </div>
        </td>
        <td data-label="Absences" class="text-center">
            <span class="metric-badge {{ ($coach->absences_count ?? 0) > 5 ? 'metric-absences-high' : 'metric-absences' }}">
                {{ $coach->absences_count ?? 0 }}
            </span>
        </td>
        <td data-label="Actions" class="text-end">
            <div class="actions-cell d-flex justify-content-end gap-2">
                <a class="action-btn action-view" href="{{ route('coach.show', $coach->id) }}" 
                   data-bs-toggle="tooltip" title="Voir le profil">
                    <i class="fas fa-eye"></i>
                </a>
                <a class="action-btn action-edit" href="{{ route('coach.edit', $coach->id) }}" 
                   data-bs-toggle="tooltip" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                <button type="button" class="action-btn action-delete" onclick="deleteCoach({{ $coach->id }})" 
                        data-bs-toggle="tooltip" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <button type="button" class="action-btn action-ban" onclick="banUser({{ $coach->id }})" 
                        data-bs-toggle="tooltip" title="Bloquer">
                    <i class="fas fa-ban"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7">
            <div class="empty-state p-5 text-center">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-users-slash fa-4x text-gray-200"></i>
                </div>
                <h4 class="text-gray-900 fw-bold">Aucun coach trouvé</h4>
                <p class="text-gray-500 mb-4">Nous n'avons trouvé aucun coach correspondant à vos critères.</p>
                <button onclick="resetFilters()" class="btn-ghost">
                    <i class="fas fa-sync-alt me-2"></i>Réinitialiser les filtres
                </button>
            </div>
        </td>
    </tr>
@endforelse
