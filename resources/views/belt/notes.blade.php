@extends('layouts.user-layout')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: #fff !important; margin: 0; padding: 10px; }
        .grade-card { box-shadow: none !important; border: 2px solid #333 !important; }
        .grade-table { font-size: 8pt; }
        .grade-table th, .grade-table td { padding: 4px 5px; }
    }
    
    .filter-box {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    
    .btn-open {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 8px;
    }
    
    .btn-open:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: #fff;
    }
    
    .btn-print {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 8px;
    }
    
    .grade-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        overflow: hidden;
        margin-bottom: 25px;
        --belt-primary: #ffd700;
        --belt-light: #ffec80;
        --belt-border: #e6c200;
        --belt-text: #1a1a2e;
    }
    
    .grade-header {
        background: linear-gradient(90deg, var(--belt-primary) 0%, var(--belt-light) 50%, var(--belt-primary) 100%);
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid var(--belt-border);
    }
    
    .grade-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--belt-text);
        margin: 0;
    }
    
    .grade-date {
        font-weight: 600;
        color: var(--belt-text);
    }
    
    .grade-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    
    .grade-table th,
    .grade-table td {
        border: 1px solid #555;
        padding: 6px 8px;
        text-align: center;
        vertical-align: middle;
    }
    
    .header-name {
        background: linear-gradient(180deg, #98FB98 0%, #7CCD7C 100%);
        font-weight: 700;
        min-width: 140px;
        color: #333;
    }
    
    .cat-main {
        background: linear-gradient(180deg, #7CCD7C 0%, #5BA55B 100%);
        font-weight: 700;
        color: #000;
        font-size: 12px;
    }
    
    .cat-sub1 {
        background: linear-gradient(180deg, #8FD88F 0%, #6BC76B 100%);
        font-weight: 600;
        color: #1a1a2e;
        font-size: 11px;
    }

    .cat-sub2 {
        background: linear-gradient(180deg, #a8e8a8 0%, #8FD88F 100%);
        font-weight: 600;
        color: #1a1a2e;
        font-size: 11px;
    }

    .cat-note {
        background: linear-gradient(180deg, #c8f7c8 0%, #a8e8a8 100%);
        font-weight: 500;
        font-size: 10px;
        color: #333;
        min-width: 60px;
    }

    .cat-note .note-name {
        font-weight: 600;
        display: block;
        margin-bottom: 2px;
    }

    .cat-note .note-score {
        font-weight: 700;
        color: #000;
        font-size: 11px;
    }
    
    .col-total {
        background: linear-gradient(180deg, #87CEEB 0%, #5CACEE 100%);
        font-weight: 700;
        min-width: 55px;
        color: #1a1a2e;
    }

    .student-row td {
        height: 32px;
        background: #fff;
        vertical-align: middle;
    }
    
    .student-row:hover td {
        background: #f5f5f5;
    }

    .score-input {
        width: 100%;
        max-width: 50px;
        height: 26px;
        border: 1px solid #bbb;
        background: #fff;
        border-radius: 4px;
        text-align: center;
        font-size: 12px;
        padding: 2px 4px;
        outline: none;
    }

    .student-name-cell {
        font-weight: 600;
        text-align: left;
        padding-left: 10px;
        min-width: 140px;
    }

    .student-total {
        font-weight: 700;
    }
    
    .management-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .management-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: #fff;
        padding: 15px 20px;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .category-block {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }
    
    .category-header {
        background: linear-gradient(90deg, #7CCD7C 0%, #5BA55B 100%);
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .category-header strong {
        color: #fff;
        font-size: 1rem;
    }
    
    .subcategory-block {
        border-left: 3px solid #98FB98;
        margin: 10px 0 10px 15px;
        padding-left: 15px;
        background: #fafafa;
        border-radius: 0 5px 5px 0;
    }
    
    .note-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 5px 12px;
        margin: 3px;
        font-size: 0.9rem;
    }
    
    .note-badge .score {
        background: #6c757d;
        color: #fff;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

<div class="content-wrapper pb-4">
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show no-print">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger no-print">
                    @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <div class="filter-box no-print">
                <label class="form-label fw-bold mb-2">{{ __('messages.belt_title') }}</label>
                <form method="GET" action="{{ route('belt.notes.index') }}" class="d-flex gap-3 align-items-center flex-wrap">
                    <select name="belt_name" class="form-select" style="max-width: 400px;" required>
                        <option value="">{{ __('messages.select_belt') }}</option>
                        @foreach($beltOptions as $value => $label)
                            <option value="{{ $value }}" {{ ($beltName ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-open">{{ __('messages.open') }}</button>
                    @if(!empty($beltName))
                        <button type="button" class="btn btn-print" onclick="window.print()">{{ __('messages.print') }}</button>
                    @endif
                </form>
                @if(!empty($beltName))
                    <div class="d-flex gap-2 align-items-end flex-wrap mt-3">
                        <div>
                            <label class="form-label fw-semibold mb-1">{{ __('messages.athletes_without_belt') }}</label>
                            <select id="eligibleAthleteSelect" class="form-select" style="min-width: 320px; max-width: 520px;">
                                <option value="">{{ __('messages.select_athlete') }}</option>
                                @foreach(($eligibleAthletes ?? collect()) as $a)
                                    <option value="{{ $a['id'] }}">{{ $a['name'] }}{{ !empty($a['last_belt']) ? ' — ' . $a['last_belt'] : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-success" id="addAthleteBtn">{{ __('messages.add') }}</button>
                        <button type="button" class="btn btn-primary" id="validateSheetBtn">{{ __('messages.validate') }}</button>
                    </div>
                @endif
            </div>

            @if(!empty($beltName))
                @php
                    $baseBelt = preg_replace('/\s+.*/', '', (string) $beltName);
                    $beltThemes = [
                        'Blanche' => ['primary' => '#f8f9fa', 'light' => '#ffffff', 'border' => '#ced4da', 'text' => '#1a1a2e'],
                        'Jaune' => ['primary' => '#ffd700', 'light' => '#ffec80', 'border' => '#e6c200', 'text' => '#1a1a2e'],
                        'Verte' => ['primary' => '#28a745', 'light' => '#8de5a1', 'border' => '#1e7e34', 'text' => '#0b2e13'],
                        'Bleue' => ['primary' => '#0d6efd', 'light' => '#86b7fe', 'border' => '#0a58ca', 'text' => '#052c65'],
                        'Violette' => ['primary' => '#6f42c1', 'light' => '#c29ffa', 'border' => '#59359a', 'text' => '#2d125a'],
                        'Rouge' => ['primary' => '#dc3545', 'light' => '#f3a6ae', 'border' => '#b02a37', 'text' => '#58151c'],
                        'Noire' => ['primary' => '#343a40', 'light' => '#adb5bd', 'border' => '#212529', 'text' => '#ffffff'],
                    ];
                    $theme = $beltThemes[$baseBelt] ?? $beltThemes['Jaune'];
                    $beltVars = "--belt-primary: {$theme['primary']}; --belt-light: {$theme['light']}; --belt-border: {$theme['border']}; --belt-text: {$theme['text']};";

                    // ========================================
                    // CONSTRUCTION SIMPLIFIÉE DU TABLEAU
                    // ========================================
                    
                    $totalMax = 0;
                    $allColumns = [];
                    $row1 = [];
                    $row2 = [];
                    $row3 = [];
                    $row4 = [];

                    foreach ($categories as $category) {
                        $catNotes = $category->notes ?? collect();
                        $subs = $category->subcategories ?? collect();
                        
                        $catColspan = 0;
                        
                        // CAS A: Notes directes de catégorie (rowspan=3 en ligne 2)
                        foreach ($catNotes as $note) {
                            $catColspan++;
                            $totalMax += (float) ($note->position ?? 0);
                            $allColumns[] = ['max' => (float) ($note->position ?? 0)];
                            $row2[] = ['type' => 'note', 'name' => $note->content, 'score' => $note->position ?? 0, 'rowspan' => 3, 'colspan' => 1];
                        }
                        
                        // CAS B: Sous-catégories niveau 1
                        foreach ($subs as $sub1) {
                            $sub1Notes = $sub1->notes ?? collect();
                            $sub1Children = $sub1->childrenRecursive ?? collect();
                            
                            $sub1Colspan = 0;
                            $sub1HasChildren = $sub1Children->count() > 0;
                            $sub1HasNotes = $sub1Notes->count() > 0;
                            
                            if ($sub1HasChildren) {
                                // Sub1 a des enfants niveau 2
                                foreach ($sub1Children as $sub2) {
                                    $sub2Notes = $sub2->notes ?? collect();
                                    $sub2Children = $sub2->childrenRecursive ?? collect();
                                    
                                    $sub2Colspan = 0;
                                    $sub2HasChildren = $sub2Children->count() > 0;
                                    $sub2HasNotes = $sub2Notes->count() > 0;
                                    
                                    if ($sub2HasChildren) {
                                        // Sub2 a des enfants niveau 3 → notes en row4
                                        foreach ($sub2Children as $sub3) {
                                            $sub3Notes = $sub3->notes ?? collect();
                                            if ($sub3Notes->count() > 0) {
                                                foreach ($sub3Notes as $note) {
                                                    $sub2Colspan++;
                                                    $totalMax += (float) ($note->position ?? 0);
                                                    $allColumns[] = ['max' => (float) ($note->position ?? 0)];
                                                    $row4[] = ['type' => 'note', 'name' => $note->content, 'score' => $note->position ?? 0];
                                                }
                                            } else {
                                                $sub2Colspan++;
                                                $allColumns[] = ['max' => 0, 'disabled' => true];
                                                $row4[] = ['type' => 'note', 'name' => $sub3->name, 'score' => '-'];
                                            }
                                        }
                                        $row3[] = ['type' => 'sub', 'name' => $sub2->name, 'colspan' => $sub2Colspan, 'rowspan' => 1];
                                    } elseif ($sub2HasNotes) {
                                        // Sub2 a des notes → notes en row4
                                        foreach ($sub2Notes as $note) {
                                            $sub2Colspan++;
                                            $totalMax += (float) ($note->position ?? 0);
                                            $allColumns[] = ['max' => (float) ($note->position ?? 0)];
                                            $row4[] = ['type' => 'note', 'name' => $note->content, 'score' => $note->position ?? 0];
                                        }
                                        $row3[] = ['type' => 'sub', 'name' => $sub2->name, 'colspan' => $sub2Colspan, 'rowspan' => 1];
                                    } else {
                                        // Sub2 vide → rowspan=2 en row3
                                        $sub2Colspan = 1;
                                        $allColumns[] = ['max' => 0, 'disabled' => true];
                                        $row3[] = ['type' => 'sub', 'name' => $sub2->name, 'colspan' => 1, 'rowspan' => 2];
                                    }
                                    
                                    $sub1Colspan += $sub2Colspan;
                                }
                                
                                $row2[] = ['type' => 'sub', 'name' => $sub1->name, 'colspan' => $sub1Colspan, 'rowspan' => 1];
                                
                            } elseif ($sub1HasNotes) {
                                // Sub1 n'a PAS d'enfants mais a des notes → notes en row3 avec rowspan=2
                                foreach ($sub1Notes as $note) {
                                    $sub1Colspan++;
                                    $totalMax += (float) ($note->position ?? 0);
                                    $allColumns[] = ['max' => (float) ($note->position ?? 0)];
                                    $row3[] = ['type' => 'note', 'name' => $note->content, 'score' => $note->position ?? 0, 'rowspan' => 2, 'colspan' => 1];
                                }
                                
                                $row2[] = ['type' => 'sub', 'name' => $sub1->name, 'colspan' => $sub1Colspan, 'rowspan' => 1];
                                
                            } else {
                                // Sub1 vide → rowspan=3 en row2
                                $sub1Colspan = 1;
                                $allColumns[] = ['max' => 0, 'disabled' => true];
                                $row2[] = ['type' => 'sub', 'name' => $sub1->name, 'colspan' => 1, 'rowspan' => 3];
                            }
                            
                            $catColspan += $sub1Colspan;
                        }
                        
                        // CAS C: Catégorie vide
                        if ($catColspan == 0) {
                            $catColspan = 1;
                            $allColumns[] = ['max' => 0, 'disabled' => true];
                            $row2[] = ['type' => 'empty', 'name' => '-', 'rowspan' => 3, 'colspan' => 1];
                        }
                        
                        $row1[] = ['name' => $category->name, 'colspan' => $catColspan];
                    }
                @endphp
                <div class="grade-card" style="{{ $beltVars }}">
                    <div class="grade-header">
                        <h2 class="grade-title">{{ __('messages.belt_grading_for', ['belt' => $beltOptions[$beltName] ?? $beltName]) }}</h2>
                        <span class="grade-date">{{ __('messages.date') }} : {{ date('d/m/Y') }}</span>
                    </div>

                    @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="grade-table">
                            <thead>
                                {{-- LIGNE 1 : Catégories --}}
                                <tr>
                                    <th class="header-name" rowspan="4">{!! __('messages.name_and_surname') !!}</th>
                                    @foreach($row1 as $item)
                                        <th class="cat-main" colspan="{{ $item['colspan'] }}">{{ $item['name'] }}</th>
                                    @endforeach
                                    <th class="col-total" rowspan="4">{{ __('messages.total_sessions') }}</th>
                                </tr>

                                {{-- LIGNE 2 : Sous-cat1 ou Notes directes --}}
                                <tr>
                                    @foreach($row2 as $item)
                                        @if($item['type'] === 'note')
                                            <th class="cat-note" rowspan="{{ $item['rowspan'] }}" colspan="{{ $item['colspan'] }}">
                                                <span class="note-name">{{ $item['name'] }}</span>
                                                <span class="note-score">/{{ $item['score'] }}</span>
                                            </th>
                                        @elseif($item['type'] === 'sub')
                                            <th class="cat-sub1" colspan="{{ $item['colspan'] }}" rowspan="{{ $item['rowspan'] }}">{{ $item['name'] }}</th>
                                        @elseif($item['type'] === 'empty')
                                            <th class="cat-sub1" rowspan="{{ $item['rowspan'] }}" colspan="{{ $item['colspan'] }}">{{ $item['name'] }}</th>
                                        @endif
                                    @endforeach
                                </tr>

                                {{-- LIGNE 3 : Sous-cat2 ou Notes de sub1 --}}
                                <tr>
                                    @foreach($row3 as $item)
                                        @if($item['type'] === 'note')
                                            <th class="cat-note" rowspan="{{ $item['rowspan'] }}" colspan="{{ $item['colspan'] }}">
                                                <span class="note-name">{{ $item['name'] }}</span>
                                                <span class="note-score">/{{ $item['score'] }}</span>
                                            </th>
                                        @elseif($item['type'] === 'sub')
                                            <th class="cat-sub2" colspan="{{ $item['colspan'] }}" rowspan="{{ $item['rowspan'] }}">{{ $item['name'] }}</th>
                                        @endif
                                    @endforeach
                                </tr>

                                {{-- LIGNE 4 : Notes finales --}}
                                <tr>
                                    @foreach($row4 as $item)
                                        <th class="cat-note">
                                            <span class="note-name">{{ $item['name'] }}</span>
                                            <span class="note-score">/{{ $item['score'] }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < 12; $i++)
                                    <tr class="student-row">
                                        <td class="student-name-cell"></td>
                                        @foreach($allColumns as $col)
                                            <td>
                                                @if(!empty($col['disabled']))
                                                    <input type="number" class="score-input" min="0" step="0.5" inputmode="decimal" data-max="0" disabled>
                                                @else
                                                    <input type="number" class="score-input" min="0" step="0.5" inputmode="decimal" data-max="{{ $col['max'] }}">
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="student-total" data-total-max="{{ $totalMax }}"></td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <p>{{ __('messages.no_categories_for_belt') }}</p>
                        </div>
                    @endif
                </div>

                {{-- SECTION GESTION --}}
                <div class="management-card no-print">
                    <div class="management-header">
                        <i class="fas fa-cogs me-2"></i>{{ __('messages.manage_categories') }}
                    </div>
                    <div class="p-4">
                        <form method="POST" action="{{ route('belt.notes.categories.store') }}" class="row g-2 mb-4">
                            @csrf
                            <input type="hidden" name="belt_name" value="{{ $beltName }}">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="{{ __('messages.category_name') }}" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="position" class="form-control" placeholder="{{ __('messages.position') }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-1"></i>{{ __('messages.add_category') }}
                                </button>
                            </div>
                        </form>

                        @php
                            $flatSubsForParentSelectByCategory = [];
                            foreach ($categories as $category) {
                                $flatForSelect = [];
                                $subs = $category->subcategories ?? collect();
                                
                                $walkForSelect = function (\App\Models\BeltNoteSubcategory $sub, string $path = '') use (&$walkForSelect, &$flatForSelect) {
                                    $fullPath = $path === '' ? $sub->name : ($path . ' › ' . $sub->name);
                                    $flatForSelect[] = ['subcategory' => $sub, 'label' => $fullPath];
                                    foreach (($sub->childrenRecursive ?? collect()) as $child) {
                                        $walkForSelect($child, $fullPath);
                                    }
                                };
                                
                                foreach ($subs as $sub) {
                                    $walkForSelect($sub, '');
                                }
                                $flatSubsForParentSelectByCategory[$category->id] = $flatForSelect;
                            }
                        @endphp

                        @foreach($categories as $category)
                            <div class="category-block">
                                <div class="category-header">
                                    <strong><i class="fas fa-folder me-2"></i>{{ $category->name }}</strong>
                                    <form method="POST" action="{{ route('belt.notes.categories.destroy', $category->id) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light"><i class="fas fa-trash text-danger"></i></button>
                                    </form>
                                </div>
                                <div class="p-3">
                                    <div class="mb-3">
                                        <h6 class="text-primary fw-bold"><i class="fas fa-sticky-note me-1"></i>Notes directes</h6>
                                        <div class="d-flex flex-wrap mb-2">
                                            @forelse($category->notes ?? [] as $note)
                                                <div class="note-badge">
                                                    <span class="score">/{{ $note->position ?? '-' }}</span>
                                                    <span>{{ $note->content }}</span>
                                                    <form method="POST" action="{{ route('belt.notes.notes.destroy', $note->id) }}" class="d-inline" onsubmit="return confirm('?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm text-danger p-0"><i class="fas fa-times"></i></button>
                                                    </form>
                                                </div>
                                            @empty
                                                <span class="text-muted fst-italic">Aucune note</span>
                                            @endforelse
                                        </div>
                                        <form method="POST" action="{{ route('belt.notes.categories.notes.store', $category->id) }}" class="row g-2">
                                            @csrf
                                            <div class="col-6"><input type="text" name="content" class="form-control form-control-sm" placeholder="Nouvelle note" required></div>
                                            <div class="col-3"><input type="number" name="position" class="form-control form-control-sm" placeholder="Barème (ex: 10)" min="0" step="0.5"></div>
                                            <div class="col-3"><button class="btn btn-sm btn-outline-success w-100"><i class="fas fa-plus"></i> Ajouter / إضافة</button></div>
                                        </form>
                                    </div>

                                    <div>
                                        <h6 class="text-success fw-bold"><i class="fas fa-sitemap me-1"></i>Sous-catégories</h6>
                                        @php
                                            $renderSubcategory = function (\App\Models\BeltNoteSubcategory $sub, int $level = 0) use (&$renderSubcategory) {
                                                $marginLeft = 15 + ($level * 20);

                                                echo '<div class="subcategory-block p-3" style="margin-left: ' . $marginLeft . 'px;">';
                                                echo '<div class="d-flex justify-content-between align-items-center mb-2">';
                                                echo '<strong>' . e($sub->name) . '</strong>';
                                                echo '<form method="POST" action="' . route('belt.notes.subcategories.destroy', $sub->id) . '" onsubmit="return confirm(\'' . __('messages.confirm_delete') . '\')">';
                                                echo csrf_field() . method_field('DELETE');
                                                echo '<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>';
                                                echo '</form>';
                                                echo '</div>';

                                                echo '<div class="d-flex flex-wrap mb-2">';
                                                $notes = $sub->notes ?? collect();
                                                if ($notes->count() > 0) {
                                                    foreach ($notes as $note) {
                                                        echo '<div class="note-badge">';
                                                        echo '<span class="score">/' . e($note->position ?? '-') . '</span>';
                                                        echo '<span>' . e($note->content) . '</span>';
                                                        echo '<form method="POST" action="' . route('belt.notes.notes.destroy', $note->id) . '" class="d-inline" onsubmit="return confirm(\'' . __('messages.confirm_delete') . '\')">';
                                                        echo csrf_field() . method_field('DELETE');
                                                        echo '<button class="btn btn-sm text-danger p-0"><i class="fas fa-times"></i></button>';
                                                        echo '</form>';
                                                        echo '</div>';
                                                    }
                                                } else {
                                                    echo '<span class="text-muted fst-italic">' . __('messages.no_notes') . '</span>';
                                                }
                                                echo '</div>';

                                                echo '<form method="POST" action="' . route('belt.notes.subcategories.notes.store', $sub->id) . '" class="row g-1">';
                                                echo csrf_field();
                                                echo '<div class="col-6"><input type="text" name="content" class="form-control form-control-sm" placeholder="' . __('messages.new_note') . '" required></div>';
                                                echo '<div class="col-3"><input type="number" name="position" class="form-control form-control-sm" placeholder="' . __('messages.position') . '" min="0" step="0.5"></div>';
                                                echo '<div class="col-3"><button class="btn btn-sm btn-outline-success w-100"><i class="fas fa-plus"></i></button></div>';
                                                echo '</form>';

                                                echo '</div>';

                                                foreach (($sub->childrenRecursive ?? collect()) as $child) {
                                                    $renderSubcategory($child, $level + 1);
                                                }
                                            };
                                        @endphp

                                        @foreach($category->subcategories ?? [] as $sub)
                                            @php $renderSubcategory($sub); @endphp
                                        @endforeach
                                        
                                        <form method="POST" action="{{ route('belt.notes.subcategories.store', $category->id) }}" class="row g-2 mt-2">
                                            @csrf
                                            <div class="col-12 col-md-5"><input type="text" name="name" class="form-control form-control-sm" placeholder="Nouvelle sous-catégorie" required></div>
                                            <div class="col-12 col-md-3">
                                                <select name="parent_id" class="form-select form-select-sm">
                                                    <option value="">-- Racine --</option>
                                                    @foreach(($flatSubsForParentSelectByCategory[$category->id] ?? []) as $entry)
                                                        <option value="{{ $entry['subcategory']->id }}">{{ $entry['label'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-2"><input type="number" name="position" class="form-control form-control-sm" placeholder="Position" min="0"></div>
                                            <div class="col-12 col-md-2"><button class="btn btn-sm btn-outline-primary w-100"><i class="fas fa-plus"></i> Ajouter / إضافة</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="grade-card">
                    <div class="p-5 text-center">
                        <i class="fas fa-hand-point-up fa-4x text-warning mb-3"></i>
                        <h4 class="text-muted">Sélectionnez une ceinture pour commencer</h4>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@if(!empty($beltName))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('addAthleteBtn');
    const validateBtn = document.getElementById('validateSheetBtn');
    const select = document.getElementById('eligibleAthleteSelect');

    function clampScore(input) {
        const max = Number(input.dataset.max || 0);
        let v = Number(input.value);
        if (!Number.isFinite(v)) v = 0;
        if (v < 0) v = 0;
        if (max > 0 && v > max) v = max;
        if (String(input.value).length && Number.isFinite(v)) {
            input.value = v;
        }
        return v;
    }

    function recalcRow(row) {
        let sum = 0;
        let anyFilled = false;
        row.querySelectorAll('input.score-input:not([disabled])').forEach(function(inp) {
            if (String(inp.value).trim() !== '') anyFilled = true;
            const v = clampScore(inp);
            if (Number.isFinite(v)) sum += v;
        });
        const totalCell = row.querySelector('.student-total');
        if (totalCell) {
            const totalMax = Number(totalCell.dataset.totalMax || 0);
            if (!anyFilled) {
                totalCell.textContent = '';
            } else if (totalMax > 0) {
                // Arrondi à 2 décimales pour éviter les problèmes de précision float en JS
                const displaySum = Number(Math.round(sum + 'e2') + 'e-2');
                totalCell.textContent = String(displaySum) + ' / ' + String(totalMax);
            } else {
                const displaySum = Number(Math.round(sum + 'e2') + 'e-2');
                totalCell.textContent = String(displaySum);
            }
        }
    }

    document.querySelectorAll('tr.student-row').forEach(function(row) {
        row.querySelectorAll('input.score-input').forEach(function(inp) {
            inp.addEventListener('input', function() { recalcRow(row); });
            inp.addEventListener('blur', function() { clampScore(inp); recalcRow(row); });
        });
    });

    function findFirstEmptyRow() {
        const rows = Array.from(document.querySelectorAll('tr.student-row'));
        return rows.find(function(r) {
            const nameCell = r.querySelector('.student-name-cell');
            return nameCell && nameCell.textContent.trim() === '';
        });
    }

    function userAlreadyAdded(userId) {
        return document.querySelector('tr.student-row[data-user-id="' + userId + '"]') !== null;
    }

    if (addBtn && select) {
        addBtn.addEventListener('click', function() {
            const userId = select.value;
            if (!userId) return;
            if (userAlreadyAdded(userId)) return;
            const row = findFirstEmptyRow();
            if (!row) return;
            const opt = select.querySelector('option[value="' + userId + '"]');
            const label = opt ? opt.textContent : '';
            row.dataset.userId = userId;
            const nameCell = row.querySelector('.student-name-cell');
            if (nameCell) nameCell.textContent = label;
            if (opt) opt.remove();
            select.value = '';
        });
    }

    if (validateBtn) {
        validateBtn.addEventListener('click', function() {
            document.querySelectorAll('tr.student-row').forEach(function(row) { recalcRow(row); });
        });
    }
});
</script>
@endif
@endsection