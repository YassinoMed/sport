@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="premium-title">
                        <i class="fas fa-edit text-primary me-2"></i>
                        <span class="label-fr">Modifier le Cours</span>
                        <span class="label-ar" dir="rtl">تعديل الدورة</span>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="modern-card">
                <div class="card-header-premium">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations du cours
                    </h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('course.update', $course->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="label-fr">Nom du cours</span>
                                    <span class="label-ar" dir="rtl">اسم الدورة</span>
                                </label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $course->name) }}" required placeholder="Ex: Judo Débutant">
                                @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="label-fr">Capacité maximale (optionnel)</span>
                                    <span class="label-ar" dir="rtl">السعة القصوى (اختياري)</span>
                                </label>
                                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $course->capacity) }}" min="1" placeholder="Ex: 20">
                                <small class="text-muted">Laissez vide pour une capacité illimitée.</small>
                                @error('capacity')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <span class="label-fr">Description</span>
                                <span class="label-ar" dir="rtl">الوصف</span>
                            </label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Détails sur le cours...">{{ old('description', $course->description) }}</textarea>
                            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="label-fr">Salle (Gym)</span>
                                    <span class="label-ar" dir="rtl">القاعة</span>
                                </label>
                                <select name="gym_id" class="form-control select2">
                                    <option value="">Sélectionner une salle (optionnel)</option>
                                    @foreach($gyms as $gym)
                                        <option value="{{ $gym->id }}" {{ old('gym_id', $course->gym_id) == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                    @endforeach
                                </select>
                                @error('gym_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="label-fr">Coachs</span>
                                    <span class="label-ar" dir="rtl">المدربون</span>
                                </label>
                                <select name="coach_ids[]" class="form-control select2" multiple data-placeholder="Choisir les coachs">
                                    @foreach(($coaches ?? []) as $coach)
                                        <option value="{{ $coach->id }}" {{ in_array($coach->id, (array)old('coach_ids', ($selectedCoaches ?? []))) ? 'selected' : '' }}>{{ $coach->name }}</option>
                                    @endforeach
                                </select>
                                @error('coach_ids')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="label-fr">Date de début</span>
                                    <span class="label-ar" dir="rtl">تاريخ البدء</span>
                                </label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($course->start_date)->format('Y-m-d')) }}">
                                @error('start_date')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="label-fr">Date de fin</span>
                                    <span class="label-ar" dir="rtl">تاريخ الانتهاء</span>
                                </label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($course->end_date)->format('Y-m-d')) }}">
                                @error('end_date')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label d-block">
                                <span class="label-fr">Mode d'inscription</span>
                                <span class="label-ar" dir="rtl">طريقة التسجيل</span>
                            </label>
                            <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded-3 border">
                                @php($enrollMode = old('enroll_mode', $course->enroll_mode ?? 'groups'))
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_groups" value="groups" {{ $enrollMode === 'groups' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_groups">
                                        <span class="label-fr">Par groupes</span>
                                        <span class="label-ar" dir="rtl">بالمجموعات</span>
                                    </label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_categories" value="categories" {{ $enrollMode === 'categories' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_categories">
                                        <span class="label-fr">Par catégories</span>
                                        <span class="label-ar" dir="rtl">بالفئات</span>
                                    </label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_belts" value="belts" {{ $enrollMode === 'belts' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_belts">
                                        <span class="label-fr">Par ceintures</span>
                                        <span class="label-ar" dir="rtl">بالأحزمة</span>
                                    </label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="enroll_mode" id="enroll_mode_individual" value="individual" {{ $enrollMode === 'individual' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="enroll_mode_individual">
                                        <span class="label-fr">Individuelle</span>
                                        <span class="label-ar" dir="rtl">فردي</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="enroll_block_individual">
                            <label class="form-label">
                                <span class="label-fr">Étudiants</span>
                                <span class="label-ar" dir="rtl">الطلاب</span>
                            </label>
                            <select name="user_ids[]" class="form-control select2" multiple data-placeholder="Sélectionner les étudiants">
                                @foreach(($users ?? []) as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, (array)old('user_ids', ($selectedUserIds ?? []))) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_ids')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3" id="enroll_block_groups">
                            <label class="form-label">
                                <span class="label-fr">Groupes</span>
                                <span class="label-ar" dir="rtl">المجموعات</span>
                            </label>
                            <select name="group_ids[]" class="form-control select2" multiple data-placeholder="Sélectionner les groupes">
                                @foreach(($groups ?? []) as $group)
                                    <option value="{{ $group->id }}" {{ in_array($group->id, (array)old('group_ids', ($selectedGroupIds ?? []))) ? 'selected' : '' }}>{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('group_ids')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3" id="enroll_block_categories">
                            <label class="form-label">
                                <span class="label-fr">Catégories</span>
                                <span class="label-ar" dir="rtl">الفئات</span>
                            </label>
                            <select name="category_ids[]" class="form-control select2" multiple data-placeholder="Sélectionner les catégories">
                                @foreach(($categories ?? []) as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, (array)old('category_ids', ($selectedCategoryIds ?? []))) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_ids')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3" id="enroll_block_belts">
                            <label class="form-label">
                                <span class="label-fr">Ceintures</span>
                                <span class="label-ar" dir="rtl">الأحزمة</span>
                            </label>
                            <select name="belt_names[]" class="form-control select2" multiple data-placeholder="Sélectionner les ceintures">
                                @foreach(($beltNames ?? []) as $beltName)
                                    <option value="{{ $beltName }}" {{ in_array($beltName, (array)old('belt_names', ($selectedBeltNames ?? []))) ? 'selected' : '' }}>{{ $beltName }}</option>
                                @endforeach
                            </select>
                            @error('belt_names')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div id="schedules_section" class="mt-4">
                            <div class="modern-card border-primary shadow-sm">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span class="label-fr">Planning hebdomadaire</span>
                                        <span class="label-ar" dir="rtl">الجدول الأسبوعي</span>
                                    </h5>
                                    <button type="button" class="btn btn-sm btn-light" id="add-schedule-row">
                                        <i class="fas fa-plus me-1"></i>
                                        <span class="label-fr">Ajouter un créneau</span>
                                        <span class="label-ar" dir="rtl">إضافة حصة</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="schedules-container">
                                        @php($slots = old('schedules', $scheduleSlots ?? []))
                                        @foreach($slots as $i => $slot)
                                            <div class="row g-2 align-items-end schedule-row mb-3 p-3 border rounded-3 bg-white shadow-xs" data-index="{{ $i }}">
                                                <div class="col-md-2">
                                                    <label class="form-label fs-13">
                                                        <span class="label-fr">Jour</span>
                                                        <span class="label-ar" dir="rtl">اليوم</span>
                                                    </label>
                                                    <select name="schedules[{{ $i }}][day]" class="form-control form-control-sm">
                                                        <option value=""></option>
                                                        @foreach(($days ?? []) as $day)
                                                            <option value="{{ $day }}" {{ ($slot['day'] ?? '') === $day ? 'selected' : '' }}>{{ $day }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label fs-13">
                                                        <span class="label-fr">Début</span>
                                                        <span class="label-ar" dir="rtl">البداية</span>
                                                    </label>
                                                    <input type="time" name="schedules[{{ $i }}][start_time]" class="form-control form-control-sm" value="{{ $slot['start_time'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label fs-13">
                                                        <span class="label-fr">Fin</span>
                                                        <span class="label-ar" dir="rtl">النهاية</span>
                                                    </label>
                                                    <input type="time" name="schedules[{{ $i }}][end_time]" class="form-control form-control-sm" value="{{ $slot['end_time'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label fs-13">
                                                        <span class="label-fr">Lieu</span>
                                                        <span class="label-ar" dir="rtl">المكان</span>
                                                    </label>
                                                    <input type="text" name="schedules[{{ $i }}][location]" class="form-control form-control-sm" value="{{ $slot['location'] ?? '' }}" placeholder="Salle...">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fs-13">
                                                        <span class="label-fr">Notes</span>
                                                        <span class="label-ar" dir="rtl">ملاحظات</span>
                                                    </label>
                                                    <input type="text" name="schedules[{{ $i }}][notes]" class="form-control form-control-sm" value="{{ $slot['notes'] ?? '' }}" placeholder="Remarques...">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-schedule-row"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="modern-card mt-4 shadow-sm border-0">
                                <div class="card-header-premium bg-light py-2 px-3 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 text-dark font-weight-bold">
                                        <i class="fas fa-history me-2"></i>
                                        <span class="label-fr">Calendrier des séances planifiées</span>
                                        <span class="label-ar" dir="rtl">جدول الحصص المخطط لها</span>
                                    </h6>
                                    <button type="button" class="btn btn-primary-modern btn-sm py-1" id="refresh-planned-sessions">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        <span class="label-fr">Actualiser</span>
                                        <span class="label-ar" dir="rtl">تحديث</span>
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    @php($events = $plannedSessions ?? collect())
                                    <div class="p-4 text-center text-muted" id="planned-sessions-empty" style="{{ $events->isEmpty() ? '' : 'display:none;' }}">
                                        <i class="fas fa-calendar-times fa-2x mb-2 opacity-50"></i>
                                        <p class="mb-0">
                                            <span class="label-fr">Aucune séance planifiée.</span>
                                            <span class="label-ar" dir="rtl">لا توجد حصص مخططة.</span>
                                        </p>
                                    </div>
                                    <div class="table-responsive" id="planned-sessions-table-wrap" style="{{ $events->isEmpty() ? 'display:none;' : '' }}">
                                        <table class="table table-hover table-sm mb-0" id="planned-sessions-table">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="px-3">
                                                        <span class="label-fr">Date</span>
                                                        <span class="label-ar" dir="rtl">التاريخ</span>
                                                    </th>
                                                    <th>
                                                        <span class="label-fr">Jour</span>
                                                        <span class="label-ar" dir="rtl">اليوم</span>
                                                    </th>
                                                    <th>
                                                        <span class="label-fr">Heure</span>
                                                        <span class="label-ar" dir="rtl">الوقت</span>
                                                    </th>
                                                    <th>
                                                        <span class="label-fr">Groupe</span>
                                                        <span class="label-ar" dir="rtl">المجموعة</span>
                                                    </th>
                                                    <th>
                                                        <span class="label-fr">Lieu</span>
                                                        <span class="label-ar" dir="rtl">المكان</span>
                                                    </th>
                                                    <th class="px-3 text-right">
                                                        <span class="label-fr">Notes</span>
                                                        <span class="label-ar" dir="rtl">ملاحظات</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="planned-sessions-tbody">
                                                @foreach($events as $e)
                                                    <tr>
                                                        <td class="px-3 font-weight-bold text-primary">{{ \Illuminate\Support\Carbon::parse($e['date'])->format('d M Y') }}</td>
                                                        <td class="text-muted">{{ $e['day'] ?? '-' }}</td>
                                                        <td><span class="badge badge-info px-2 py-1">{{ ($e['start_time'] ?? '-') }} - {{ ($e['end_time'] ?? '-') }}</span></td>
                                                        <td>{{ $e['group'] ?? '-' }}</td>
                                                        <td><i class="fas fa-map-marker-alt text-danger me-1 small"></i>{{ ($e['location'] ?? '') !== '' ? $e['location'] : '-' }}</td>
                                                        <td class="px-3 text-right text-muted italic">{{ ($e['notes'] ?? '') !== '' ? $e['notes'] : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex gap-2">
                            <button type="submit" class="btn btn-primary-modern px-5">
                                <i class="fas fa-save me-2"></i>
                                <span class="label-fr">Mettre à jour</span>
                                <span class="label-ar" dir="rtl">تحديث</span>
                            </button>
                            <a href="{{ route('course.list') }}" class="btn btn-outline-secondary px-4">
                                <span class="label-fr">Annuler</span>
                                <span class="label-ar" dir="rtl">إلغاء</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        $(function () {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: "Sélectionner...",
                allowClear: true,
                width: '100%'
            });

            const modeRadios = $('input[name="enroll_mode"]');
            const blocks = {
                'groups': $('#enroll_block_groups'),
                'categories': $('#enroll_block_categories'),
                'belts': $('#enroll_block_belts'),
                'individual': $('#enroll_block_individual')
            };
            const schedulesSection = $('#schedules_section');

            function toggleEnrollBlocks(animate = true) {
                const mode = $('input[name="enroll_mode"]:checked').val();
                
                // Toggle enrollment blocks with animation
                Object.keys(blocks).forEach(key => {
                    const block = blocks[key];
                    if (key === mode) {
                        if (animate) block.slideDown(300); else block.show();
                        block.find('select, input').prop('disabled', false);
                    } else {
                        if (animate) block.slideUp(300); else block.hide();
                        block.find('select, input').prop('disabled', true);
                    }
                });
                
                // Show/Hide schedules section based on mode
                if (mode === 'groups') {
                    if (animate) schedulesSection.slideDown(300); else schedulesSection.show();
                } else {
                    if (animate) schedulesSection.slideUp(300); else schedulesSection.hide();
                }
            }

            modeRadios.on('change', function() {
                toggleEnrollBlocks(true);
            });

            // Initial toggle without animation
            toggleEnrollBlocks(false);
        });

        (function () {
            var days = @json($days ?? []);
            var container = document.getElementById('schedules-container');
            var addBtn = document.getElementById('add-schedule-row');
            var refreshBtn = document.getElementById('refresh-planned-sessions');
            var startDateInput = document.querySelector('input[name="start_date"]');
            var endDateInput = document.querySelector('input[name="end_date"]');
            var groupSelect = document.querySelector('select[name="group_ids[]"]');
            var plannedEmpty = document.getElementById('planned-sessions-empty');
            var plannedWrap = document.getElementById('planned-sessions-table-wrap');
            var plannedTbody = document.getElementById('planned-sessions-tbody');

            if (!container || !addBtn) return;

            function nextIndex() {
                var rows = container.querySelectorAll('.schedule-row');
                var max = -1;
                rows.forEach(function (row) {
                    var idx = parseInt(row.getAttribute('data-index') || '-1', 10);
                    if (!isNaN(idx) && idx > max) max = idx;
                });
                return max + 1;
            }

            function buildDayOptions() {
                var options = '<option value=""></option>';
                for (var i = 0; i < days.length; i++) {
                    options += '<option value="' + String(days[i]).replace(/"/g, '&quot;') + '">' + days[i] + '</option>';
                }
                return options;
            }

            function addRow(animate = true) {
                var i = nextIndex();
                var row = document.createElement('div');
                row.className = 'row g-2 align-items-end schedule-row mb-3 p-3 border rounded-3 bg-white shadow-xs';
                if (animate) row.style.display = 'none';
                row.setAttribute('data-index', String(i));
                row.innerHTML =
                    '<div class="col-md-2">' +
                        '<label class="form-label fs-13">' +
                            '<span class="label-fr">Jour</span>' +
                            '<span class="label-ar" dir="rtl">اليوم</span>' +
                        '</label>' +
                        '<select name="schedules[' + i + '][day]" class="form-control form-control-sm">' +
                            buildDayOptions() +
                        '</select>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label fs-13">' +
                            '<span class="label-fr">Début</span>' +
                            '<span class="label-ar" dir="rtl">البداية</span>' +
                        '</label>' +
                        '<input type="time" name="schedules[' + i + '][start_time]" class="form-control form-control-sm">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label fs-13">' +
                            '<span class="label-fr">Fin</span>' +
                            '<span class="label-ar" dir="rtl">النهاية</span>' +
                        '</label>' +
                        '<input type="time" name="schedules[' + i + '][end_time]" class="form-control form-control-sm">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label fs-13">' +
                            '<span class="label-fr">Lieu</span>' +
                            '<span class="label-ar" dir="rtl">المكان</span>' +
                        '</label>' +
                        '<input type="text" name="schedules[' + i + '][location]" class="form-control form-control-sm" placeholder="Salle...">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                        '<label class="form-label fs-13">' +
                            '<span class="label-fr">Notes</span>' +
                            '<span class="label-ar" dir="rtl">ملاحظات</span>' +
                        '</label>' +
                        '<input type="text" name="schedules[' + i + '][notes]" class="form-control form-control-sm" placeholder="Remarques...">' +
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<button type="button" class="btn btn-outline-danger btn-sm w-100 remove-schedule-row"><i class="fas fa-trash"></i></button>' +
                    '</div>';
                container.appendChild(row);
                if (animate) $(row).fadeIn(300);
            }

            addBtn.addEventListener('click', addRow);
            
            container.addEventListener('click', function (e) {
                var btn = e.target.closest('.remove-schedule-row');
                if (btn) {
                    var row = btn.closest('.schedule-row');
                    $(row).fadeOut(300, function() {
                        row.remove();
                        scheduleRefresh();
                    });
                }
            });

            function parseDateValue(v) {
                if (!v) return null;
                var parts = String(v).split('-');
                if (parts.length !== 3) return null;
                var y = parseInt(parts[0], 10);
                var m = parseInt(parts[1], 10);
                var d = parseInt(parts[2], 10);
                if (isNaN(y) || isNaN(m) || isNaN(d)) return null;
                return new Date(y, m - 1, d);
            }

            function pad2(n) {
                return (n < 10 ? '0' : '') + String(n);
            }

            function formatDateForTable(dateObj) {
                var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return pad2(dateObj.getDate()) + ' ' + months[dateObj.getMonth()] + ' ' + String(dateObj.getFullYear());
            }

            function getSelectedGroups() {
                if (!groupSelect) return [];
                var selected = [];
                for (var i = 0; i < groupSelect.options.length; i++) {
                    var opt = groupSelect.options[i];
                    if (opt && opt.selected) {
                        selected.push({ id: opt.value, name: opt.text || '' });
                    }
                }
                return selected;
            }

            function getSlotsFromForm() {
                var slots = [];
                container.querySelectorAll('.schedule-row').forEach(function (row) {
                    var dayEl = row.querySelector('select[name$="[day]"]');
                    var startEl = row.querySelector('input[type="time"][name$="[start_time]"]');
                    var endEl = row.querySelector('input[type="time"][name$="[end_time]"]');
                    var locationEl = row.querySelector('input[type="text"][name$="[location]"]');
                    var notesEl = row.querySelector('input[type="text"][name$="[notes]"]');
                    var day = (dayEl && dayEl.value) ? dayEl.value : '';
                    var startTime = (startEl && startEl.value) ? startEl.value : '';
                    var endTime = (endEl && endEl.value) ? endEl.value : '';
                    var location = (locationEl && locationEl.value) ? locationEl.value : '';
                    var notes = (notesEl && notesEl.value) ? notesEl.value : '';

                    if (day === '' && startTime === '' && endTime === '' && location === '' && notes === '') {
                        return;
                    }
                    if (day === '' || startTime === '' || endTime === '') {
                        return;
                    }
                    slots.push({
                        day: day,
                        start_time: startTime,
                        end_time: endTime,
                        location: location,
                        notes: notes
                    });
                });
                return slots;
            }

            function renderPlannedSessions(events) {
                if (!plannedEmpty || !plannedWrap || !plannedTbody) return;

                plannedTbody.innerHTML = '';
                if (!events || !events.length) {
                    plannedEmpty.style.display = '';
                    plannedWrap.style.display = 'none';
                    return;
                }

                plannedEmpty.style.display = 'none';
                plannedWrap.style.display = '';

                events.forEach(function (e) {
                    var tr = document.createElement('tr');
                    var tdDate = document.createElement('td');
                    tdDate.textContent = e.dateLabel || '-';
                    var tdDay = document.createElement('td');
                    tdDay.textContent = e.day || '-';
                    var tdTime = document.createElement('td');
                    tdTime.textContent = (e.start_time || '-') + ' - ' + (e.end_time || '-');
                    var tdGroup = document.createElement('td');
                    tdGroup.textContent = e.group || '-';
                    var tdLoc = document.createElement('td');
                    tdLoc.textContent = (e.location || '') !== '' ? e.location : '-';
                    var tdNotes = document.createElement('td');
                    tdNotes.textContent = (e.notes || '') !== '' ? e.notes : '-';
                    tr.appendChild(tdDate);
                    tr.appendChild(tdDay);
                    tr.appendChild(tdTime);
                    tr.appendChild(tdGroup);
                    tr.appendChild(tdLoc);
                    tr.appendChild(tdNotes);
                    plannedTbody.appendChild(tr);
                });
            }

            function buildPlannedSessionsFromForm() {
                var startDate = parseDateValue(startDateInput ? startDateInput.value : '');
                var endDate = parseDateValue(endDateInput ? endDateInput.value : '');
                if (!startDate || !endDate) {
                    return [];
                }
                if (startDate.getTime() > endDate.getTime()) {
                    var tmp = startDate;
                    startDate = endDate;
                    endDate = tmp;
                }

                var selectedGroups = getSelectedGroups();
                if (!selectedGroups.length) {
                    return [];
                }
                var slots = getSlotsFromForm();
                if (!slots.length) {
                    return [];
                }

                var maxDays = 366;
                var cursor = new Date(startDate.getTime());
                var limitEnd = new Date(endDate.getTime());
                var maxEnd = new Date(startDate.getTime());
                maxEnd.setDate(maxEnd.getDate() + maxDays);
                if (limitEnd.getTime() > maxEnd.getTime()) {
                    limitEnd = maxEnd;
                }

                var weekdayNamesByJs = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                var events = [];
                while (cursor.getTime() <= limitEnd.getTime()) {
                    var dayName = weekdayNamesByJs[cursor.getDay()];
                    for (var si = 0; si < slots.length; si++) {
                        var slot = slots[si];
                        if (slot.day !== dayName) continue;
                        for (var gi = 0; gi < selectedGroups.length; gi++) {
                            events.push({
                                dateKey: String(cursor.getFullYear()) + '-' + pad2(cursor.getMonth() + 1) + '-' + pad2(cursor.getDate()),
                                dateLabel: formatDateForTable(cursor),
                                day: dayName,
                                start_time: slot.start_time,
                                end_time: slot.end_time,
                                group: selectedGroups[gi].name || '-',
                                location: slot.location || '',
                                notes: slot.notes || ''
                            });
                        }
                    }
                    cursor.setDate(cursor.getDate() + 1);
                }

                events.sort(function (a, b) {
                    var ak = (a.dateKey || '') + ' ' + (a.start_time || '');
                    var bk = (b.dateKey || '') + ' ' + (b.start_time || '');
                    if (ak < bk) return -1;
                    if (ak > bk) return 1;
                    return 0;
                });
                return events;
            }

            function refreshPlannedSessions() {
                renderPlannedSessions(buildPlannedSessionsFromForm());
            }

            if (refreshBtn) {
                refreshBtn.addEventListener('click', refreshPlannedSessions);
            }

            var refreshTimer = null;
            function scheduleRefresh() {
                if (refreshTimer) window.clearTimeout(refreshTimer);
                refreshTimer = window.setTimeout(function () {
                    refreshPlannedSessions();
                }, 80);
            }
            if (startDateInput) startDateInput.addEventListener('change', scheduleRefresh);
            if (endDateInput) endDateInput.addEventListener('change', scheduleRefresh);
            if (groupSelect) groupSelect.addEventListener('change', scheduleRefresh);
            container.addEventListener('change', scheduleRefresh);
            container.addEventListener('input', scheduleRefresh);

            refreshPlannedSessions();
        })();
    </script>
@endpush
