<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseAttendance;
use App\Models\Gym;
use App\Models\Schedule;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\Attendance;

class CoachController extends Controller
{
    private function beltOptions(): array
    {
        $opts = [];
        $opts['Blanche'] = __('messages.belt_white_label');
        foreach ([
            ['Jaune', 'yellow', 3],
            ['Verte', 'green', 3],
            ['Bleue', 'blue', 3],
            ['Violette', 'purple', 3],
            ['Rouge', 'red', 3],
        ] as [$fr, $colorKey, $n]) {
            for ($i = 1; $i <= $n; $i++) {
                $value = "$fr $i";
                $label = __('messages.belt_label', [
                    'color' => __("messages.belt_$colorKey"),
                    'level' => $i,
                ]);
                $opts[$value] = $label;
            }
        }
        foreach (range(1, 6) as $i) {
            $value = "Noire $i";
            $label = __('messages.belt_black_label', [
                'level' => $i,
            ]);
            $opts[$value] = $label;
        }
        return $opts;
    }

    private function beltOptionGroups(): array
    {
        $opts = $this->beltOptions();
        $groups = [];
        foreach ($opts as $value => $label) {
            $group = preg_replace('/\s+\d+$/', '', $value);
            $groups[$group][$value] = $label;
        }
        return $groups;
    }

    private function sanitizeCustomFolderName(string $name): string
    {
        $name = trim($name);
        $name = str_replace(['/', '\\'], '-', $name);
        $name = str_replace(['..', "\0"], '', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        $name = trim($name, ". \t\n\r\0\x0B");
        return $name !== '' ? $name : 'Dossier';
    }

    #=======================================================================================#
    #			                        list Function                                       #
    #=======================================================================================#
    public function list(Request $request)
    {
        $user = auth()->user();
        $query = User::role('coach')
            ->withoutBanned()
            ->with(['city', 'belts'])
            ->withCount([
                'TrainingSessions as sessions_count',
                'courseAttendances as presences_count' => function($q){
                    $q->where('course_attendances.status', 'present');
                },
                'courseAttendances as absences_count' => function($q){
                    $q->where('course_attendances.status', 'absent');
                }
            ]);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // City Filter
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Belt Filter
        if ($request->filled('belt')) {
            $query->whereHas('belts', function($q) use ($request) {
                $q->where('belt_name', $request->belt);
            });
        }

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $query->where('gym_id', $user->gym_id);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = strtolower((string) $request->get('direction', 'desc'));
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';

        if (in_array($sort, ['name', 'created_at'])) {
            $query->orderBy($sort, $direction);
        } elseif ($sort === 'sessions_count') {
            $query->orderBy('sessions_count', $direction);
        } elseif ($sort === 'presences_count') {
            $query->orderBy('presences_count', $direction);
        } elseif ($sort === 'absences_count') {
            $query->orderBy('absences_count', $direction);
        } elseif ($sort === 'attendance_rate') {
            $query->orderByRaw(
                "(CASE WHEN (presences_count + absences_count) = 0 THEN 0 ELSE (presences_count / (presences_count + absences_count)) END) {$direction}"
            );
        } else {
            $query->latest('id');
        }

        $coachesFromDB = $query->paginate(20)->withQueryString();
        
        $cities = [];
        if ($user->hasRole('admin')) {
             $cities = \App\Models\City::orderBy('name')->get();
        } elseif ($user->hasRole('cityManager')) {
             $cities = \App\Models\City::where('id', $user->city_id)->get();
        } elseif ($user->hasRole('gymManager') && $user->city_id) {
             $cities = \App\Models\City::where('id', $user->city_id)->get();
        }

        $belts = \App\Models\Belt::distinct()->pluck('belt_name')->filter()->sort()->values();

        if ($request->ajax()) {
            $html = view('coach.table_partial', ['coaches' => $coachesFromDB])->render();
            $pagination = method_exists($coachesFromDB, 'links')
                ? (string) $coachesFromDB->links('pagination::bootstrap-5')
                : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
            ]);
        }

        return view("coach.list", [
            'coaches' => $coachesFromDB,
            'cities' => $cities,
            'belts' => $belts
        ]);
    }

    private function checkCoachAccess($coachId)
    {
        $authUser = auth()->user();
        if (!$authUser) abort(401);

        $coach = User::role('coach')->findOrFail($coachId);

        if ($authUser->hasRole('admin')) {
            return $coach;
        }

        if ($authUser->hasRole('coach')) {
            if ((int) $authUser->id !== (int) $coach->id) {
                abort(403);
            }
            return $coach;
        }

        if ($authUser->hasRole('cityManager')) {
            if ($coach->city_id !== $authUser->city_id) {
                abort(403);
            }
            return $coach;
        }

        if ($authUser->hasRole('gymManager')) {
            if ($coach->gym_id !== $authUser->gym_id) {
                abort(403);
            }
            return $coach;
        }

        abort(403);
    }

    #=======================================================================================#
    #			                        show Function                                       #
    #=======================================================================================#
    public function show(Request $request, $id)
    {
        $singleCoach = $this->checkCoachAccess($id);
        
        $singleCoach->loadCount([
                'courseAttendances as total_presences' => function($q) {
                    $q->where('course_attendances.status', 'present');
                },
                'courseAttendances as total_absences' => function($q) {
                    $q->where('course_attendances.status', 'absent');
                }
            ]);

        $recentCourseAttendances = CourseAttendance::query()
            ->whereHas('participation', function ($q) use ($singleCoach) {
                $q->where('user_id', $singleCoach->id);
            })
            ->with(['participation.course'])
            ->orderByDesc('attendance_date')
            ->limit(10)
            ->get();

        $monthStr = $request->string('month')->toString();
        try {
            $monthStart = $monthStr ? \Carbon\Carbon::createFromFormat('Y-m', $monthStr)->startOfMonth() : \Carbon\Carbon::now()->startOfMonth();
        } catch (\Throwable $e) {
            $monthStart = \Carbon\Carbon::now()->startOfMonth();
        }
        $monthEnd = $monthStart->copy()->endOfMonth();

        $gridStart = $monthStart->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

        $calendarCourses = collect();
        $coachCourses = collect();
        if (Schema::hasTable('course_coach')) {
            $coachCourses = Course::query()
                ->whereHas('coaches', function ($q) use ($singleCoach) {
                    $q->where('users.id', $singleCoach->id);
                })
                ->with(['gym'])
                ->orderByDesc('start_date')
                ->orderByDesc('id')
                ->get();

            $calendarCourses = Course::query()
                ->whereHas('coaches', function ($q) use ($singleCoach) {
                    $q->where('users.id', $singleCoach->id);
                })
                ->whereNotNull('start_date')
                ->where(function ($q) use ($monthStart, $monthEnd) {
                    $q->whereDate('start_date', '<=', $monthEnd->toDateString())
                        ->where(function ($q2) use ($monthStart) {
                            $q2->whereNull('end_date')
                                ->orWhereDate('end_date', '>=', $monthStart->toDateString());
                        });
                })
                ->with(['gym'])
                ->orderBy('start_date')
                ->get();
        }

        $dayToIso = [
            'lundi' => 1,
            'monday' => 1,
            'mar' => 2,
            'mardi' => 2,
            'tuesday' => 2,
            'mer' => 3,
            'mercredi' => 3,
            'wednesday' => 3,
            'jeu' => 4,
            'jeudi' => 4,
            'thursday' => 4,
            'ven' => 5,
            'vendredi' => 5,
            'friday' => 5,
            'sam' => 6,
            'samedi' => 6,
            'saturday' => 6,
            'dim' => 7,
            'dimanche' => 7,
            'sunday' => 7,
        ];

        $calendarSessionsMap = [];
        $calendarSessionCount = 0;
        foreach ($calendarCourses as $course) {
            $courseStart = $course->start_date ? $course->start_date->copy()->startOfDay() : null;
            if (!$courseStart) {
                continue;
            }

            $courseEnd = $course->end_date ? $course->end_date->copy()->endOfDay() : null;
            $isInMonthWindow = $courseStart->lessThanOrEqualTo($monthEnd) && (!$courseEnd || $courseEnd->greaterThanOrEqualTo($monthStart));
            if (!$isInMonthWindow) {
                continue;
            }

            $rangeStart = $courseStart->greaterThan($gridStart) ? $courseStart->copy() : $gridStart->copy();
            $effectiveCourseEnd = $courseEnd ?: $gridEnd->copy()->endOfDay();
            $rangeEnd = $effectiveCourseEnd->lessThan($gridEnd) ? $effectiveCourseEnd->copy() : $gridEnd->copy()->endOfDay();
            if ($rangeEnd->lessThan($rangeStart)) {
                continue;
            }

            $schedules = collect();
            if (Schema::hasTable('schedules')) {
                if (Schema::hasColumn('schedules', 'course_id')) {
                    $schedules = Schedule::query()
                        ->with(['group'])
                        ->where('course_id', $course->id)
                        ->get();

                    if ($schedules->count() <= 0 && Schema::hasTable('group_user') && Schema::hasTable('participations')) {
                        $groupIds = DB::table('group_user')
                            ->join('participations', 'participations.user_id', '=', 'group_user.user_id')
                            ->where('participations.course_id', $course->id)
                            ->distinct()
                            ->pluck('group_user.group_id')
                            ->map(fn ($gid) => (int) $gid)
                            ->all();

                        if (!empty($groupIds)) {
                            $schedules = Schedule::query()
                                ->with(['group'])
                                ->whereNull('course_id')
                                ->whereIn('group_id', $groupIds)
                                ->get();
                        }
                    }
                } elseif (Schema::hasTable('group_user') && Schema::hasTable('participations')) {
                    $groupIds = DB::table('group_user')
                        ->join('participations', 'participations.user_id', '=', 'group_user.user_id')
                        ->where('participations.course_id', $course->id)
                        ->distinct()
                        ->pluck('group_user.group_id')
                        ->map(fn ($gid) => (int) $gid)
                        ->all();

                    if (!empty($groupIds)) {
                        $schedules = Schedule::query()
                            ->with(['group'])
                            ->whereIn('group_id', $groupIds)
                            ->get();
                    }
                }
            }

            foreach ($schedules as $schedule) {
                $dayKey = strtolower(trim((string) $schedule->day));
                $isoDow = $dayToIso[$dayKey] ?? null;
                if (!$isoDow) {
                    continue;
                }

                $firstCursor = $rangeStart->copy()->startOfDay();
                $deltaDays = ($isoDow - (int) $firstCursor->dayOfWeekIso + 7) % 7;
                $sessionDate = $firstCursor->copy()->addDays($deltaDays);
                while ($sessionDate->lessThanOrEqualTo($rangeEnd)) {
                    $key = $sessionDate->toDateString();
                    $calendarSessionsMap[$key] = $calendarSessionsMap[$key] ?? [];

                    $startTime = $schedule->start_time ? substr((string) $schedule->start_time, 0, 5) : null;
                    $endTime = $schedule->end_time ? substr((string) $schedule->end_time, 0, 5) : null;
                    $timeRange = trim(($startTime ?: '') . ($endTime ? '–' . $endTime : ''));

                    $calendarSessionsMap[$key][] = [
                        'course_id' => $course->id,
                        'course_name' => $course->name,
                        'gym_name' => optional($course->gym)->name ?: null,
                        'group_name' => optional($schedule->group)->name ?: null,
                        'time' => $timeRange ?: null,
                        'location' => $schedule->location ?: null,
                        'notes' => $schedule->notes ?: null,
                        'start_date' => $course->start_date ? $course->start_date->format('Y-m-d') : null,
                        'end_date' => $course->end_date ? $course->end_date->format('Y-m-d') : null,
                    ];
                    $calendarSessionCount++;
                    $sessionDate->addWeek();
                }
            }
        }

        $calendarWeeks = [];
        $cursor = $gridStart->copy();
        while ($cursor->lessThanOrEqualTo($gridEnd)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $cursor->copy();
                $key = $day->toDateString();
                $items = collect($calendarSessionsMap[$key] ?? [])
                    ->sortBy(function ($s) {
                        $timeKey = isset($s['time']) && $s['time'] ? (int) str_replace([':', '–'], '', (string) $s['time']) : 999999;
                        $nameKey = isset($s['course_name']) && $s['course_name'] ? strtolower((string) $s['course_name']) : '';
                        return ($timeKey * 1000) . '|' . $nameKey;
                    })
                    ->values()
                    ->all();

                $week[] = [
                    'date' => $key,
                    'day' => $day->day,
                    'in_month' => $day->month === $monthStart->month,
                    'is_today' => $day->isToday(),
                    'sessions' => $items,
                ];
                $cursor->addDay();
            }
            $calendarWeeks[] = $week;
        }

        return view("coach.show", [
            'singleCoach' => $singleCoach,
            'coachCourses' => $coachCourses,
            'recentAttendances' => $recentCourseAttendances,
            'calendarMonth' => $monthStart->format('Y-m'),
            'calendarMonthLabel' => $monthStart->format('F Y'),
            'calendarPrevMonth' => $monthStart->copy()->subMonth()->format('Y-m'),
            'calendarNextMonth' => $monthStart->copy()->addMonth()->format('Y-m'),
            'calendarWeeks' => $calendarWeeks,
            'calendarSessionCount' => $calendarSessionCount,
        ]);
    }

    public function report(Request $request, $id)
    {
        $month = $request->string('month')->toString();
        $categoryId = $request->filled('category_id') ? $request->integer('category_id') : null;
        $coach = User::withoutBanned()->with(['city','gym'])->findOrFail($id);
        if ($month) {
            [$year, $mon] = explode('-', $month);
            $start = \Carbon\Carbon::createFromDate((int)$year, (int)$mon, 1)->startOfDay();
            $end = \Carbon\Carbon::createFromDate((int)$year, (int)$mon, 1)->endOfMonth()->endOfDay();
        } else {
            $start = \Carbon\Carbon::now()->startOfMonth();
            $end = \Carbon\Carbon::now()->endOfMonth();
            $month = $start->format('Y-m');
        }

        $expensesQuery = \App\Models\Expense::query()
            ->where('coach_id', $coach->id)
            ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])
            ->with(['category','gym'])
            ->orderByDesc('expense_date');
        if ($categoryId) { $expensesQuery->where('expense_category_id', $categoryId); }
        $expenses = $expensesQuery->get();
        $expensesByCategory = $expenses->groupBy(function($e){ return optional($e->category)->name ?: '—'; })
            ->map(function($grp){ return (int) $grp->sum('amount_cents'); });
        $expensesTotal = (int) $expenses->sum('amount_cents');

        $attendances = \App\Models\CourseAttendance::whereHas('participation', function($q) use ($coach) {
                $q->where('user_id', $coach->id);
            })
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw("DATE(attendance_date) as day, SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as presents, SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absents")
            ->groupBy('day')
            ->orderBy('day','desc')
            ->get();

        $coursesCount = (int) Course::query()
            ->whereHas('coaches', function ($q) use ($coach) {
                $q->where('users.id', $coach->id);
            })
            ->count();

        $createdAt = $coach->created_at ? $coach->created_at->copy()->startOfMonth() : \Carbon\Carbon::now()->startOfMonth();
        $endMonth = \Carbon\Carbon::now()->endOfMonth();
        $salaryCategoryIds = \App\Models\ExpenseCategory::query()
            ->where(function($q){
                $q->whereIn('name', ['Coach','Paie Coach','Salaire Coach','Salaire'])
                  ->orWhere('name','like','%coach%')
                  ->orWhere('name','like','%salaire%')
                  ->orWhere('name','like','%paie%');
            })
            ->pluck('id');
        $salarySubcategoryIds = \App\Models\ExpenseSubcategory::query()
            ->where(function($q){
                $q->where('name','like','%coach%')
                  ->orWhere('name','like','%salaire%')
                  ->orWhere('name','like','%paie%');
            })
            ->pluck('id');
        $salaryExpenses = \App\Models\Expense::query()
            ->where('coach_id', $coach->id)
            ->where(function($q) use ($salaryCategoryIds, $salarySubcategoryIds){
                $q->whereIn('expense_category_id', $salaryCategoryIds)
                  ->orWhereIn('expense_subcategory_id', $salarySubcategoryIds);
            })
            ->get();
        $paidMap = $salaryExpenses->reduce(function($carry,$e){
            $key = \Carbon\Carbon::parse($e->expense_date)->format('Y-m');
            $carry[$key] = true;
            return $carry;
        }, []);
        $coachPaidMonths = [];
        $coachUnpaidMonths = [];
        $cursor = $createdAt->copy();
        while ($cursor->lessThanOrEqualTo($endMonth)) {
            $key = $cursor->format('Y-m');
            if (isset($paidMap[$key])) {
                $coachPaidMonths[] = $key;
            } else {
                $coachUnpaidMonths[] = $key;
            }
            $cursor->addMonth();
        }

        $categories = \App\Models\ExpenseCategory::orderBy('name')->get(['id','name']);

        return view('coach.report', compact('coach','month','expenses','expensesByCategory','expensesTotal','attendances','coursesCount','coachPaidMonths','coachUnpaidMonths','categories','categoryId'));
    }

    #=======================================================================================#
    #			                        create Function                                     #
    #=======================================================================================#
    public function create()
    {
        $user = auth()->user();
        $cities = City::query();
        
        if ($user->hasRole('cityManager')) {
            $cities->where('id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $cities->where('id', $user->city_id);
        }
        
        $cities = $cities->get();
        $beltList = \App\Models\Belt::distinct()->pluck('belt_name')->filter()->toArray();
        
        return view('coach.create', [
            'cities' => $cities,
            'beltList' => $beltList,
        ]);
    }
    #=======================================================================================#
    #			                        store Function                                      #
    #=======================================================================================#
    public function store(Request $request)
    {
        $user = auth()->user();
        $cityIdRule = 'required|exists:cities,id';
        
        if ($user->hasRole('cityManager') || $user->hasRole('gymManager')) {
            if ($request->city_id != $user->city_id) {
                return back()->withErrors(['city_id' => 'Vous ne pouvez pas assigner un coach à une autre ville.'])->withInput();
            }
        }

        $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'phone' => ['required', 'string', 'unique:users,phone', 'regex:/^[0-9+\-\s()]+$/'],
            'profile_image' => ['nullable', 'mimes:jpg,jpeg'],
            'city_id' => $cityIdRule,
            'belt_name' => ['nullable', 'string'],
            'training_grade' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('profile_image') == null) {
            $imageName = 'imgs/defaultImg.jpg';
        } else {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'imgs/' . $name;
        }

        $user_new = new User();
        $user_new->name = $request->name;
        $user_new->phone = $request->phone;
        $user_new->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user_new->city_id = $request->city_id;
        
        if ($user->hasRole('gymManager')) {
            $user_new->gym_id = $user->gym_id;
        }
        
        $user_new->profile_image = $imageName;
        $user_new->assignRole('coach');
        $user_new->save();

        if ($request->filled('belt_name') || $request->filled('training_grade')) {
            \App\Models\Belt::create([
                'belt_name' => $request->belt_name,
                'training_grade' => $request->training_grade,
                'user_id' => $user_new->id,
                'achieved_at' => now(),
            ]);
        }

        return redirect()->route('coach.list');
    }

    #=======================================================================================#
    #			                        Edit Function                                       #
    #=======================================================================================#
    public function edit($id)
    {
        $singleCoach = $this->checkCoachAccess($id);
        $user = auth()->user();
        
        $cities = City::query();
        if ($user->hasRole('cityManager') || $user->hasRole('gymManager')) {
            $cities->where('id', $user->city_id);
        }
        $cities = $cities->get();

        $beltGroups = $this->beltOptionGroups();
        $beltOptions = $this->beltOptions();
        $otherLabel = 'Autres / أخرى';
        $dbBeltNames = \App\Models\Belt::query()
            ->select('belt_name')
            ->distinct()
            ->whereNotNull('belt_name')
            ->where('belt_name', '!=', '')
            ->orderBy('belt_name')
            ->pluck('belt_name')
            ->all();

        foreach ($dbBeltNames as $beltName) {
            if (!array_key_exists($beltName, $beltOptions)) {
                $beltGroups[$otherLabel][$beltName] = $beltName;
            }
        }
        
        return view("coach.edit", [
            'coach' => $singleCoach, 
            'cities' => $cities,
            'beltGroups' => $beltGroups,
        ]);
    }

    #=======================================================================================#
    #			                        Update Function                                     #
    #=======================================================================================#
    public function update(Request $request, $id)
    {
        $coach = $this->checkCoachAccess($id);
        $authUser = auth()->user();

        $validated = $request->validate([
            'name' => 'required|max:50',
            'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/|unique:users,phone,' . $coach->id,
            'city_id' => 'required|exists:cities,id',
            'profile_image' => 'nullable|mimes:jpg,jpeg',
            'belt_name' => 'nullable|string',
            'training_grade' => 'nullable|string',
            'custom_folder_name' => 'nullable|string|max:120',
            'custom_files' => 'nullable|array',
            'custom_files.*' => 'file|mimes:pdf,png,jpg,jpeg|max:10240',
        ]);

        if ($authUser->hasRole('cityManager') || $authUser->hasRole('gymManager')) {
            if ($request->city_id != $authUser->city_id) {
                return back()->withErrors(['city_id' => 'Vous ne pouvez pas assigner un coach à une autre ville.'])->withInput();
            }
        }

        $coach->name = $request->name;
        $coach->phone = $request->phone;
        $coach->city_id = $request->city_id;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'imgs/' . $name;
            if ($coach->profile_image && File::exists(public_path($coach->profile_image)) && $coach->profile_image !== 'imgs/defaultImg.jpg') {
                File::delete(public_path($coach->profile_image));
            }
            $coach->profile_image = $imageName;
        }

        $coach->save();

        if ($request->filled('belt_name') || $request->filled('training_grade')) {
            $coach->belts()->updateOrCreate(
                ['user_id' => $coach->id],
                [
                    'belt_name' => $request->belt_name,
                    'training_grade' => $request->training_grade,
                    'achieved_at' => now()
                ]
            );
        }

        if ($request->hasFile('custom_files')) {
            $folderInput = trim((string) $request->input('custom_folder_name', ''));
            $folderPath = $this->sanitizeCustomFolderName($folderInput);
            $folderLabel = $folderInput !== '' ? $folderInput : $folderPath;
            $files = (array) $request->file('custom_files');

            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }
                $ext = strtolower((string) $file->getClientOriginalExtension());
                $name = time() . '_' . \Illuminate\Support\Str::random(12) . ($ext ? '.' . $ext : '');
                $path = $file->storeAs("private/users/{$coach->id}/custom/{$folderPath}", $name);

                \App\Models\UserDocument::create([
                    'user_id' => $coach->id,
                    'type' => 'custom',
                    'file_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'notes' => json_encode([
                        'folder' => $folderLabel,
                        'original' => $file->getClientOriginalName(),
                    ], JSON_UNESCAPED_UNICODE),
                ]);
            }
        }

        return redirect()->route('coach.list')->with('success', __('messages.coach_updated'));
    }

    #=======================================================================================#
    #			                        Delete Function                                     #
    #=======================================================================================#
    public function deleteCoach($id)
    {
        $singleCoach = $this->checkCoachAccess($id);
        $singleCoach->delete();
        return response()->json(['success' => __('messages.coach_deleted')]);
    }
}
