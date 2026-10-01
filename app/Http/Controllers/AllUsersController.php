<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Gym;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Course;
use App\Models\Participation;
use App\Models\UserDocument;
use App\Models\Belt;
use Illuminate\Validation\Rule;

class AllUsersController extends Controller
{
    private function beltOptions(): array
    {
        $opts = [];
        $opts['Blanche'] = 'Blanche / حزام أبيض';
        foreach ([
            ['Jaune', 'أصفر', 3],
            ['Verte', 'أخضر', 3],
            ['Bleue', 'أزرق', 3],
            ['Violette', 'بنفسجي', 3],
            ['Rouge', 'أحمر', 3],
        ] as [$fr,$ar,$n]) {
            for ($i=1; $i<=$n; $i++) {
                $value = "$fr $i";
                $label = "$fr $i / حزام $ar $i";
                $opts[$value] = $label;
            }
        }
        foreach (range(1,6) as $i) {
            $value = "Noire $i";
            $label = "Noire $i / حزام أسود درجة $i";
            $opts[$value] = $label;
        }
        return $opts;
    }
    #=======================================================================================#
    #			                           List Function                                	#
    #=======================================================================================#
    public function list(Request $request)
    {
        $user = auth()->user();
        $query = User::role('user')->withoutBanned()->with(['gym', 'city', 'belts', 'groups', 'category']);

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $query->where('gym_id', $user->gym_id);
        } elseif ($user->hasRole('coach')) {
            if ($user->gym_id) {
                $query->where('gym_id', $user->gym_id);
            }
        }

        // Recherche par nom/email
        if ($request->filled('q')) {
            $q = trim($request->get('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%");
            });
        }

        // Filtre par salle (gym)
        if ($request->filled('gym_id')) {
            $query->where('gym_id', $request->get('gym_id'));
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Filtre par ceinture
        if ($request->filled('belt_name')) {
            $beltName = $request->get('belt_name');
            $query->whereHas('belts', function ($q) use ($beltName) {
                $q->where('belt_name', $beltName);
            });
        }

        // Filtre par genre
        if ($request->filled('gender')) {
            $query->where('gender', $request->get('gender'));
        }

        // Filtre par assurance
        if ($request->filled('has_insurance')) {
            $query->where('has_insurance', $request->get('has_insurance') === 'yes' ? 1 : 0);
        }

        // Filtre par stage
        if ($request->filled('has_stage')) {
            $query->where('has_stage', $request->get('has_stage') === 'yes' ? 1 : 0);
        }

        // Filtre par groupe
        if ($request->filled('group_id')) {
            $groupId = $request->get('group_id');
            $query->whereHas('groups', function ($q) use ($groupId) {
                $q->where('groups.id', $groupId);
            });
        }

        // Filtre par date de naissance (intervalle)
        if ($request->filled('birth_date_from')) {
            $query->whereDate('birth_date', '>=', $request->get('birth_date_from'));
        }
        if ($request->filled('birth_date_to')) {
            $query->whereDate('birth_date', '<=', $request->get('birth_date_to'));
        }

        // Filtre par statut de paiement
        if ($request->get('payment_status') === 'due') {
            $query->whereHas('payments', function ($q) {
                $q->where('status', 'due');
            });
        }

        // Filtre par expiration d'adhésion (KPI)
        if ($request->get('filter') === 'expiring_soon') {
            $query->whereHas('payments', function ($q) {
                $q->where('status', 'paid')
                  ->whereBetween('end_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()]);
            });
        }

        // Tri par plus récent
        $query->latest('id');

        $users = $query->paginate(20)->appends($request->query());

        // Listes pour les filtres (Scoped)
        $gymsQuery = Gym::orderBy('name');
        if ($user->hasRole('cityManager')) {
            $gymsQuery->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $gymsQuery->where('id', $user->gym_id);
        } elseif ($user->hasRole('coach')) {
            if ($user->gym_id) {
                $gymsQuery->where('id', $user->gym_id);
            }
        }
        $gyms = $gymsQuery->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        $groups = \App\Models\Group::orderBy('name')->get();
        $beltOptions = $this->beltOptions();
        $filters = $request->all();

        if ($request->ajax()) {
            return view('allUsers.list', compact('users', 'gyms', 'categories', 'groups', 'beltOptions', 'filters'))->fragment('table');
        }

        return view('allUsers.list', compact('users', 'gyms', 'categories', 'groups', 'beltOptions', 'filters'));
    }

    public function stages(Request $request)
    {
        $user = auth()->user();
        $query = User::role('user')->withoutBanned()->with(['gym', 'category']);

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $query->where('gym_id', $user->gym_id);
        } elseif ($user->hasRole('coach')) {
            if ($user->gym_id) {
                $query->where('gym_id', $user->gym_id);
            }
        }

        // Filtres
        if ($request->filled('q')) {
            $q = trim($request->get('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%");
            });
        }
        if ($request->filled('gym_id')) {
            $query->where('gym_id', $request->get('gym_id'));
        }
        if ($request->filled('has_stage')) {
            $query->where('has_stage', $request->get('has_stage'));
        }

        $users = $query->latest('id')->paginate(50)->appends($request->query());
        
        // Listes pour les filtres (Scoped)
        $gymsQuery = Gym::orderBy('name');
        if ($user->hasRole('cityManager')) {
            $gymsQuery->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $gymsQuery->where('id', $user->gym_id);
        } elseif ($user->hasRole('coach')) {
            if ($user->gym_id) {
                $gymsQuery->where('id', $user->gym_id);
            }
        }
        $gyms = $gymsQuery->get();

        return view('allUsers.stages', compact('users', 'gyms'));
    }

    public function updateStage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'has_stage' => 'required|boolean',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->has_stage = $request->has_stage;
        $user->save();

        return response()->json(['success' => true, 'message' => __('messages.user_updated')]);
    }

    #=======================================================================================#
    #			                           Show Function                                	#
    #=======================================================================================#
    public function show($id)
    {
        $singleUser = User::with(['gym','city','category','belts','payments','documents','groups'])->findOrFail($id);
        $groups = Group::orderBy('name')->get(['id', 'name']);
        $currentGroupId = $singleUser->groups->first()?->id;

        $createdAt = $singleUser->created_at ? $singleUser->created_at->copy()->startOfMonth() : now()->startOfMonth();
        $endMonth = now()->endOfMonth();
        $paymentsByMonth = $singleUser->payments->keyBy(function($p){
            return \Illuminate\Support\Carbon::parse($p->billing_month)->format('Y-m');
        });
        $unpaidMonths = [];
        $paidMonths = [];
        $cursor = $createdAt->copy();
        while ($cursor->lessThanOrEqualTo($endMonth)) {
            $key = $cursor->format('Y-m');
            $p = $paymentsByMonth->get($key);
            if ($p && $p->status === 'paid') {
                $paidMonths[] = $key;
            } else {
                $unpaidMonths[] = $key;
            }
            $cursor->addMonth();
        }

        $windowStart = \Illuminate\Support\Carbon::now()->subMonths(11)->startOfMonth();
        if ($windowStart->lessThan($createdAt)) { $windowStart = $createdAt->copy(); }
        $windowCursor = $windowStart->copy();
        $last12Months = [];
        while ($windowCursor->lessThanOrEqualTo($endMonth)) {
            $key = $windowCursor->format('Y-m');
            $p = $paymentsByMonth->get($key);
            $status = $p ? $p->status : 'unpaid';
            $last12Months[] = ['month' => $key, 'status' => $status];
            $windowCursor->addMonth();
        }

        $leastPaid = $singleUser->payments()->orderBy('amount_cents', 'asc')->orderBy('billing_month','desc')->limit(5)->get();
        $totals = [
            'paid_cents' => (int) $singleUser->payments()->where('status','paid')->sum('amount_cents'),
            'due_cents' => (int) $singleUser->payments()->where('status','due')->sum('amount_cents'),
        ];

        // Fetch Event Registrations
        $eventRegistrations = \App\Models\EventRegistration::where('user_id', $id)
            ->with('event')
            ->orderByDesc('registered_at')
            ->get();

        return view("allUsers.show", [
            'singleUser' => $singleUser,
            'unpaidMonths' => $unpaidMonths,
            'paidMonths' => $paidMonths,
            'leastPaid' => $leastPaid,
            'totals' => $totals,
            'last12Months' => $last12Months,
            'groups' => $groups,
            'currentGroupId' => $currentGroupId,
            'eventRegistrations' => $eventRegistrations,
        ]);
    }

    public function assignGroup(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'group_id' => ['nullable', 'integer', 'exists:groups,id'],
        ]);

        $groupId = $validated['group_id'] ?? null;
        if ($groupId) {
            $user->groups()->sync([(int) $groupId]);
        } else {
            $user->groups()->sync([]);
        }

        return redirect()->route('allUsers.show', $user->id)->with('success', __('messages.group_updated'));
    }

    public function report(Request $request, $id)
    {
        $month = $request->string('month')->toString();
        $user = User::with(['gym','city','belts','payments','eventRegistrations.event'])->findOrFail($id);

        if ($month && str_contains($month, '-')) {
            try {
                [$year, $mon] = explode('-', $month);
                $start = \Illuminate\Support\Carbon::createFromDate((int)$year, (int)$mon, 1)->startOfDay();
                $end = \Illuminate\Support\Carbon::createFromDate((int)$year, (int)$mon, 1)->endOfMonth()->endOfDay();
            } catch (\Exception $e) {
                $start = \Illuminate\Support\Carbon::now()->startOfMonth();
                $end = \Illuminate\Support\Carbon::now()->endOfMonth();
                $month = $start->format('Y-m');
            }
        } else {
            $start = \Illuminate\Support\Carbon::now()->startOfMonth();
            $end = \Illuminate\Support\Carbon::now()->endOfMonth();
            $month = $start->format('Y-m');
        }

        $payments = $user->payments()->whereBetween('billing_month', [$start->toDateString(), $end->toDateString()])->orderByDesc('billing_month')->get();
        $summary = [
            'total_cents' => (int) $payments->sum('amount_cents'),
            'paid_cents' => (int) $payments->where('status','paid')->sum('amount_cents'),
            'paid_count' => (int) $payments->where('status','paid')->count(),
            'due_count' => (int) $payments->where('status','due')->count(),
        ];

        $belts = $user->belts()->whereBetween('achieved_at', [$start->toDateString(), $end->toDateString()])->get();
        $beltsTotal = (int) $belts->sum('price_cents');

        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->whereBetween('attendance_at', [$start, $end])
            ->selectRaw("DATE(attendance_at) as day, SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as presents, SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absents")
            ->groupBy('day')
            ->orderBy('day','desc')
            ->get();

        $courseAttendanceStats = \App\Models\CourseAttendance::query()
            ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
            ->join('courses', 'participations.course_id', '=', 'courses.id')
            ->where('participations.user_id', $user->id)
            ->whereBetween('course_attendances.attendance_date', [$start->toDateString(), $end->toDateString()])
            ->select([
                'courses.id as course_id',
                'courses.name as course_name',
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_sessions'),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN course_attendances.status = 'present' THEN 1 ELSE 0 END) as presents"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN course_attendances.status = 'absent' THEN 1 ELSE 0 END) as absents"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN course_attendances.status = 'excused' THEN 1 ELSE 0 END) as excused"),
            ])
            ->groupBy('courses.id', 'courses.name')
            ->get();

        $enrolledParticipations = \App\Models\Participation::query()
            ->with(['course:id,name'])
            ->where('user_id', $user->id)
            ->whereHas('course')
            ->get();

        $enrolledCourses = $enrolledParticipations
            ->map(fn ($p) => $p->course)
            ->filter()
            ->unique('id')
            ->values();

        $courseSessions = \App\Models\CourseAttendance::query()
            ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
            ->where('participations.user_id', $user->id)
            ->whereBetween('course_attendances.attendance_date', [$start->toDateString(), $end->toDateString()])
            ->select([
                'course_attendances.attendance_date as day',
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN course_attendances.status = 'present' THEN 1 ELSE 0 END) as presents"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN course_attendances.status = 'absent' THEN 1 ELSE 0 END) as absents"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN course_attendances.status = 'excused' THEN 1 ELSE 0 END) as excused"),
            ])
            ->groupBy('course_attendances.attendance_date')
            ->orderByDesc('course_attendances.attendance_date')
            ->get();

        $courseAttendanceStatsByCourseId = $courseAttendanceStats->keyBy('course_id');
        $enrolledCoursesWithPresence = $enrolledParticipations
            ->map(function ($p) use ($courseAttendanceStatsByCourseId) {
                $courseId = (int) ($p->course_id ?? 0);
                $courseName = (string) (optional($p->course)->name ?? '');
                $row = $courseAttendanceStatsByCourseId->get($courseId);
                return [
                    'course_id' => $courseId,
                    'course_name' => $courseName,
                    'enroll_status' => (string) ($p->status ?? ''),
                    'total_sessions' => (int) ($row->total_sessions ?? 0),
                    'presents' => (int) ($row->presents ?? 0),
                    'absents' => (int) ($row->absents ?? 0),
                    'excused' => (int) ($row->excused ?? 0),
                ];
            })
            ->filter(fn ($c) => !empty($c['course_id']) && !empty($c['course_name']))
            ->unique('course_id')
            ->values();

        $courseAttendanceStatsFilled = $enrolledCoursesWithPresence->map(function ($row) {
            return [
                'course_id' => (int) ($row['course_id'] ?? 0),
                'course_name' => (string) ($row['course_name'] ?? ''),
                'total_sessions' => (int) ($row['total_sessions'] ?? 0),
                'presents' => (int) ($row['presents'] ?? 0),
                'absents' => (int) ($row['absents'] ?? 0),
                'excused' => (int) ($row['excused'] ?? 0),
            ];
        });

        $courseAttendanceDetailsByCourse = \App\Models\CourseAttendance::query()
            ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
            ->join('courses', 'participations.course_id', '=', 'courses.id')
            ->where('participations.user_id', $user->id)
            ->whereBetween('course_attendances.attendance_date', [$start->toDateString(), $end->toDateString()])
            ->whereNull('course_attendances.deleted_at')
            ->orderByDesc('course_attendances.attendance_date')
            ->select([
                'courses.id as course_id',
                'courses.name as course_name',
                'course_attendances.attendance_date',
                'course_attendances.status',
                'course_attendances.notes',
            ])
            ->get()
            ->groupBy('course_id');

        $createdAt = $user->created_at ? $user->created_at->copy()->startOfMonth() : \Illuminate\Support\Carbon::now()->startOfMonth();
        $endMonth = \Illuminate\Support\Carbon::now()->endOfMonth();
        $paidMap = $user->payments->reduce(function($carry,$p){
            if ($p->status === 'paid') {
                $key = \Illuminate\Support\Carbon::parse($p->billing_month)->format('Y-m');
                $carry[$key] = true;
            }
            return $carry;
        }, []);
        $userPaidMonths = [];
        $userUnpaidMonths = [];
        $cursor = $createdAt->copy();
        while ($cursor->lessThanOrEqualTo($endMonth)) {
            $key = $cursor->format('Y-m');
            if (isset($paidMap[$key])) {
                $userPaidMonths[] = $key;
            } else {
                $userUnpaidMonths[] = $key;
            }
            $cursor->addMonth();
        }

        // ========================================
        // ENHANCED UX DATA
        // ========================================
        
        // Calculate unpaid total amount
        $unpaidTotalCents = $user->payments()
            ->where('status', 'due')
            ->sum('amount_cents');
        
        // Club averages for contextual comparison
        $clubAverages = \Illuminate\Support\Facades\Cache::remember('club_averages_' . $month, 3600, function() use ($start, $end) {
            $allUserPayments = \App\Models\UserPayment::whereBetween('billing_month', [$start->toDateString(), $end->toDateString()])->get();
            $allAttendances = \App\Models\CourseAttendance::whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])->get();
            
            $totalUsers = User::role('user')->count();
            $totalPresent = $allAttendances->where('status', 'present')->count();
            $totalSessions = $allAttendances->count();
            
            return [
                'avg_payment_cents' => $totalUsers > 0 ? (int) ($allUserPayments->sum('amount_cents') / $totalUsers) : 0,
                'avg_presence_rate' => $totalSessions > 0 ? round(($totalPresent / $totalSessions) * 100, 1) : 0,
                'avg_sessions_per_user' => $totalUsers > 0 ? round($totalSessions / $totalUsers, 1) : 0,
            ];
        });
        
        // Calculate user's presence rate for comparison
        $userPresenceRate = 0;
        $userTotalSessions = $courseAttendanceStats->sum('total_sessions');
        $userTotalPresent = $courseAttendanceStats->sum('presents');
        if ($userTotalSessions > 0) {
            $userPresenceRate = round(($userTotalPresent / $userTotalSessions) * 100, 1);
        }
        
        // Payment history for chart (last 6 months)
        $paymentHistory = [];
        $paymentCursor = \Illuminate\Support\Carbon::now()->subMonths(5)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $monthKey = $paymentCursor->format('Y-m');
            $monthStart = $paymentCursor->copy()->startOfMonth();
            $monthEnd = $paymentCursor->copy()->endOfMonth();
            
            $monthPayments = $user->payments()
                ->whereBetween('billing_month', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->get();
            
            $paymentHistory[] = [
                'month' => $monthKey,
                'label' => $paymentCursor->format('M Y'),
                'total_cents' => (int) $monthPayments->sum('amount_cents'),
                'paid_cents' => (int) $monthPayments->where('status', 'paid')->sum('amount_cents'),
                'due_cents' => (int) $monthPayments->where('status', 'due')->sum('amount_cents'),
            ];
            
            $paymentCursor->addMonth();
        }
        
        // Attendance history for chart (last 6 months)
        $attendanceHistory = [];
        $attendanceCursor = \Illuminate\Support\Carbon::now()->subMonths(5)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $monthKey = $attendanceCursor->format('Y-m');
            $monthStart = $attendanceCursor->copy()->startOfMonth();
            $monthEnd = $attendanceCursor->copy()->endOfMonth();
            
            $monthAttendances = \App\Models\CourseAttendance::query()
                ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
                ->where('participations.user_id', $user->id)
                ->whereBetween('course_attendances.attendance_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->selectRaw("COUNT(*) as total, SUM(CASE WHEN course_attendances.status = 'present' THEN 1 ELSE 0 END) as presents")
                ->first();
            
            $total = $monthAttendances->total ?? 0;
            $presents = $monthAttendances->presents ?? 0;
            
            $attendanceHistory[] = [
                'month' => $monthKey,
                'label' => $attendanceCursor->format('M Y'),
                'total' => (int) $total,
                'presents' => (int) $presents,
                'rate' => $total > 0 ? round(($presents / $total) * 100, 1) : 0,
            ];
            
            $attendanceCursor->addMonth();
        }
        
        // Belt progression timeline (all belts for user)
        $beltTimeline = $user->belts()->orderBy('achieved_at', 'asc')->get()->map(function($belt) {
            return [
                'name' => $belt->belt_name,
                'date' => $belt->achieved_at ? $belt->achieved_at->format('Y-m-d') : null,
                'price_cents' => $belt->price_cents,
            ];
        });

        $eventRegistrations = $user->eventRegistrations()
            ->with('event')
            ->whereBetween('registered_at', [$start, $end])
            ->get();

        $eventSummary = [
            'total_cents' => (int) $eventRegistrations->sum(function($reg) {
                return optional($reg->event)->registration_fee ?? 0;
            }),
            'paid_cents' => (int) $eventRegistrations->sum('paid_amount'),
        ];

        $viewData = compact(
            'user','month','summary','payments','belts','beltsTotal','attendances',
            'userPaidMonths','userUnpaidMonths','courseAttendanceStats','enrolledCourses',
            'unpaidTotalCents','clubAverages','userPresenceRate','paymentHistory',
            'attendanceHistory','beltTimeline', 'eventRegistrations', 'eventSummary',
            'courseSessions', 'courseAttendanceStatsFilled',
            'enrolledCoursesWithPresence', 'courseAttendanceDetailsByCourse'
        );

        if ($request->query('export') === 'pdf' && class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $viewData['isPdfExport'] = true;

            // Ar-PHP for processing arabic names correctly, if available.
            $html = view('allUsers.report_pdf', $viewData)->render();
            if (class_exists(\ArPHP\I18N\Arabic::class)) {
                try {
                    $arabic = new \ArPHP\I18N\Arabic();
                    $p = $arabic->arIdentify($html);
                    if (!empty($p) && count($p) >= 2) {
                        for ($i = count($p) - 1; $i >= 1; $i -= 2) {
                            $start  = $p[$i - 1];
                            $length = $p[$i] - $p[$i - 1];
                            $utf8ar = $arabic->utf8Glyphs(substr($html, $start, $length));
                            $html   = substr_replace($html, $utf8ar, $start, $length);
                        }
                    }
                } catch (\Exception $e) { /* ignore */ }
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('a4', 'portrait');
            $filename = \Illuminate\Support\Str::slug($user->name, '_') . '_rapport_' . $month . '.pdf';
            return $pdf->download($filename);
        }

        return view('allUsers.report', $viewData);
    }
    #=======================================================================================#
    #			                           Delete Function                                	#
    #=======================================================================================#
    public function deleteUser($id)
    {
        $singleUser = User::findorfail($id);
        $singleUser->delete();
        return response()->json(['success' => __('messages.user_deleted')]);
    }


    #=======================================================================================#
    #			                        Assign Gym To User                              	#
    #=======================================================================================#
    public function addGym($id)
    {
        $singleUser = User::findorfail($id);
        return view('allUsers.addGym', [
            'user' => User::find($id),
            'gyms' => Gym::all(),
        ]);
    }

    public function submitGym(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
        ]);
        $user->update(['gym_id' => $request->gym_id]);
        return redirect()->route('allUsers.list')->with('success', __('messages.user_updated'));
    }

    #=======================================================================================#
    #                                   Create Function                                    #
    #=======================================================================================#
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get(['id','name']);
        $beltOptions = $this->beltOptions();
        $groups = \App\Models\Group::orderBy('name')->get(['id','name']);
        return view('allUsers.create', compact('categories','beltOptions','groups'));
    }

    #=======================================================================================#
    #                                    Store Function                                    #
    #=======================================================================================#
    public function store(Request $request)
    {
        $options = array_keys($this->beltOptions());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/',
            'email' => 'nullable|email|unique:users,email',
            'national_id' => 'nullable|numeric|unique:users,national_id',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'birth_date' => 'nullable|date',
            'weight_kg' => 'nullable|numeric|min:10|max:300',
            'category_id' => 'nullable|exists:categories,id',
            'num_dep' => 'nullable|string|max:255',
            'has_insurance' => 'nullable|boolean',
            'has_stage' => 'nullable|boolean',
            'belt_name' => ['nullable','string', Rule::in($options)],
            'belt_achieved_at' => 'nullable|date|before_or_equal:today',
            'group_id' => 'required|exists:groups,id',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make(Str::random(12));
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->national_id = $request->national_id;
        $user->gender = $request->gender;
        $user->birth_date = $request->birth_date;
        $user->weight_kg = $request->weight_kg;
        $user->category_id = $request->category_id;
        $user->num_dep = $request->num_dep;
        $user->has_insurance = $request->has('has_insurance') ? 1 : 0;
        $user->has_stage = $request->has('has_stage') ? 1 : 0;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $user->profile_image = 'imgs/' . $name;
        }

        $user->save();
        $user->assignRole('user');

        

        if ($request->filled('belt_name')) {
            Belt::create([
                'user_id' => $user->id,
                'belt_name' => $request->belt_name,
                'achieved_at' => $request->belt_achieved_at ?: now()->toDateString(),
                'price_cents' => null,
            ]);
        }

        if ($request->filled('group_id')) {
            $user->groups()->sync([(int) $request->group_id]);
        }

        return redirect()->route('allUsers.list')->with('success', __('messages.user_created'));
    }

    #=======================================================================================#
    #                                     Edit Function                                     #
    #=======================================================================================#
    public function edit($id)
    {
        $singleUser = User::findOrFail($id);
        $courses = Course::orderBy('name')->get(['id','name']);
        $categories = \App\Models\Category::orderBy('name')->get(['id','name']);

        $currentCourseId = $singleUser->courses()->first()?->id;

        return view('allUsers.edit', [
            'singleUser' => $singleUser,
            'courses' => $courses,
            'categories' => $categories,
            'currentCourseId' => $currentCourseId,
        ]);
    }

    #=======================================================================================#
    #                                    Update Function                                    #
    #=======================================================================================#
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'national_id' => 'nullable|numeric|unique:users,national_id,' . $user->id,
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'birth_date' => 'nullable|date',
            'weight_kg' => 'nullable|numeric|min:10|max:300',
            'category_id' => 'nullable|exists:categories,id',
            'num_dep' => 'nullable|string|max:255',
            'has_insurance' => 'nullable|boolean',
            'has_stage' => 'nullable|boolean',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->national_id = $request->national_id;
        $user->gender = $request->gender;
        $user->birth_date = $request->birth_date;
        $user->weight_kg = $request->weight_kg;
        $user->category_id = $request->category_id;
        $user->num_dep = $request->num_dep;
        $user->has_insurance = $request->has('has_insurance') ? 1 : 0;
        $user->has_stage = $request->has('has_stage') ? 1 : 0;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $user->profile_image = 'imgs/' . $name;
        }

        $user->save();

        if ($request->filled('course_id')) {
            $course = Course::withCount('participations')->findOrFail($request->course_id);
            
            // Check if user is already registered for this course
            $isAlreadyRegistered = Participation::where('user_id', $user->id)
                ->where('course_id', $request->course_id)
                ->exists();

            if (!$isAlreadyRegistered && $course->capacity && $course->participations_count >= $course->capacity) {
                return redirect()->back()->withErrors([
                    'course_id' => "La capacité maximale du cours ({$course->capacity}) a été atteinte."
                ])->withInput();
            }

            // Si on souhaite un seul cours associé, supprimer les autres et garder celui sélectionné
            Participation::where('user_id', $user->id)->delete();
            Participation::create([
                'user_id' => $user->id,
                'course_id' => $request->course_id,
                'status' => 'registered',
                'participated_at' => now(),
            ]);
        }
        return redirect()->route('allUsers.list')->with('success', __('messages.user_updated'));
    }
    public function storeDocuments(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $types = ['photo','cin','certificat_medical','diplome_ceinture','certificat_stage','extra1','extra2','extra3'];
        if ($request->has('documents')) {
            foreach ($types as $t) {
                $file = $request->file("documents.$t");
                if ($file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (!in_array($ext, ['pdf','png','jpg','jpeg'])) {
                        return back()->withErrors(['documents' => __('messages.unsupported_file_type')])->withInput();
                    }
                    $name = time() . '_' . \Illuminate\Support\Str::random(12) . '.' . $ext;
                    
                    // Secure storage in storage/app/private/users/{id}
                    $path = $file->storeAs("private/users/{$user->id}", $name);
                    
                    UserDocument::create([
                        'user_id' => $user->id,
                        'type' => $t,
                        'file_path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'notes' => $request->input("notes.$t"),
                    ]);
                }
            }
        }
        return redirect()->route('allUsers.show', $user->id)->with('success', __('messages.document_uploaded'));
    }

    public function deleteDocument($userId, $docId)
    {
        $user = User::findOrFail($userId);
        $doc = UserDocument::where('id', $docId)->where('user_id', $user->id)->firstOrFail();
        
        // Handle legacy public files
        if (str_starts_with($doc->file_path, 'docs/users/')) {
            $path = public_path('/' . $doc->file_path);
            if (is_file($path)) { @unlink($path); }
        } else {
            // Handle new private files
            \Illuminate\Support\Facades\Storage::delete($doc->file_path);
        }
        
        $doc->delete();
        return redirect()->route('allUsers.show', $user->id)->with('success', __('messages.document_deleted'));
    }

    public function downloadDocument($userId, $docId)
    {
        $user = User::findOrFail($userId);
        $doc = UserDocument::where('id', $docId)->where('user_id', $user->id)->firstOrFail();

        // Handle legacy public files
        if (str_starts_with($doc->file_path, 'docs/users/')) {
            $path = public_path($doc->file_path);
            if (!file_exists($path)) { abort(404); }
            return response()->file($path);
        }

        // Handle new private files
        if (!\Illuminate\Support\Facades\Storage::exists($doc->file_path)) {
            abort(404);
        }
        return response()->file(\Illuminate\Support\Facades\Storage::path($doc->file_path));
    }
}
