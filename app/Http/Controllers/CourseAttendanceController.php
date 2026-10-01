<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAttendance;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CourseAttendanceController extends Controller
{
    /**
     * Afficher la liste des cours avec leurs statistiques de présence
     */
    public function index(Request $request)
    {
        $selectedGroupId = $request->query('group_id');
        $selectedGroup = $selectedGroupId ? \App\Models\Group::find($selectedGroupId) : null;

        $groups = \App\Models\Group::query()
            ->withCount('users')
            ->orderBy('name')
            ->get();

        $enrolledCounts = collect();
        if (Schema::hasTable('group_user') && Schema::hasTable('participations')) {
            $enrolledCounts = DB::table('group_user')
                ->join('participations', 'participations.user_id', '=', 'group_user.user_id')
                ->select('group_user.group_id')
                ->selectRaw('COUNT(DISTINCT participations.user_id) as enrolled_users')
                ->groupBy('group_user.group_id')
                ->pluck('enrolled_users', 'group_user.group_id');
        }

        foreach ($groups as $group) {
            $group->enrolled_users = (int) ($enrolledCounts[$group->id] ?? 0);
        }

        if (!$selectedGroup) {
            return view('course-attendance.index', [
                'viewMode' => 'groups',
                'groups' => $groups,
                'selectedGroup' => null,
                'courses' => null,
            ]);
        }

        $courseIds = collect();
        if (Schema::hasTable('group_user') && Schema::hasTable('participations')) {
            $courseIds = Participation::query()
                ->join('group_user', 'participations.user_id', '=', 'group_user.user_id')
                ->where('group_user.group_id', $selectedGroup->id)
                ->distinct()
                ->pluck('participations.course_id');
        }

        $courseIds = $courseIds->map(fn ($id) => (int) $id)->filter()->unique()->values()->all();

        $participantsCounts = collect();
        $statusCounts = collect();
        $lastAttendanceDates = collect();

        if (!empty($courseIds) && Schema::hasTable('group_user') && Schema::hasTable('participations')) {
            $participantsCounts = Participation::query()
                ->join('group_user', 'participations.user_id', '=', 'group_user.user_id')
                ->where('group_user.group_id', $selectedGroup->id)
                ->whereIn('participations.course_id', $courseIds)
                ->select('participations.course_id')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('participations.course_id')
                ->pluck('total', 'participations.course_id');

            $statusCounts = CourseAttendance::query()
                ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
                ->join('group_user', 'participations.user_id', '=', 'group_user.user_id')
                ->where('group_user.group_id', $selectedGroup->id)
                ->whereIn('participations.course_id', $courseIds)
                ->select('participations.course_id')
                ->selectRaw('SUM(CASE WHEN course_attendances.status = "present" THEN 1 ELSE 0 END) AS presents')
                ->selectRaw('SUM(CASE WHEN course_attendances.status = "absent" THEN 1 ELSE 0 END) AS absents')
                ->selectRaw('SUM(CASE WHEN course_attendances.status = "excused" THEN 1 ELSE 0 END) AS excused')
                ->groupBy('participations.course_id')
                ->get()
                ->keyBy('course_id');

            $lastAttendanceDates = CourseAttendance::query()
                ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
                ->join('group_user', 'participations.user_id', '=', 'group_user.user_id')
                ->where('group_user.group_id', $selectedGroup->id)
                ->whereIn('participations.course_id', $courseIds)
                ->select('participations.course_id')
                ->selectRaw('MAX(course_attendances.attendance_date) as last')
                ->groupBy('participations.course_id')
                ->pluck('last', 'participations.course_id');
        }

        $courses = Course::with('gym')
            ->whereIn('id', $courseIds)
            ->orderByDesc('start_date')
            ->paginate(10)
            ->withQueryString();

        foreach ($courses as $course) {
            $course->total_participants = (int) ($participantsCounts[$course->id] ?? 0);
            $course->presents = (int) (optional($statusCounts->get($course->id))->presents ?? 0);
            $course->absents = (int) (optional($statusCounts->get($course->id))->absents ?? 0);
            $course->excused = (int) (optional($statusCounts->get($course->id))->excused ?? 0);
            $course->last_attendance_date = isset($lastAttendanceDates[$course->id])
                ? \Carbon\Carbon::parse($lastAttendanceDates[$course->id])
                : null;
        }

        return view('course-attendance.index', [
            'viewMode' => 'courses',
            'groups' => $groups,
            'selectedGroup' => $selectedGroup,
            'courses' => $courses,
        ]);
    }

    /**
     * Afficher la liste des présences pour un cours spécifique
     */
    public function showCourse($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Récupérer toutes les participations pour ce cours
        $participations = Participation::with(['user', 'courseAttendances'])
            ->where('course_id', $courseId)
            ->get();

        return view('course-attendance.show-course', compact('course', 'participations'));
    }

    /**
     * Afficher le formulaire pour marquer les présences d'une date spécifique
     */
    public function create($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $course->load(['gym']);
            if (Schema::hasTable('course_coach')) {
                $course->load(['coaches']);
            } else {
                $course->setRelation('coaches', collect());
            }

            $weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            $weekDayIndex = array_flip($weekDays);

            $groupIds = [];
            if (Schema::hasTable('group_user') && Schema::hasTable('participations')) {
                $groupIds = DB::table('group_user')
                    ->join('participations', 'participations.user_id', '=', 'group_user.user_id')
                    ->where('participations.course_id', $course->id)
                    ->distinct()
                    ->pluck('group_user.group_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
            }

            $schedulesByDay = collect();
            if (!empty($groupIds) && Schema::hasTable('schedules')) {
                $schedulesQuery = \App\Models\Schedule::with('group')->whereIn('group_id', $groupIds);
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

            $monthDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $attendanceDate = \Carbon\Carbon::now()->toDateString();

            $with = [
                'user',
                'user.category',
                'user.groups',
                'courseAttendances' => function ($q) {
                    $q->orderByDesc('attendance_date')->limit(5);
                },
            ];
            if (Schema::hasTable('user_payments')) {
                $with['user.payments'] = function ($q) use ($monthDate) {
                    $q->where('billing_month', $monthDate);
                };
            }

            $participations = Participation::with($with)
                ->where('course_id', $courseId)
                ->whereIn('status', ['registered', 'completed'])
                ->whereHas('user')
                ->get();

            $html = view('course-attendance.create', compact('course', 'participations', 'weekDays', 'schedulesByDay'))->render();
            return response($html);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('course-attendance.index')->with('error', __('messages.attendance_error_loading'));
        }
    }

    /**
     * Enregistrer les présences pour une date donnée
     */
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.participation_id' => 'required|exists:participations,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        $attendanceDate = $request->attendance_date;

        DB::transaction(function () use ($request, $attendanceDate) {
            foreach ($request->attendances as $attendanceData) {
                // Vérifier si une présence existe déjà pour cette participation et cette date
                $existingAttendance = CourseAttendance::where('participation_id', $attendanceData['participation_id'])
                    ->where('attendance_date', $attendanceDate)
                    ->first();

                if ($existingAttendance) {
                    // Mettre à jour l'enregistrement existant
                    $existingAttendance->update([
                        'status' => $attendanceData['status'],
                        'notes' => $attendanceData['notes'] ?? null,
                    ]);
                } else {
                    // Créer un nouvel enregistrement
                    CourseAttendance::create([
                        'participation_id' => $attendanceData['participation_id'],
                        'attendance_date' => $attendanceDate,
                        'status' => $attendanceData['status'],
                        'notes' => $attendanceData['notes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('course-attendance.index', $courseId)
            ->with('success', __('messages.attendance_recorded_date', ['date' => $attendanceDate]));
    }

    /**
     * Afficher les présences pour une date spécifique
     */
    public function show($courseId, $date)
    {
        $course = Course::findOrFail($courseId);
        
        $attendances = CourseAttendance::with(['participation.user'])
            ->whereHas('participation', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('attendance_date', $date)
            ->get();

        return view('course-attendance.show', compact('course', 'attendances', 'date'));
    }

    /**
     * Afficher le formulaire d'édition des présences pour une date
     */
    public function edit($courseId, $date)
    {
        $course = Course::findOrFail($courseId);
        
        // Récupérer toutes les participations pour ce cours
        $participations = Participation::with(['user', 'user.category', 'user.groups', 'courseAttendances' => function ($query) use ($date) {
            $query->where('attendance_date', $date);
        }])
        ->where('course_id', $courseId)
        ->whereIn('status', ['registered', 'completed'])
        ->get();

        return view('course-attendance.edit', compact('course', 'participations', 'date'));
    }

    /**
     * Mettre à jour les présences pour une date donnée
     */
    public function update(Request $request, $courseId, $date)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.participation_id' => 'required|exists:participations,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        $course = Course::findOrFail($courseId);

        DB::transaction(function () use ($request, $date) {
            foreach ($request->attendances as $attendanceData) {
                CourseAttendance::updateOrCreate(
                    [
                        'participation_id' => $attendanceData['participation_id'],
                        'attendance_date' => $date,
                    ],
                    [
                        'status' => $attendanceData['status'],
                        'notes' => $attendanceData['notes'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('course-attendance.index', $courseId)
            ->with('success', __('messages.attendance_updated_date', ['date' => $date]));
    }

    /**
     * Supprimer les présences pour une date donnée
     */
    public function destroy($courseId, $date)
    {
        $course = Course::findOrFail($courseId);
        
        CourseAttendance::whereHas('participation', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->where('attendance_date', $date)
        ->delete();

        return redirect()->route('course-attendance.index', $courseId)
            ->with('success', __('messages.attendance_deleted_date', ['date' => $date]));
    }

    /**
     * Obtenir les statistiques de présence pour un cours
     */
    public function statistics($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        $stats = DB::table('course_attendances')
            ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
            ->join('users', 'participations.user_id', '=', 'users.id')
            ->leftJoin('categories', 'users.category_id', '=', 'categories.id')
            ->where('participations.course_id', $courseId)
            ->select(
                'users.name',
                'users.email',
                'users.category_id',
                'categories.name as category_name',
                DB::raw('COUNT(CASE WHEN course_attendances.status = "present" THEN 1 END) as presents'),
                DB::raw('COUNT(CASE WHEN course_attendances.status = "absent" THEN 1 END) as absents'),
                DB::raw('COUNT(CASE WHEN course_attendances.status = "excused" THEN 1 END) as excused'),
                DB::raw('COUNT(*) as total_sessions')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.category_id', 'categories.name')
            ->get();

        return view('course-attendance.statistics', compact('course', 'stats'));
    }

    public function ajaxUpdate(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'participation_id' => 'required|exists:participations,id',
            'status' => 'required|in:present,absent,excused',
        ]);

        CourseAttendance::updateOrCreate(
            [
                'participation_id' => $data['participation_id'],
                'attendance_date' => $data['attendance_date'],
            ],
            [
                'status' => $data['status'],
            ]
        );

        return response()->json(['success' => true, 'message' => __('messages.attendance_updated')]);
    }
}
