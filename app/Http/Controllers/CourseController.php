<?php

namespace App\Http\Controllers;

use App\Models\Belt;
use App\Models\Category;
use App\Models\City;
use App\Models\Course;
use App\Models\Group;
use App\Models\Gym;
use App\Models\Participation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function list(Request $request)
    {
        $allowedGymIds = $this->resolveAllowedGymIds();
        $query = Course::with('gym');
        if (is_array($allowedGymIds)) {
            $query->whereIn('gym_id', $allowedGymIds);
        }

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('gym_id')) {
            $query->where('gym_id', $request->integer('gym_id'));
        }

        if ($request->filled('from')) {
            $query->whereDate('start_date', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('end_date', '<=', $request->date('to'));
        }

        $courses = $query->orderBy('start_date', 'desc')->paginate(10)->appends($request->query());

        $gymsQuery = Gym::query()->orderBy('name')->select(['id', 'name']);
        if (is_array($allowedGymIds)) {
            $gymsQuery->whereIn('id', $allowedGymIds);
        }
        $gyms = $gymsQuery->get();
        return view('course.list', compact('courses', 'gyms'));
    }

    public function create()
    {
        $allowedGymIds = $this->resolveAllowedGymIds();
        $gymsQuery = Gym::query()->orderBy('name')->select(['id', 'name']);
        if (is_array($allowedGymIds)) {
            $gymsQuery->whereIn('id', $allowedGymIds);
        }
        $gyms = $gymsQuery->get();
        $coaches = \App\Models\User::role('coach')->orderBy('name')->get(['id','name']);
        $groups = Group::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $beltNames = Belt::select('belt_name')->distinct()->orderBy('belt_name')->pluck('belt_name');
        $users = User::role('user')->orderBy('name')->get(['id', 'name']);
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        return view('course.create', compact('gyms','coaches', 'groups', 'categories', 'beltNames', 'users', 'days'));
    }

    public function store(Request $request)
    {
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $allowedGymIds = $this->resolveAllowedGymIds();
        $allowedBeltNames = Belt::select('belt_name')->distinct()->pluck('belt_name')->all();
        $gymIdRules = ['nullable', 'exists:gyms,id'];
        if (is_array($allowedGymIds)) {
            $gymIdRules[] = Rule::in($allowedGymIds);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'gym_id' => $gymIdRules,
            'coach_ids' => ['nullable','array'],
            'coach_ids.*' => ['integer','exists:users,id'],
            'enroll_mode' => ['nullable', 'string', 'in:groups,categories,belts,individual'],
            'group_ids' => ['nullable', 'array'],
            'group_ids.*' => ['integer', 'exists:groups,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'belt_names' => ['nullable', 'array'],
            'belt_names.*' => ['string'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'schedules' => ['nullable', 'array'],
            'schedules.*.day' => ['nullable', 'string', 'in:' . implode(',', $days)],
            'schedules.*.start_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.end_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.location' => ['nullable', 'string', 'max:255'],
            'schedules.*.notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validator->after(function ($v) use ($days, $allowedBeltNames) {
            $data = $v->getData();
            $groupIds = $data['group_ids'] ?? [];
            $categoryIds = $data['category_ids'] ?? [];
            $beltNames = $data['belt_names'] ?? [];
            $userIds = $data['user_ids'] ?? [];
            $slots = $data['schedules'] ?? [];

            $selected = 0;
            if (!empty($groupIds)) { $selected++; }
            if (!empty($categoryIds)) { $selected++; }
            if (!empty($beltNames)) { $selected++; }
            if (!empty($userIds)) { $selected++; }
            if ($selected > 1) {
                $v->errors()->add('enroll_mode', __('messages.choose_enroll_mode_error'));
            }

            foreach ($beltNames as $i => $beltName) {
                $beltName = trim((string) $beltName);
                if ($beltName === '') {
                    continue;
                }
                if (!in_array($beltName, $allowedBeltNames, true)) {
                    $v->errors()->add("belt_names.$i", __('messages.invalid_belt_error'));
                }
            }

            $hasAnySlot = false;
            foreach ($slots as $i => $slot) {
                $day = trim((string)($slot['day'] ?? ''));
                $start = trim((string)($slot['start_time'] ?? ''));
                $end = trim((string)($slot['end_time'] ?? ''));
                $location = trim((string)($slot['location'] ?? ''));
                $notes = trim((string)($slot['notes'] ?? ''));

                $hasAny = $day !== '' || $start !== '' || $end !== '' || $location !== '' || $notes !== '';
                if (!$hasAny) {
                    continue;
                }
                $hasAnySlot = true;

                if ($day === '') {
                    $v->errors()->add("schedules.$i.day", __('messages.day_required'));
                } elseif (!in_array($day, $days, true)) {
                    $v->errors()->add("schedules.$i.day", __('messages.invalid_day'));
                }

                if ($start === '') {
                    $v->errors()->add("schedules.$i.start_time", __('messages.start_time_required'));
                }
                if ($end === '') {
                    $v->errors()->add("schedules.$i.end_time", __('messages.end_time_required'));
                }

                if ($start !== '' && $end !== '' && $start >= $end) {
                    $v->errors()->add("schedules.$i.end_time", __('messages.end_time_after_start_time'));
                }
            }

            if ($hasAnySlot && empty($groupIds)) {
                $v->errors()->add('group_ids', __('messages.select_group_for_schedule'));
            }
        });

        $validated = $validator->validate();
        if (is_array($allowedGymIds) && empty($validated['gym_id']) && count($allowedGymIds) === 1) {
            $validated['gym_id'] = $allowedGymIds[0];
        }

        $course = null;
        DB::transaction(function () use ($validated, &$course) {
            $course = Course::create(collect($validated)->except(['coach_ids', 'group_ids', 'schedules'])->all());
            if (!empty($validated['coach_ids'])) {
                $course->coaches()->sync($validated['coach_ids']);
            }

            $groupIds = $validated['group_ids'] ?? [];
            if (!empty($groupIds)) {
                foreach ($groupIds as $groupId) {
                    $group = Group::find($groupId);
                    if (!$group) {
                        continue;
                    }
                    $users = $group->users()->withoutBanned()->get(['users.id']);
                    foreach ($users as $u) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break 2; // Exit both loops
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => $u->id, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                }

                $slots = $validated['schedules'] ?? [];
                $slots = collect($slots)->filter(function ($slot) {
                    $day = trim((string)($slot['day'] ?? ''));
                    $start = trim((string)($slot['start_time'] ?? ''));
                    $end = trim((string)($slot['end_time'] ?? ''));
                    $location = trim((string)($slot['location'] ?? ''));
                    $notes = trim((string)($slot['notes'] ?? ''));
                    return $day !== '' || $start !== '' || $end !== '' || $location !== '' || $notes !== '';
                })->values();

                if ($slots->isNotEmpty()) {
                    if (!Schema::hasColumn('schedules', 'course_id')) {
                        Schedule::whereIn('group_id', $groupIds)->delete();
                    }
                    foreach ($groupIds as $groupId) {
                        foreach ($slots as $slot) {
                            $scheduleData = [
                                'group_id' => (int) $groupId,
                                'day' => $slot['day'],
                                'start_time' => $slot['start_time'],
                                'end_time' => $slot['end_time'],
                                'location' => $slot['location'] ?? null,
                                'notes' => $slot['notes'] ?? null,
                            ];
                            if (Schema::hasColumn('schedules', 'course_id')) {
                                $scheduleData['course_id'] = (int) $course->id;
                            }
                            Schedule::create($scheduleData);
                        }
                    }
                }
            } else {
                $categoryIds = $validated['category_ids'] ?? [];
                $beltNames = $validated['belt_names'] ?? [];
                $userIds = $validated['user_ids'] ?? [];

                if (!empty($categoryIds)) {
                    $users = User::role('user')
                        ->withoutBanned()
                        ->whereIn('category_id', $categoryIds)
                        ->get(['id']);
                    foreach ($users as $u) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break;
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => $u->id, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                } elseif (!empty($beltNames)) {
                    $users = User::role('user')
                        ->withoutBanned()
                        ->whereHas('belts', function ($q) use ($beltNames) {
                            $q->whereIn('belt_name', $beltNames);
                        })
                        ->select('users.id')
                        ->distinct()
                        ->get();
                    foreach ($users as $u) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break;
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => $u->id, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                } elseif (!empty($userIds)) {
                    foreach ($userIds as $userId) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break;
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => (int) $userId, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                }
            }
        });

        return redirect()->route('course.show', $course->id)->with('status', __('messages.course_created'));
    }

    public function show(Course $course)
    {
        $this->ensureCourseAccessible($course);
        $weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $weekDayIndex = array_flip($weekDays);

        $course->load(['gym', 'coaches']);
        $course->loadCount(['participations', 'waitlists']);

        $userWaitlist = null;
        $isParticipating = false;
        if (auth()->check()) {
            $userWaitlist = \App\Models\Waitlist::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->first();
            $isParticipating = \App\Models\Participation::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->exists();
        }

        $participationStatusCounts = $course->participations()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $attendanceBaseQuery = \App\Models\CourseAttendance::query()
            ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
            ->where('participations.course_id', $course->id);

        $lastAttendanceDate = (clone $attendanceBaseQuery)->max('course_attendances.attendance_date');

        $attendanceStatusCounts = (clone $attendanceBaseQuery)
            ->selectRaw('course_attendances.status, COUNT(*) as total')
            ->groupBy('course_attendances.status')
            ->pluck('total', 'course_attendances.status')
            ->all();

        $recentAttendanceDates = (clone $attendanceBaseQuery)
            ->select('course_attendances.attendance_date')
            ->distinct()
            ->orderByDesc('course_attendances.attendance_date')
            ->limit(8)
            ->pluck('course_attendances.attendance_date')
            ->map(fn ($d) => (string) $d)
            ->all();

        $recentAttendanceSessions = collect();
        if (!empty($recentAttendanceDates)) {
            $recentAttendanceSessions = \App\Models\CourseAttendance::query()
                ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
                ->where('participations.course_id', $course->id)
                ->whereIn('course_attendances.attendance_date', $recentAttendanceDates)
                ->whereNull('course_attendances.deleted_at')
                ->select('course_attendances.attendance_date')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN course_attendances.status = 'present' THEN 1 ELSE 0 END) as present")
                ->selectRaw("SUM(CASE WHEN course_attendances.status = 'absent' THEN 1 ELSE 0 END) as absent")
                ->selectRaw("SUM(CASE WHEN course_attendances.status = 'excused' THEN 1 ELSE 0 END) as excused")
                ->groupBy('course_attendances.attendance_date')
                ->orderByDesc('course_attendances.attendance_date')
                ->get();
        }

        $lastSessionAttendances = collect();
        if ($lastAttendanceDate) {
            $lastSessionAttendances = \App\Models\CourseAttendance::with(['participation.user'])
                ->whereHas('participation', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->where('attendance_date', $lastAttendanceDate)
                ->get()
                ->sortBy(function ($attendance) {
                    return strtolower((string) (optional(optional($attendance->participation)->user)->name ?? ''));
                })
                ->values();
        }

        $groupIds = DB::table('group_user')
            ->join('participations', 'participations.user_id', '=', 'group_user.user_id')
            ->where('participations.course_id', $course->id)
            ->distinct()
            ->pluck('group_user.group_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $schedulesByDay = collect();
        if (!empty($groupIds)) {
            $schedulesQuery = Schedule::with('group')->whereIn('group_id', $groupIds);
            if (Schema::hasColumn('schedules', 'course_id')) {
                $schedules = (clone $schedulesQuery)->where('course_id', $course->id)->get();
            } else {
                $schedules = $schedulesQuery->get();
            }

            $schedules = $schedules->sortBy(function ($s) use ($weekDayIndex) {
                $dayKey = $weekDayIndex[$s->day] ?? 999;
                $timeKey = (int) str_replace(':', '', (string) $s->start_time);
                return ($dayKey * 1000000) + $timeKey;
            });

            $schedulesByDay = $schedules->groupBy('day');
        }

        $plannedSessions = $this->buildPlannedSessionsForCourse($course, $groupIds);
        $plannedSessionsByMonth = $plannedSessions->groupBy(function($session) {
            return \Illuminate\Support\Carbon::parse($session['date'])->locale('fr')->translatedFormat('F Y');
        });

        return view('course.show', compact(
            'course',
            'participationStatusCounts',
            'attendanceStatusCounts',
            'lastAttendanceDate',
            'recentAttendanceSessions',
            'lastSessionAttendances',
            'weekDays',
            'schedulesByDay',
            'plannedSessionsByMonth',
            'userWaitlist',
            'isParticipating'
        ));
    }

    public function edit(Course $course)
    {
        $this->ensureCourseAccessible($course);
        $allowedGymIds = $this->resolveAllowedGymIds();
        $gymsQuery = Gym::query()->orderBy('name')->select(['id', 'name']);
        if (is_array($allowedGymIds)) {
            $gymsQuery->whereIn('id', $allowedGymIds);
        }
        $gyms = $gymsQuery->get();
        $coaches = \App\Models\User::role('coach')->orderBy('name')->get(['id','name']);
        $users = User::role('user')->orderBy('name')->get(['id', 'name']);
        $selectedCoaches = [];
        if (Schema::hasTable('course_coach')) {
            $selectedCoaches = $course->coaches()->pluck('id')->all();
        }
        $selectedUserIds = $course->participations()->pluck('user_id')->all();
        $groups = Group::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $beltNames = Belt::select('belt_name')->distinct()->orderBy('belt_name')->pluck('belt_name');
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        $defaultGroupIds = DB::table('group_user')
            ->join('participations', 'participations.user_id', '=', 'group_user.user_id')
            ->where('participations.course_id', $course->id)
            ->distinct()
            ->pluck('group_user.group_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $selectedGroupIds = collect(old('group_ids', $defaultGroupIds))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $scheduleSlots = old('schedules');
        if ($scheduleSlots === null) {
            $scheduleSlots = [];
            if (!empty($selectedGroupIds)) {
                $existingSchedulesQuery = Schedule::whereIn('group_id', $selectedGroupIds);
                if (Schema::hasColumn('schedules', 'course_id')) {
                    $existingSchedules = (clone $existingSchedulesQuery)->where('course_id', $course->id)->get(['day', 'start_time', 'end_time', 'location', 'notes']);
                } else {
                    $existingSchedules = $existingSchedulesQuery->get(['day', 'start_time', 'end_time', 'location', 'notes']);
                }

                $scheduleSlots = $existingSchedules
                    ->map(function ($s) {
                        return [
                            'day' => (string) $s->day,
                            'start_time' => substr((string) $s->start_time, 0, 5),
                            'end_time' => substr((string) $s->end_time, 0, 5),
                            'location' => $s->location ?? '',
                            'notes' => $s->notes ?? '',
                        ];
                    })
                    ->unique(function ($slot) {
                        return implode('|', [
                            $slot['day'] ?? '',
                            $slot['start_time'] ?? '',
                            $slot['end_time'] ?? '',
                            $slot['location'] ?? '',
                            $slot['notes'] ?? '',
                        ]);
                    })
                    ->values()
                    ->all();
            }

            if (empty($scheduleSlots)) {
                $scheduleSlots = [['day' => '', 'start_time' => '', 'end_time' => '', 'location' => '', 'notes' => '']];
            }
        }

        $plannedSessions = $this->buildPlannedSessionsForCourse($course, $selectedGroupIds);

        return view('course.edit', compact('course', 'gyms', 'coaches', 'selectedCoaches', 'groups', 'categories', 'beltNames', 'users', 'selectedUserIds', 'days', 'selectedGroupIds', 'scheduleSlots', 'plannedSessions'));
    }

    public function update(Request $request, Course $course)
    {
        $this->ensureCourseAccessible($course);
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $allowedGymIds = $this->resolveAllowedGymIds();
        $gymIdRules = ['nullable', 'exists:gyms,id'];
        if (is_array($allowedGymIds)) {
            $gymIdRules[] = Rule::in($allowedGymIds);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'gym_id' => $gymIdRules,
            'coach_ids' => ['nullable','array'],
            'coach_ids.*' => ['integer','exists:users,id'],
            'enroll_mode' => ['nullable', 'string', 'in:groups,categories,belts,individual'],
            'group_ids' => ['nullable', 'array'],
            'group_ids.*' => ['integer', 'exists:groups,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'belt_names' => ['nullable', 'array'],
            'belt_names.*' => ['string'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'schedules' => ['nullable', 'array'],
            'schedules.*.day' => ['nullable', 'string', 'in:' . implode(',', $days)],
            'schedules.*.start_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.end_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.location' => ['nullable', 'string', 'max:255'],
            'schedules.*.notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validator->after(function ($v) use ($days) {
            $data = $v->getData();
            $groupIds = $data['group_ids'] ?? [];
            $categoryIds = $data['category_ids'] ?? [];
            $beltNames = $data['belt_names'] ?? [];
            $userIds = $data['user_ids'] ?? [];
            $slots = $data['schedules'] ?? [];

            $selected = 0;
            if (!empty($groupIds)) { $selected++; }
            if (!empty($categoryIds)) { $selected++; }
            if (!empty($beltNames)) { $selected++; }
            if (!empty($userIds)) { $selected++; }
            if ($selected > 1) {
                $v->errors()->add('enroll_mode', __('messages.choose_enroll_mode_error'));
            }

            $hasAnySlot = false;
            foreach ($slots as $i => $slot) {
                $day = trim((string)($slot['day'] ?? ''));
                $start = trim((string)($slot['start_time'] ?? ''));
                $end = trim((string)($slot['end_time'] ?? ''));
                $location = trim((string)($slot['location'] ?? ''));
                $notes = trim((string)($slot['notes'] ?? ''));

                $hasAny = $day !== '' || $start !== '' || $end !== '' || $location !== '' || $notes !== '';
                if (!$hasAny) {
                    continue;
                }
                $hasAnySlot = true;

                if ($day === '') {
                    $v->errors()->add("schedules.$i.day", __('messages.day_required'));
                } elseif (!in_array($day, $days, true)) {
                    $v->errors()->add("schedules.$i.day", __('messages.invalid_day'));
                }

                if ($start === '') {
                    $v->errors()->add("schedules.$i.start_time", __('messages.start_time_required'));
                }
                if ($end === '') {
                    $v->errors()->add("schedules.$i.end_time", __('messages.end_time_required'));
                }

                if ($start !== '' && $end !== '' && $start >= $end) {
                    $v->errors()->add("schedules.$i.end_time", __('messages.end_time_after_start_time'));
                }
            }

            if ($hasAnySlot && empty($groupIds)) {
                $v->errors()->add('group_ids', __('messages.select_group_for_schedule'));
            }
        });

        $validated = $validator->validate();
        if (is_array($allowedGymIds) && empty($validated['gym_id']) && count($allowedGymIds) === 1) {
            $validated['gym_id'] = $allowedGymIds[0];
        }

        DB::transaction(function () use ($validated, $course) {
            $course->update(collect($validated)->except(['coach_ids', 'group_ids', 'schedules'])->all());

            if (Schema::hasTable('course_coach')) {
                $course->coaches()->sync($validated['coach_ids'] ?? []);
            }

            $groupIds = collect($validated['group_ids'] ?? [])
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->unique()
                ->values()
                ->all();

            if (Schema::hasColumn('schedules', 'course_id')) {
                Schedule::where('course_id', $course->id)->delete();
            }

            if (!empty($groupIds)) {
                foreach ($groupIds as $groupId) {
                    $group = Group::find($groupId);
                    if (!$group) {
                        continue;
                    }
                    $users = $group->users()->withoutBanned()->get(['users.id']);
                    foreach ($users as $u) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break 2; // Exit both loops
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => $u->id, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                }

                $slots = collect($validated['schedules'] ?? [])
                    ->filter(function ($slot) {
                        $day = trim((string)($slot['day'] ?? ''));
                        $start = trim((string)($slot['start_time'] ?? ''));
                        $end = trim((string)($slot['end_time'] ?? ''));
                        $location = trim((string)($slot['location'] ?? ''));
                        $notes = trim((string)($slot['notes'] ?? ''));
                        return $day !== '' || $start !== '' || $end !== '' || $location !== '' || $notes !== '';
                    })
                    ->values();

                if ($slots->isNotEmpty()) {
                    if (!Schema::hasColumn('schedules', 'course_id')) {
                        Schedule::whereIn('group_id', $groupIds)->delete();
                    }
                    foreach ($groupIds as $groupId) {
                        foreach ($slots as $slot) {
                            $scheduleData = [
                                'group_id' => (int) $groupId,
                                'day' => $slot['day'],
                                'start_time' => $slot['start_time'],
                                'end_time' => $slot['end_time'],
                                'location' => $slot['location'] ?? null,
                                'notes' => $slot['notes'] ?? null,
                            ];
                            if (Schema::hasColumn('schedules', 'course_id')) {
                                $scheduleData['course_id'] = (int) $course->id;
                            }
                            Schedule::create($scheduleData);
                        }
                    }
                }
            } else {
                $categoryIds = $validated['category_ids'] ?? [];
                $beltNames = $validated['belt_names'] ?? [];
                $userIds = $validated['user_ids'] ?? [];

                if (!empty($categoryIds)) {
                    $users = User::role('user')
                        ->withoutBanned()
                        ->whereIn('category_id', $categoryIds)
                        ->get(['id']);
                    foreach ($users as $u) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break;
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => $u->id, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                } elseif (!empty($beltNames)) {
                    $users = User::role('user')
                        ->withoutBanned()
                        ->whereHas('belts', function ($q) use ($beltNames) {
                            $q->whereIn('belt_name', $beltNames);
                        })
                        ->select('users.id')
                        ->distinct()
                        ->get();
                    foreach ($users as $u) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break;
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => $u->id, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                } elseif (!empty($userIds)) {
                    foreach ($userIds as $userId) {
                        if ($course->capacity) {
                            $currentCount = Participation::where('course_id', $course->id)->count();
                            if ($currentCount >= $course->capacity) {
                                break;
                            }
                        }
                        Participation::updateOrCreate(
                            ['user_id' => (int) $userId, 'course_id' => (int) $course->id],
                            ['status' => 'registered', 'participated_at' => now()]
                        );
                    }
                }
            }
        });

        return redirect()->route('course.show', $course->id)->with('status', __('messages.course_updated'));
    }

    public function destroy(Course $course)
    {
        $this->ensureCourseAccessible($course);
        $course->delete();
        return response()->json(['message' => __('messages.course_deleted')]);
    }

    private function resolveAllowedGymIds(): ?array
    {
        $user = auth()->user();
        if (!$user) {
            return [];
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('gymManager')) {
            return $user->gym_id ? [(int) $user->gym_id] : [];
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('cityManager')) {
            $cityIds = City::query()
                ->where('manager_id', $user->id)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            if (empty($cityIds) && $user->city_id) {
                $cityIds = [(int) $user->city_id];
            }

            if (empty($cityIds)) {
                return [];
            }

            return Gym::query()
                ->whereIn('city_id', $cityIds)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return null;
    }

    private function ensureCourseAccessible(Course $course): void
    {
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('coach')) {
            if (!Schema::hasTable('course_coach')) {
                abort(404);
            }
            $assigned = $course->coaches()->where('users.id', $user->id)->exists();
            if (!$assigned) {
                abort(404);
            }
        }

        $allowedGymIds = $this->resolveAllowedGymIds();
        if (!is_array($allowedGymIds)) {
            return;
        }

        $courseGymId = $course->gym_id ? (int) $course->gym_id : null;
        if (!$courseGymId || !in_array($courseGymId, $allowedGymIds, true)) {
            abort(404);
        }
    }

    private function buildPlannedSessionsForCourse(Course $course, array $groupIds)
    {
        $groupIds = collect($groupIds)->map(fn ($id) => (int) $id)->filter()->unique()->values()->all();
        if (empty($groupIds)) {
            return collect();
        }

        $start = $course->start_date ? \Illuminate\Support\Carbon::parse($course->start_date)->startOfDay() : now()->startOfDay();
        $end = $course->end_date ? \Illuminate\Support\Carbon::parse($course->end_date)->endOfDay() : now()->addDays(30)->endOfDay();

        if ($end->lt($start)) {
            [$start, $end] = [$end, $start];
        }

        $maxDays = 366;
        if ($start->diffInDays($end) > $maxDays) {
            $end = $start->copy()->addDays($maxDays)->endOfDay();
        }

        $weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        $schedulesQuery = Schedule::with('group')->whereIn('group_id', $groupIds);
        if (Schema::hasColumn('schedules', 'course_id')) {
            $schedules = (clone $schedulesQuery)->where('course_id', $course->id)->get();
        } else {
            $schedules = $schedulesQuery->get();
        }

        $schedules = $schedules->groupBy('day');

        $events = collect();
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $dayName = $weekDays[$cursor->dayOfWeekIso - 1] ?? null;
            if ($dayName !== null) {
                $daySchedules = $schedules->get($dayName, collect());
                foreach ($daySchedules as $sch) {
                    $events->push([
                        'date' => $cursor->toDateString(),
                        'day' => $dayName,
                        'start_time' => substr((string) $sch->start_time, 0, 5),
                        'end_time' => substr((string) $sch->end_time, 0, 5),
                        'group' => $sch->group ? (string) $sch->group->name : '',
                        'location' => $sch->location ?? '',
                        'notes' => $sch->notes ?? '',
                    ]);
                }
            }
            $cursor->addDay();
        }

        return $events->sortBy(function ($e) {
            return ($e['date'] ?? '') . ' ' . ($e['start_time'] ?? '');
        })->values();
    }
}
