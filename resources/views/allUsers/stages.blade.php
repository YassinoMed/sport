@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <!-- En-tête -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="/" class="breadcrumb-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    Accueil
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Gestion des stages</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">Gestion des stages</h1>
                    <p class="page-subtitle">Gérer les stages des athlètes</p>
                </div>
                <div class="header-stats">
                    <div class="stat-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>{{ $users->total() }} athlètes</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <section class="content">
        <div class="container-fluid">
            <!-- Carte des filtres -->
            <div class="modern-card mb-4">
                <div class="card-header-modern card-header-compact">
                    <div class="header-icon header-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    </div>
                    <h3 class="header-title">Filtres</h3>
                </div>
                <div class="filter-body">
                    <form id="filterForm" method="GET" action="{{ route('allUsers.stages') }}">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label-modern">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    Rechercher
                                </label>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    <input type="text" name="q" class="form-control-modern with-icon" value="{{ request('q') }}" placeholder="Nom ou email...">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label-modern">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" x2="6" y1="1" y2="4"/><line x1="10" x2="10" y1="1" y2="4"/><line x1="14" x2="14" y1="1" y2="4"/></svg>
                                    Salle
                                </label>
                                <div class="select-wrapper">
                                    <select name="gym_id" class="form-select-modern">
                                        <option value="">Toutes les salles</option>
                                        @foreach($gyms as $gym)
                                            <option value="{{ $gym->id }}" {{ request('gym_id') == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="select-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label-modern">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Statut stage
                                </label>
                                <div class="select-wrapper">
                                    <select name="has_stage" class="form-select-modern">
                                        <option value="">Tous les statuts</option>
                                        <option value="1" {{ request('has_stage') === '1' ? 'selected' : '' }}>Avec stage</option>
                                        <option value="0" {{ request('has_stage') === '0' ? 'selected' : '' }}>Sans stage</option>
                                    </select>
                                    <svg class="select-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 d-flex align-items-end gap-2">
                                <button type="submit" class="btn-primary-modern flex-grow-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    Filtrer
                                </button>
                                <a href="{{ route('allUsers.stages') }}" class="btn-ghost btn-icon" title="Réinitialiser">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Carte du tableau -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-icon header-icon-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <h3 class="header-title">Liste des athlètes</h3>
                        <span class="header-subtitle">{{ $users->total() }} résultat(s) trouvé(s)</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Athlète</th>
                                <th>Salle</th>
                                <th>Catégorie</th>
                                <th class="text-center">Stage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <div class="avatar-modern {{ !$user->profile_image ? 'initials-avatar' : '' }}">
                                                @if($user->profile_image)
                                                    <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" onerror="this.style.display='none'; this.parentElement.classList.add('initials-avatar');">
                                                    <span class="avatar-initials">{{ mb_substr($user->name, 0, 1) }}</span>
                                                @else
                                                    <span class="avatar-initials">{{ mb_substr($user->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ $user->name }}</span>
                                                <span class="user-email">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" x2="6" y1="1" y2="4"/><line x1="10" x2="10" y1="1" y2="4"/><line x1="14" x2="14" y1="1" y2="4"/></svg>
                                            {{ $user->gym->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            {{ $user->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="stage-toggle" 
                                                data-user-id="{{ $user->id }}"
                                                {{ $user->has_stage ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span class="toggle-label {{ $user->has_stage ? 'active' : '' }}">
                                            {{ $user->has_stage ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                            <h4>Aucun athlète trouvé</h4>
                                            <p>Essayez de modifier vos critères de recherche</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="card-footer-modern">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Toast de notification -->
<div class="toast-container" id="toastContainer"></div>

@push('scripts')
<script>
document.querySelectorAll('.stage-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const userId = this.getAttribute('data-user-id');
        const hasStage = this.checked ? 1 : 0;
        const toggleLabel = this.closest('td').querySelector('.toggle-label');

        fetch('{{ route("allUsers.stages.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                has_stage: hasStage
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mise à jour du label
                toggleLabel.textContent = hasStage ? 'Actif' : 'Inactif';
                toggleLabel.classList.toggle('active', hasStage);
                
                // Afficher le toast de succès
                showToast('success', data.message || 'Statut mis à jour avec succès');
            } else {
                this.checked = !this.checked;
                showToast('error', 'Erreur lors de la mise à jour');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !this.checked;
            showToast('error', 'Erreur réseau');
        });
    });
});

function showToast(type, message) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            ${type === 'success' 
                ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'
                : '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>'}
        </svg>
        <span>${message}</span>
    `;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endpush

@push('styles')
<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-bg: #eef2ff;
    --success: #10b981;
    --success-bg: #d1fae5;
    --warning: #f59e0b;
    --warning-bg: #fef3c7;
    --danger: #ef4444;
    --danger-bg: #fee2e2;
    --info: #06b6d4;
    --info-bg: #cffafe;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

/* En-tête */
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.page-subtitle { font-size: 0.9375rem; color: var(--gray-500); margin: 0.25rem 0 0; }
.breadcrumb { padding: 0; margin: 0; background: transparent; font-size: 0.875rem; }
.breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--gray-400); }
.breadcrumb-link { color: var(--gray-500); text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: var(--transition); }
.breadcrumb-link:hover { color: var(--primary); }

.header-stats { display: flex; gap: 1rem; }
.stat-pill { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: var(--primary-bg); color: var(--primary); font-weight: 600; font-size: 0.875rem; border-radius: 9999px; }

/* Carte moderne */
.modern-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.card-header-modern { display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-100); }
.card-header-compact { padding: 1rem 1.25rem; }
.header-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.header-icon-primary { background: var(--primary-bg); color: var(--primary); }
.header-icon-success { background: var(--success-bg); color: var(--success); }
.header-title { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.header-subtitle { font-size: 0.8125rem; color: var(--gray-500); }

/* Filtres */
.filter-body { padding: 1.25rem; }
.form-label-modern { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem; }
.form-label-modern svg { color: var(--gray-400); }

.input-icon-wrapper { position: relative; }
.input-icon-wrapper svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; }
.form-control-modern.with-icon { padding-left: 2.75rem; }

.form-control-modern, .form-select-modern { width: 100%; padding: 0.75rem 1rem; font-size: 0.9375rem; color: var(--gray-900); background: white; border: 2px solid var(--gray-200); border-radius: var(--radius-md); transition: var(--transition); }
.form-control-modern:hover, .form-select-modern:hover { border-color: var(--gray-300); }
.form-control-modern:focus, .form-select-modern:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }

.select-wrapper { position: relative; }
.form-select-modern { appearance: none; padding-right: 2.5rem; cursor: pointer; }
.select-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; }

/* Boutons */
.btn-primary-modern { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; font-size: 0.875rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-primary-modern:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); color: white; }

.btn-ghost { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: transparent; color: var(--gray-600); font-weight: 500; font-size: 0.875rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }
.btn-ghost.btn-icon { padding: 0.75rem; }

/* Tableau */
.table-modern { width: 100%; border-collapse: collapse; }
.table-modern thead { background: var(--gray-50); }
.table-modern th { padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; text-align: left; border-bottom: 1px solid var(--gray-200); }
.table-modern td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
.table-modern tbody tr { transition: var(--transition); }
.table-modern tbody tr:hover { background: var(--gray-50); }

/* Cellule utilisateur */
.user-cell { display: flex; align-items: center; gap: 0.875rem; }
.avatar-modern { width: 44px; height: 44px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.avatar-modern.initials-avatar { background: linear-gradient(135deg, var(--primary-bg) 0%, #e0e7ff 100%); }
.avatar-modern img { width: 100%; height: 100%; object-fit: cover; }
.avatar-initials { color: white; font-weight: 700; font-size: 1rem; text-transform: uppercase; }
.initials-avatar .avatar-initials { color: var(--primary); }
.user-info { display: flex; flex-direction: column; }
.user-name { font-weight: 600; color: var(--gray-900); }
.user-email { font-size: 0.8125rem; color: var(--gray-500); }

/* Badges */
.badge-modern { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.8125rem; font-weight: 600; border-radius: 9999px; }
.badge-light { background: var(--gray-100); color: var(--gray-700); }
.badge-info { background: var(--info-bg); color: #0e7490; }

/* Toggle Switch personnalisé */
.toggle-switch { position: relative; display: inline-block; width: 52px; height: 28px; margin: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: var(--gray-200); border-radius: 9999px; transition: var(--transition); }
.toggle-slider::before { position: absolute; content: ""; height: 22px; width: 22px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: var(--transition); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.toggle-switch input:checked + .toggle-slider { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); }
.toggle-switch input:checked + .toggle-slider::before { transform: translateX(24px); }
.toggle-switch input:focus + .toggle-slider { box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }

.toggle-label { display: block; margin-top: 0.375rem; font-size: 0.75rem; font-weight: 600; color: var(--gray-400); transition: var(--transition); }
.toggle-label.active { color: var(--success); }

/* État vide */
.empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem; text-align: center; }
.empty-state svg { color: var(--gray-300); margin-bottom: 1rem; }
.empty-state h4 { font-size: 1.125rem; font-weight: 600; color: var(--gray-700); margin: 0 0 0.5rem; }
.empty-state p { color: var(--gray-500); font-size: 0.875rem; margin: 0; }

/* Footer du tableau */
.card-footer-modern { padding: 1rem 1.5rem; background: var(--gray-50); border-top: 1px solid var(--gray-100); }

/* Toast notifications */
.toast-container { position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem; }
.toast-notification { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; background: white; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); transform: translateX(120%); transition: transform 0.3s ease; min-width: 280px; }
.toast-notification.show { transform: translateX(0); }
.toast-notification.success { border-left: 4px solid var(--success); }
.toast-notification.success svg { color: var(--success); }
.toast-notification.error { border-left: 4px solid var(--danger); }
.toast-notification.error svg { color: var(--danger); }
.toast-notification span { font-weight: 500; color: var(--gray-700); }

/* Responsive */
@media (max-width: 992px) {
    .page-title { font-size: 1.375rem; }
    .header-stats { display: none; }
}

@media (max-width: 768px) {
    .table-modern th, .table-modern td { padding: 0.875rem 1rem; }
    .user-cell { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    .avatar-modern { width: 36px; height: 36px; }
    .toggle-label { display: none; }
}
</style>
@endpush
@endsection