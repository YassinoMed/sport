@extends('layouts.user-layout')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --card-shadow: 0 10px 40px rgba(0,0,0,0.1);
        --hover-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .content-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%) !important;
    }

    .planning-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 30px 40px;
        margin-bottom: 30px;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .planning-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .planning-header h1 {
        color: white;
        font-weight: 700;
        font-size: 2rem;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .planning-header .subtitle {
        color: rgba(255,255,255,0.8);
        font-size: 1rem;
        margin-top: 5px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        position: relative;
        z-index: 1;
    }

    .btn-glass {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-glass:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .btn-add {
        background: white;
        color: #667eea;
        border: none;
    }

    .btn-add:hover {
        background: #f8f9fa;
        color: #764ba2;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: var(--card-shadow);
    }

    .filter-card .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .filter-card .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
    }

    .filter-card .btn-filter {
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        padding: 12px 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .filter-card .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102,126,234,0.4);
    }

    /* Calendar Grid */
    .calendar-container {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
    }

    .group-title {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .group-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
    }

    .group-title h3 {
        margin: 0;
        font-weight: 700;
        color: #2d3748;
        font-size: 1.4rem;
    }

    .group-title .badge-count {
        background: #e9ecef;
        color: #667eea;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Week Calendar */
    .week-calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
        margin-top: 20px;
    }

    .day-column {
        background: #f8fafc;
        border-radius: 16px;
        padding: 15px;
        min-height: 200px;
        transition: all 0.3s ease;
    }

    .day-column:hover {
        background: #f1f5f9;
        transform: translateY(-3px);
    }

    .day-header {
        text-align: center;
        padding-bottom: 12px;
        margin-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }

    .day-name {
        font-weight: 700;
        color: #4a5568;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .day-column.has-schedule .day-header {
        border-bottom-color: #667eea;
    }

    .day-column.has-schedule .day-name {
        color: #667eea;
    }

    /* Schedule Card */
    .schedule-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .schedule-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--primary-gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .schedule-card:hover::before {
        opacity: 0.03;
    }

    .schedule-time {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: #667eea;
        font-size: 1rem;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .schedule-time i {
        font-size: 0.85rem;
    }

    .schedule-location {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #718096;
        font-size: 0.85rem;
        margin-bottom: 5px;
        position: relative;
        z-index: 1;
    }

    .schedule-location i {
        color: #e53e3e;
    }

    .schedule-notes {
        color: #a0aec0;
        font-size: 0.8rem;
        font-style: italic;
        position: relative;
        z-index: 1;
    }

    .schedule-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f0f0f0;
        position: relative;
        z-index: 1;
    }

    .btn-action {
        flex: 1;
        padding: 8px;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #fef3c7;
        color: #d97706;
    }

    .btn-edit:hover {
        background: #fde68a;
        color: #b45309;
        text-decoration: none;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
    }

    .empty-state-icon i {
        font-size: 3rem;
        color: #667eea;
    }

    .empty-state h4 {
        color: #4a5568;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #a0aec0;
        max-width: 400px;
        margin: 0 auto;
    }

    .no-schedule {
        text-align: center;
        padding: 20px 10px;
        color: #cbd5e0;
        font-size: 0.85rem;
    }

    .no-schedule i {
        display: block;
        font-size: 1.5rem;
        margin-bottom: 8px;
    }

    /* View Toggle */
    .view-toggle {
        display: flex;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 4px;
        margin-left: auto;
    }

    .view-btn {
        padding: 8px 16px;
        border: none;
        background: transparent;
        border-radius: 8px;
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .view-btn.active {
        background: white;
        color: #667eea;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* List View */
    .list-view {
        display: none;
    }

    .list-view.active {
        display: block;
    }

    .month-view {
        display: none;
    }

    .month-view.active {
        display: block;
    }

    .calendar-view.hidden {
        display: none;
    }

    .schedule-list-item {
        display: flex;
        align-items: center;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .schedule-list-item:hover {
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transform: translateX(5px);
    }

    .list-day-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        min-width: 100px;
        text-align: center;
        margin-right: 20px;
    }

    .list-time-block {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .list-time {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #4a5568;
    }

    .list-time i {
        color: #667eea;
    }

    .list-location {
        color: #718096;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .list-location i {
        color: #e53e3e;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .week-calendar {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 992px) {
        .week-calendar {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .week-calendar {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .planning-header {
            padding: 20px;
        }
        
        .header-actions {
            flex-direction: column;
        }
        
        .list-time-block {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    @media (max-width: 576px) {
        .week-calendar {
            grid-template-columns: 1fr;
        }
    }

    /* Print Styles */
    @media print {
        .content-wrapper {
            background: white !important;
        }
        
        .btn-glass, .btn-action, .filter-card, .view-toggle {
            display: none !important;
        }
        
        .calendar-container {
            box-shadow: none;
            page-break-inside: avoid;
        }
        
        .schedule-card {
            box-shadow: none;
            border: 1px solid #e2e8f0;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="planning-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1><i class="fas fa-calendar-alt mr-3"></i>Planning des Groupes</h1>
                        <p class="subtitle mb-0">Gestion des horaires d'entraînement</p>
                    </div>
                    <div class="header-actions mt-3 mt-md-0">
                        <button onclick="window.print()" class="btn-glass">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                        <a href="{{ route('schedules.create') }}" class="btn-glass btn-add">
                            <i class="fas fa-plus"></i> Ajouter un horaire
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filter -->
            <div class="filter-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <form action="{{ route('schedules.index') }}" method="GET" class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-filter text-muted"></i>
                            <select name="group_id" class="form-control" style="min-width: 250px;" onchange="this.form.submit()">
                                <option value="">🏆 Tous les groupes</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ $groupId == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="far fa-calendar text-muted"></i>
                            <input type="month" name="month" class="form-control" style="min-width: 180px;" value="{{ $month ?? now()->format('Y-m') }}" onchange="this.form.submit()">
                        </div>
                        <button class="btn btn-filter" type="submit">
                            <i class="fas fa-search mr-1"></i> Filtrer
                        </button>
                    </form>
                    
                    <div class="view-toggle mt-3 mt-md-0">
                        <button class="view-btn active" onclick="toggleView('calendar')">
                            <i class="fas fa-th-large mr-1"></i> Semaine
                        </button>
                        <button class="view-btn" onclick="toggleView('month')">
                            <i class="far fa-calendar mr-1"></i> Mois
                        </button>
                        <button class="view-btn" onclick="toggleView('list')">
                            <i class="fas fa-list mr-1"></i> Liste
                        </button>
                    </div>
                </div>
            </div>

            @if($groupedSchedules->isEmpty())
                <!-- Empty State -->
                <div class="calendar-container">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h4>Aucun horaire défini</h4>
                        <p>Commencez par ajouter des horaires d'entraînement pour vos groupes.</p>
                        <a href="{{ route('schedules.create') }}" class="btn-glass btn-add mt-4" style="background: var(--primary-gradient); color: white;">
                            <i class="fas fa-plus"></i> Créer un horaire
                        </a>
                    </div>
                </div>
            @else
                @php
                    $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                @endphp
                
                @foreach($groupedSchedules as $gId => $groupSchedules)
                    @php $groupName = $groups->firstWhere('id', $gId)->name ?? 'Groupe inconnu'; @endphp
                    
                    <div class="calendar-container">
                        <div class="group-title">
                            <div class="group-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h3>{{ $groupName }}</h3>
                            </div>
                            <span class="badge-count">{{ $groupSchedules->count() }} séance(s)</span>
                        </div>
                        
                        <!-- Calendar View -->
                        <div class="calendar-view" id="calendar-{{ $gId }}">
                            <div class="week-calendar">
                                @foreach($days as $day)
                                    @php
                                        $daySchedules = $groupSchedules->filter(function($s) use ($day) {
                                            return $s->day === $day;
                                        });
                                    @endphp
                                    <div class="day-column {{ $daySchedules->count() > 0 ? 'has-schedule' : '' }}">
                                        <div class="day-header">
                                            <span class="day-name">{{ $day }}</span>
                                        </div>
                                        
                                        @if($daySchedules->count() > 0)
                                            @foreach($daySchedules as $schedule)
                                                <div class="schedule-card">
                                                    <div class="schedule-time">
                                                        <i class="far fa-clock"></i>
                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                    </div>
                                                    @if($schedule->location)
                                                        <div class="schedule-location">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            {{ $schedule->location }}
                                                        </div>
                                                    @endif
                                                    @if($schedule->notes)
                                                        <div class="schedule-notes">
                                                            {{ Str::limit($schedule->notes, 50) }}
                                                        </div>
                                                    @endif
                                                    <div class="schedule-actions">
                                                        <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn-action btn-edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-action btn-delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="no-schedule">
                                                <i class="far fa-calendar"></i>
                                                Repos
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Month View -->
                        <div class="month-view" id="month-{{ $gId }}">
                            @php($monthItems = $monthOccurrencesByGroup[$gId] ?? [])
                            @if(empty($monthItems))
                                <div class="text-muted">Aucune séance planifiée pour ce mois.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%">Date</th>
                                                <th style="width: 18%">Heure</th>
                                                <th style="width: 20%">Lieu</th>
                                                <th>Notes</th>
                                                <th style="width: 12%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($monthItems as $it)
                                                <tr>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($it['date'])->format('d/m/Y') }}
                                                        <span class="text-muted">({{ $it['day'] }})</span>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($it['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($it['end_time'])->format('H:i') }}</td>
                                                    <td>{{ ($it['location'] ?? '') !== '' ? $it['location'] : '-' }}</td>
                                                    <td>{{ ($it['notes'] ?? '') !== '' ? $it['notes'] : '-' }}</td>
                                                    <td>
                                                        <a href="{{ route('schedules.edit', $it['schedule_id']) }}" class="btn-action btn-edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- List View -->
                        <div class="list-view" id="list-{{ $gId }}">
                            @foreach($groupSchedules->sortBy(function($s) use ($days) {
                                return array_search($s->day, $days);
                            }) as $schedule)
                                <div class="schedule-list-item">
                                    <div class="list-day-badge">
                                        {{ $schedule->day }}
                                    </div>
                                    <div class="list-time-block">
                                        <div class="list-time">
                                            <i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </div>
                                        @if($schedule->location)
                                            <div class="list-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $schedule->location }}
                                            </div>
                                        @endif
                                        @if($schedule->notes)
                                            <div class="text-muted">
                                                <small>{{ $schedule->notes }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
</div>

<script>
function toggleView(view) {
    const calendarViews = document.querySelectorAll('.calendar-view');
    const monthViews = document.querySelectorAll('.month-view');
    const listViews = document.querySelectorAll('.list-view');
    const buttons = document.querySelectorAll('.view-btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    
    if (view === 'calendar') {
        calendarViews.forEach(el => el.classList.remove('hidden'));
        monthViews.forEach(el => el.classList.remove('active'));
        listViews.forEach(el => el.classList.remove('active'));
        buttons[0].classList.add('active');
        return;
    }

    if (view === 'month') {
        calendarViews.forEach(el => el.classList.add('hidden'));
        monthViews.forEach(el => el.classList.add('active'));
        listViews.forEach(el => el.classList.remove('active'));
        buttons[1].classList.add('active');
        return;
    }

    calendarViews.forEach(el => el.classList.add('hidden'));
    monthViews.forEach(el => el.classList.remove('active'));
    listViews.forEach(el => el.classList.add('active'));
    buttons[2].classList.add('active');
}
</script>
@endsection
