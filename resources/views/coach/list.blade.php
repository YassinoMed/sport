@extends('layouts.user-layout')

@push('styles')
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #a5b4fc;
            --primary-bg: #eef2ff;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #06b6d4;
            --info-light: #cffafe;
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
            --radius-xl: 24px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        /* Glassmorphism & Modern Layout */
        .modern-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-header-modern {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
            color: white;
            position: relative;
        }

        /* Progress Bar for AJAX */
        #topProgressBar {
            position: absolute;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--success);
            width: 0;
            transition: width 0.3s ease;
            z-index: 1000;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--gray-100);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .stat-card:hover::after { opacity: 1; }

        .stat-card.active-filter {
            border: 2px solid var(--primary);
            background: var(--primary-bg);
            box-shadow: inset 0 0 0 1px var(--primary-light);
        }

        /* Search Input Improvements */
        .search-wrapper { position: relative; }
        .search-clear {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray-400);
            display: none;
            transition: color 0.2s ease;
        }
        .search-clear:hover { color: var(--danger); }
        .search-wrapper input:not(:placeholder-shown) + .search-clear { display: block; }

        .stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            font-size: 1.25rem;
        }

        .stat-primary .stat-icon { background: #eef2ff; color: var(--primary); }
        .stat-success .stat-icon { background: #ecfdf5; color: var(--success); }
        .stat-info .stat-icon { background: #ecfeff; color: var(--info); }
        .stat-warning .stat-icon { background: #fffbeb; color: var(--warning); }

        .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); line-height: 1.2; }
        .stat-label { font-size: 0.8125rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; }

        /* Grid View */
        .grid-view-active .modern-table thead { display: none; }
        .grid-view-active #tableBody {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }
        .grid-view-active #tableBody tr {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-100);
            padding: 1.5rem;
            height: 100%;
            animation: fadeIn 0.4s ease forwards;
            box-shadow: var(--shadow-sm);
        }
        .grid-view-active #tableBody tr:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        .grid-view-active #tableBody td {
            display: block;
            width: 100%;
            padding: 0.5rem 0;
            border: none;
            text-align: left !important;
        }
        .grid-view-active #tableBody td::before {
            content: attr(data-label);
            font-weight: 700;
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
            display: block;
            margin-bottom: 0.25rem;
        }
        .grid-view-active #tableBody td[data-label="Coach"]::before { display: none; }
        .grid-view-active #tableBody td[data-label="Actions"]::before { display: none; }
        
        .grid-view-active .coach-cell { flex-direction: column; text-align: center; }
        .grid-view-active .coach-avatar { width: 80px; height: 80px; margin: 0 auto 1rem; }
        .grid-view-active .actions-cell { justify-content: center !important; border-top: 1px solid var(--gray-100); margin-top: 1rem; padding-top: 1rem; }
        .grid-view-active .belt-info { align-items: flex-start; }
        .grid-view-active .grade-text { font-size: 0.7rem; }
        .grid-view-active .metric-badge { width: 100%; justify-content: flex-start; padding-left: 1rem; }
        .grid-view-active .progress { width: 100% !important; height: 6px !important; margin-top: 0.5rem; }

        /* Utility Classes from table_partial */
        .coach-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: var(--primary-bg);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            background-position: center;
            background-size: cover;
            border: 2px solid white;
            box-shadow: 0 0 0 1px var(--gray-200);
        }
        .empty-avatar { background-color: var(--gray-100); color: var(--gray-400); }
        
        .status-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .status-active { background-color: var(--success); }
        .status-inactive { background-color: var(--gray-300); }

        .tag {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .tag-info { background: var(--info-light); color: var(--info); }
        
        .belt-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: var(--gray-900);
            color: white;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .metric-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.875rem;
        }
        .metric-sessions { background: var(--primary-bg); color: var(--primary); }
        .metric-presences { background: var(--success-light); color: var(--success); }
        .metric-absences { background: var(--gray-100); color: var(--gray-600); }
        .metric-absences-high { background: var(--danger-light); color: var(--danger); }

        .action-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: var(--gray-500);
            text-decoration: none;
            border: 1px solid var(--gray-200);
            background: white;
        }
        .action-btn:hover { transform: translateY(-2px); }
        .action-view:hover { color: var(--info); border-color: var(--info); background: var(--info-light); }
        .action-edit:hover { color: var(--primary); border-color: var(--primary); background: var(--primary-bg); }
        .action-delete:hover { color: var(--danger); border-color: var(--danger); background: var(--danger-light); }
        .action-ban:hover { color: var(--warning); border-color: var(--warning); background: var(--warning-light); }

        /* Buttons & Controls */
        .view-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .view-btn.active { background: white; color: var(--primary); }
        .view-btn:hover:not(.active) { background: rgba(255,255,255,0.2); }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
            color: white;
            filter: brightness(1.1);
        }

        .btn-ghost {
            background: white;
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        .btn-ghost:hover { background: var(--gray-50); color: var(--primary); border-color: var(--primary-light); }

        /* Animations */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Skeleton */
        .skeleton {
            background: linear-gradient(90deg, var(--gray-100) 25%, var(--gray-200) 50%, var(--gray-100) 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 4px;
        }
        @keyframes skeleton-loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        .skeleton-avatar { width: 44px; height: 44px; border-radius: 50%; background: var(--gray-100); }
        .skeleton-text { height: 12px; margin-bottom: 8px; background: var(--gray-100); border-radius: 2px; }
        .skeleton-card { border: 1px solid var(--gray-100); border-radius: var(--radius-lg); background: white; }

        /* Floating Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-xl);
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }
        .back-to-top.show { opacity: 1; visibility: visible; }
        .back-to-top:hover { transform: translateY(-5px); background: var(--primary-dark); }

        .modern-table td { transition: background 0.2s ease; }
        .modern-table tr:hover td { background: var(--gray-50); }

        @media (max-width: 768px) {
            .modern-table thead { display: none; }
            .modern-table tr { display: block; background: white; border: 1px solid var(--gray-200); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1rem; }
            .modern-table td { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--gray-50); }
            .modern-table td::before { content: attr(data-label); font-weight: 700; font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; }
        }
    </style>
@endpush

@section('content')
<div class="content-wrapper pb-5">
    <!-- Header Section -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="page-title mb-1" style="font-size: 1.75rem; font-weight: 800; color: var(--gray-900);">Gestion des Coachs</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Accueil</a></li>
                            <li class="breadcrumb-item active">Coachs</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="exportData()" class="btn-ghost d-none d-md-flex align-items-center gap-2">
                        <i class="fas fa-file-export"></i> Exporter
                    </button>
                    <a href="{{ route('coach.create') }}" class="btn-primary-modern">
                        <i class="fas fa-plus"></i> Nouveau Coach
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row g-4 mb-5">
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-primary active-filter" onclick="filterByStat('all')" id="stat-all">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $coaches->total() ?? count($coaches) }}</span>
                            <span class="stat-label">Total Coachs</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-success" onclick="filterByStat('active')" id="stat-active">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $coaches->sum('sessions_count') ?? 0 }}</span>
                            <span class="stat-label">Séances</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-info" onclick="filterByStat('present')" id="stat-present">
                        <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $coaches->sum('presences_count') ?? 0 }}</span>
                            <span class="stat-label">Présences</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-warning" onclick="filterByStat('absent')" id="stat-absent">
                        <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
                        <div class="stat-content">
                            <span class="stat-value">{{ $coaches->sum('absences_count') ?? 0 }}</span>
                            <span class="stat-label">Absences</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coaches Management Card -->
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fas fa-id-card fa-lg"></i>
                        </div>
                        <div>
                            <h2 class="h5 mb-0 fw-bold">Liste des Instructeurs</h2>
                            <p class="small mb-0 text-white-50">Gérez vos coachs et suivez leurs performances</p>
                        </div>
                    </div>
                    
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <div class="view-toggle d-none d-md-flex">
                            <button class="view-btn active" onclick="setView('table')" title="Vue Tableau" id="view-table">
                                <i class="fas fa-list"></i>
                            </button>
                            <button class="view-btn" onclick="setView('grid')" title="Vue Grille" id="view-grid">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section p-4 border-bottom bg-white position-relative">
                    <div id="topProgressBar"></div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="search-wrapper">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0" 
                                           placeholder="Rechercher par nom, téléphone, ID..." 
                                           value="{{ request('search') }}">
                                </div>
                                <i class="fas fa-times-circle search-clear" onclick="clearSearch()"></i>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select id="cityFilter" class="form-select">
                                <option value="">Toutes les villes</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="beltFilter" class="form-select">
                                <option value="">Toutes les ceintures</option>
                                @foreach($belts as $beltName)
                                    <option value="{{ $beltName }}" {{ request('belt') == $beltName ? 'selected' : '' }}>
                                        {{ $beltName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="statusFilter" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button onclick="resetFilters()" class="btn-ghost w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-sync-alt"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-container position-relative bg-white">
                    <div class="table-responsive">
                        <table class="modern-table w-100" id="coachesTable">
                            <thead class="bg-light">
                                <tr>
                                    <th onclick="setSort('name')" class="sortable p-4">Coach <i class="fas fa-sort ms-1"></i></th>
                                    <th class="p-4">Ville</th>
                                    <th class="p-4">Ceinture</th>
                                    <th onclick="setSort('sessions_count')" class="sortable text-center p-4">Sessions <i class="fas fa-sort ms-1"></i></th>
                                    <th onclick="setSort('attendance_rate')" class="sortable text-center p-4">Présence <i class="fas fa-sort ms-1"></i></th>
                                    <th onclick="setSort('absences_count')" class="sortable text-center p-4">Absences <i class="fas fa-sort ms-1"></i></th>
                                    <th class="text-end p-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @include('coach.table_partial')
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="paginationContainer" class="p-4 border-top d-flex justify-content-between align-items-center bg-white">
                        @if(method_exists($coaches, 'links'))
                            {{ $coaches->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 9999; align-items: center; justify-content: center;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
    </div>
    <!-- Back to Top -->
    <div class="back-to-top" id="backToTop" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-4"><i class="fas fa-trash-alt fa-3x"></i></div>
                <h3 class="fw-bold">Confirmer la suppression</h3>
                <p class="text-muted">Êtes-vous sûr de vouloir supprimer ce coach ? Cette action est irréversible.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-danger px-4" id="confirmDeleteBtn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="banModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="modal-body p-4 text-center">
                <div class="text-warning mb-4"><i class="fas fa-ban fa-3x"></i></div>
                <h3 class="fw-bold">Bloquer le coach</h3>
                <p class="text-muted">Voulez-vous vraiment bloquer ce coach ? Il ne pourra plus accéder à son compte.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-warning px-4 text-white" id="confirmBanBtn">Bloquer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let pendingDeleteId = null;
let pendingBanId = null;
let currentFilters = {
    search: '',
    city_id: '',
    belt: '',
    status: '',
    sort: 'created_at',
    direction: 'desc',
    page: 1
};

function initTooltips() {
    if (!(window.bootstrap && bootstrap.Tooltip)) return;
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function setView(type) {
    const container = document.querySelector('.modern-card');
    const tableBtn = document.getElementById('view-table');
    const gridBtn = document.getElementById('view-grid');
    const tableBody = document.getElementById('tableBody');

    // Add fade-out effect
    tableBody.style.opacity = '0';
    
    setTimeout(() => {
        if (type === 'grid') {
            container.classList.add('grid-view-active');
            gridBtn.classList.add('active');
            tableBtn.classList.remove('active');
            localStorage.setItem('coach_view_type', 'grid');
        } else {
            container.classList.remove('grid-view-active');
            tableBtn.classList.add('active');
            gridBtn.classList.remove('active');
            localStorage.setItem('coach_view_type', 'table');
        }
        
        // Fetch to update layout and fade-in
        fetchCoaches();
        tableBody.style.opacity = '1';
    }, 200);
}

function clearSearch() {
    const input = document.getElementById('searchInput');
    input.value = '';
    currentFilters.search = '';
    currentFilters.page = 1;
    fetchCoaches();
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function filterByStat(type) {
    document.querySelectorAll('.stat-card').forEach(card => card.classList.remove('active-filter'));
    const activeCard = document.getElementById(`stat-${type}`);
    if (activeCard) activeCard.classList.add('active-filter');

    currentFilters.page = 1;

    if (type === 'all') {
        currentFilters.sort = 'created_at';
        currentFilters.direction = 'desc';
        currentFilters.status = '';
    } else if (type === 'active') {
        currentFilters.sort = 'sessions_count';
        currentFilters.direction = 'desc';
        currentFilters.status = 'active';
    } else if (type === 'present') {
        currentFilters.sort = 'presences_count';
        currentFilters.direction = 'desc';
    } else if (type === 'absent') {
        currentFilters.sort = 'absences_count';
        currentFilters.direction = 'desc';
    }

    fetchCoaches();
}

function setSort(column) {
    if (currentFilters.sort === column) {
        currentFilters.direction = currentFilters.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentFilters.sort = column;
        currentFilters.direction = 'desc';
    }
    fetchCoaches();
}

function resetFilters() {
    currentFilters = {
        search: '',
        city_id: '',
        belt: '',
        status: '',
        sort: 'created_at',
        direction: 'desc',
        page: 1
    };

    document.getElementById('searchInput').value = '';
    document.getElementById('cityFilter').value = '';
    document.getElementById('beltFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.querySelectorAll('.stat-card').forEach(card => card.classList.remove('active-filter'));
    document.getElementById('stat-all').classList.add('active-filter');

    fetchCoaches();
    showToast('Filtres réinitialisés', 'info');
}

function exportData() {
    const table = document.getElementById('coachesTable');
    if (!table) return;

    showToast('Préparation de l\'export...', 'info');
    
    // Simple CSV Export as fallback
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        for (let j = 0; j < cols.length - 1; j++) { // Exclude actions
            let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
            row.push('"' + text + '"');
        }
        if (row.length > 0) csv.push(row.join(","));
    }

    const csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `export_coachs_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('Export terminé', 'success');
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const cityFilter = document.getElementById('cityFilter');
    const beltFilter = document.getElementById('beltFilter');
    const statusFilter = document.getElementById('statusFilter');

    const savedView = localStorage.getItem('coach_view_type');
    if (savedView) setView(savedView);

    searchInput.addEventListener('input', debounce(function(e) {
        currentFilters.search = e.target.value;
        currentFilters.page = 1;
        fetchCoaches();
    }, 500));

    cityFilter.addEventListener('change', function(e) {
        currentFilters.city_id = e.target.value;
        currentFilters.page = 1;
        fetchCoaches();
    });

    beltFilter.addEventListener('change', function(e) {
        currentFilters.belt = e.target.value;
        currentFilters.page = 1;
        fetchCoaches();
    });

    statusFilter.addEventListener('change', function(e) {
        currentFilters.status = e.target.value;
        currentFilters.page = 1;
        fetchCoaches();
    });

    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('.pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const url = new URL(pageLink.href);
            const page = url.searchParams.get('page');
            if (page) {
                currentFilters.page = page;
                fetchCoaches();
                window.scrollTo({ top: document.querySelector('.modern-card').offsetTop - 20, behavior: 'smooth' });
            }
        }
    });

    document.addEventListener('scroll', function() {
        const backToTop = document.getElementById('backToTop');
        if (window.scrollY > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    initTooltips();
});

function fetchCoaches() {
    const tableBody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const progressBar = document.getElementById('topProgressBar');

    if (loadingOverlay) loadingOverlay.style.display = 'flex';
    if (progressBar) progressBar.style.width = '30%';
    
    // Skeleton
    const isGrid = document.querySelector('.modern-card').classList.contains('grid-view-active');
    let skeletonHtml = '';
    for(let i=0; i<6; i++) {
        skeletonHtml += isGrid ? `
            <tr class="skeleton-card">
                <td colspan="7">
                    <div class="p-3 text-center">
                        <div class="skeleton-avatar mx-auto mb-3 skeleton"></div>
                        <div class="skeleton-text w-75 mx-auto skeleton"></div>
                        <div class="skeleton-text w-50 mx-auto skeleton"></div>
                        <div class="mt-4 d-flex gap-2 justify-content-center">
                            <div class="skeleton-text w-25 skeleton"></div>
                            <div class="skeleton-text w-25 skeleton"></div>
                        </div>
                    </div>
                </td>
            </tr>
        ` : `
            <tr>
                <td colspan="7" class="p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="skeleton-avatar skeleton"></div>
                        <div class="w-100">
                            <div class="skeleton-text w-25 skeleton"></div>
                            <div class="skeleton-text w-50 skeleton"></div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }
    tableBody.innerHTML = skeletonHtml;

    const params = new URLSearchParams(currentFilters);
    const url = `{{ route('coach.list') }}?${params.toString()}`;
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 15000);

    if (progressBar) progressBar.style.width = '60%';

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        signal: controller.signal
    })
    .then(response => {
        if (!response.ok) throw new Error('Server error');
        if (progressBar) progressBar.style.width = '90%';
        return response.json();
    })
    .then(data => {
        tableBody.style.opacity = '0';
        setTimeout(() => {
            tableBody.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            initTooltips();
            tableBody.style.opacity = '1';
            if (progressBar) {
                progressBar.style.width = '100%';
                setTimeout(() => { progressBar.style.width = '0'; }, 500);
            }
        }, 50);
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erreur de chargement', 'error');
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center p-5 text-danger">Une erreur est survenue. <button onclick="fetchCoaches()" class="btn btn-sm btn-outline-danger ms-2">Réessayer</button></td></tr>';
        if (progressBar) progressBar.style.width = '0';
    })
    .finally(() => {
        clearTimeout(timeoutId);
        if (loadingOverlay) loadingOverlay.style.display = 'none';
    });
}

function deleteCoach(id) {
    pendingDeleteId = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function banUser(id) {
    pendingBanId = id;
    const modal = new bootstrap.Modal(document.getElementById('banModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!pendingDeleteId) return;
    const id = pendingDeleteId;
    
    $.ajax({
        url: '/coach/' + id,
        type: 'DELETE',
        data: { _token: "{{ csrf_token() }}" },
        success: function() {
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
            fetchCoaches();
            showToast('Coach supprimé', 'success');
        },
        error: function() {
            showToast('Erreur de suppression', 'error');
        }
    });
});

document.getElementById('confirmBanBtn').addEventListener('click', function() {
    if (!pendingBanId) return;
    const id = pendingBanId;
    
    $.ajax({
        url: '/banUser/' + id,
        type: 'GET',
        success: function() {
            bootstrap.Modal.getInstance(document.getElementById('banModal')).hide();
            fetchCoaches();
            showToast('Statut mis à jour', 'success');
        },
        error: function() {
            showToast('Erreur de modification', 'error');
        }
    });
});

function showToast(message, type) {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show shadow-sm`;
    toast.style.minWidth = '250px';
    toast.innerHTML = `
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-${type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle')}"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    toastContainer.appendChild(toast);
    setTimeout(() => {
        const bsToast = new bootstrap.Alert(toast);
        bsToast.close();
    }, 4000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.style.position = 'fixed';
    container.style.top = '20px';
    container.style.right = '20px';
    container.style.zIndex = '10000';
    document.body.appendChild(container);
    return container;
}
</script>
@endsection