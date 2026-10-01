<?php

namespace App\Http\Controllers;
use App\Models\Gym;
use App\Models\User;
use App\Models\Attendance;
use App\Models\TrainingSession;
use App\Models\Course;
use App\Models\Participation;
use App\Models\CourseAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{

    public function listHistory(Request $request)
    {
        $user = auth()->user();
        $query = Attendance::select(DB::raw('attendances.id, training_sessions.name as training_session_name, gyms.name as gym_name, cities.name as gym_city, Date(attendances.attendance_at) as attendance_date, Time(attendances.attendance_at) as attendance_time, users.name as name, users.email as email, attendances.status, users.profile_image'))
        ->join('users', 'users.id', '=', 'attendances.user_id')
        ->join('training_sessions', 'training_sessions.id', '=', 'attendances.training_session_id')
        ->join('gyms', "gyms.id", '=', 'users.gym_id')
        ->join('cities', "gyms.city_id", '=', 'cities.id');

        // Filtering
        if ($request->filled('date')) {
            $query->whereDate('attendances.attendance_at', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('attendances.status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('training_sessions.name', 'like', "%{$search}%");
            });
        }

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->where('cities.id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $query->where('gyms.id', $user->gym_id);
        } elseif ($user->hasRole('coach')) {
            if ($user->gym_id) {
                $query->where('gyms.id', $user->gym_id);
            }
        }

        $history_attendances = $query->orderBy('attendances.attendance_at', 'desc')->paginate(15);

        // Stats for dashboard
        $stats = [
            'total' => (clone $query)->count(),
            'present' => (clone $query)->where('attendances.status', 'present')->count(),
            'absent' => (clone $query)->where('attendances.status', 'absent')->count(),
            'excused' => (clone $query)->where('attendances.status', 'excused')->count(),
        ];

        return view('attendance', [
            'attendances' => $history_attendances,
            'stats' => $stats,
        ]);
    }

    public function storeForCoach(Request $request, $coachId)
    {
        $coach = User::findOrFail($coachId);
        if ($coach->hasRole('coach') && Auth::user()->hasRole('coach') && Auth::id() !== $coach->id) {
            abort(403);
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'attendance_at' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,excused'],
        ]);

        $courseAssigned = DB::table('course_coach')
            ->where('coach_id', $coach->id)
            ->where('course_id', $validated['course_id'])
            ->exists();
        if (!$courseAssigned) {
            return back()->withErrors(__('messages.coach_not_assigned'))->withInput();
        }

        $participation = Participation::firstOrCreate(
            [
                'user_id' => $coach->id,
                'course_id' => $validated['course_id'],
            ],
            [
                'status' => 'registered',
                'participated_at' => now(),
            ]
        );

        $attendanceDate = Carbon::parse($validated['attendance_at'])->toDateString();
        CourseAttendance::updateOrCreate(
            [
                'participation_id' => $participation->id,
                'attendance_date' => $attendanceDate,
            ],
            [
                'status' => $validated['status'],
            ]
        );

        return redirect()->route('coach.show', $coach->id)->with('success', __('messages.coach_attendance_recorded'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $sessionsQuery = TrainingSession::orderBy('day')->orderBy('starts_at');
        $usersQuery = User::role('user')->withoutBanned()->orderBy('name');

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $sessionsQuery->whereExists(function ($q) use ($user) {
                $q->select(DB::raw(1))
                  ->from('training_packages')
                  ->join('gyms_training_packages', 'gyms_training_packages.training_package_id', '=', 'training_packages.id')
                  ->join('gyms', 'gyms.id', '=', 'gyms_training_packages.gym_id')
                  ->whereColumn('training_packages.id', 'training_sessions.training_package_id')
                  ->where('gyms.city_id', $user->city_id);
            });
            $usersQuery->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager') || $user->hasRole('coach')) {
            if ($user->gym_id) {
                $sessionsQuery->whereExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))
                      ->from('training_packages')
                      ->join('gyms_training_packages', 'gyms_training_packages.training_package_id', '=', 'training_packages.id')
                      ->whereColumn('training_packages.id', 'training_sessions.training_package_id')
                      ->where('gyms_training_packages.gym_id', $user->gym_id);
                });
                $usersQuery->where('gym_id', $user->gym_id);
            }
        }

        $sessions = $sessionsQuery->get(['id','name','day','starts_at','finishes_at']);
        
        $monthDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $usersQuery->with(['attendances' => function($q) {
            $q->latest('attendance_at')->limit(5);
        }]);

        if (\Illuminate\Support\Facades\Schema::hasTable('user_payments')) {
            $usersQuery->with(['payments' => function ($q) use ($monthDate) {
                $q->where('billing_month', $monthDate);
            }]);
        }

        $users = $usersQuery->get(['id','name','email','gym_id','city_id','category_id']);
        
        $defaultDate = now()->format('Y-m-d');
        return view('attendance.create', compact('sessions','users','defaultDate'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_at' => ['required','date'],
            'training_session_id' => ['required','exists:training_sessions,id'],
            'attendances' => ['required','array'],
            'attendances.*.user_id' => ['required','exists:users,id'],
            'attendances.*.status' => ['required','in:present,absent,excused'],
        ]);

        $attendanceDate = \Carbon\Carbon::parse($data['attendance_at']);
        \DB::transaction(function() use ($data, $attendanceDate) {
            foreach ($data['attendances'] as $row) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => $row['user_id'],
                        'training_session_id' => $data['training_session_id'],
                        'attendance_at' => $attendanceDate,
                    ],
                    [
                        'status' => $row['status'],
                    ]
                );
            }
        });

        return redirect()->route('attendance.create')->with('success', __('messages.attendances_recorded'));
    }

    public function ajaxUpdate(Request $request)
    {
        $data = $request->validate([
            'attendance_at' => 'required|date',
            'training_session_id' => 'required|exists:training_sessions,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent,excused',
        ]);

        $attendanceDate = \Carbon\Carbon::parse($data['attendance_at']);

        Attendance::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'training_session_id' => $data['training_session_id'],
                'attendance_at' => $attendanceDate,
            ],
            [
                'status' => $data['status'],
            ]
        );

        return response()->json(['success' => true, 'message' => __('messages.attendance_updated')]);
    }
}
