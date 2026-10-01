<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Gym;
use App\Models\Revenue;
use App\Models\TrainingSession;
use App\Models\User;
use App\Models\UserPayment;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Attendance;
use App\Models\CourseAttendance;
use App\Models\Course;
use App\Models\Event as GymEvent;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WelcomeController extends Controller
{
    private $userID, $userData, $userRole, $cities = 0, $citiesManagers = 0, $gyms = 0, $gymsManagers = 0,
        $coaches = 0, $users = 0, $cashRegisters = 0, $cashRegistersBalance = 0, $cashRegistersList = [], $totalRevenue = 0, $totalRevenueFormatted = 0;

    public function __construct()
    {
        $this->middleware('auth');
    }

    #=======================================================================================#
    #			                               index                                       	#
    #=======================================================================================#
    public function index(Request $request)
    {
        $this->userID = Auth::id();
        $this->userData = User::find($this->userID);
        $this->userRole = Auth::user()->getRoleNames();

        // Basic counts
        $this->cities = City::count();
        $this->citiesManagers = User::role('cityManager')->count();
        $this->gyms = Gym::count();
        $this->gymsManagers = User::role('gymManager')->count();
        $this->coaches = User::role('coach')->count();
        $this->users = User::role('user')->count();

        // Cash Registers count and balance based on role
        if ($this->userRole[0] === 'admin') {
            $this->cashRegisters = CashRegister::count();
            $paymentsSum = UserPayment::where('status', 'paid')->sum('amount_cents');
            $revenuesSum = Revenue::sum('price');
            $expensesSum = Expense::sum('amount_cents');
            $this->cashRegistersBalance = ($paymentsSum + (int)$revenuesSum - (int)$expensesSum) / 100;
            
            $cashRegistersList = CashRegister::withSum(['userPayments as payments_sum' => function($query) {
                $query->where('status', 'paid');
            }], 'amount_cents')->withSum('revenues as revenues_sum', 'price')->get();

            $gymIds = $cashRegistersList->pluck('gym_id')->filter()->unique()->values();
            $expensesByGym = $gymIds->isEmpty()
                ? collect()
                : Expense::query()
                    ->whereIn('gym_id', $gymIds)
                    ->selectRaw('gym_id, SUM(amount_cents) as total_cents')
                    ->groupBy('gym_id')
                    ->pluck('total_cents', 'gym_id');

            $this->cashRegistersList = $cashRegistersList->map(function($reg) use ($expensesByGym) {
                $expenseCents = (int) ($expensesByGym[$reg->gym_id] ?? 0);
                $reg->balance = ((int)$reg->payments_sum + (int)$reg->revenues_sum - $expenseCents) / 100;
                return $reg;
            });
        } elseif ($this->userRole[0] === 'cityManager') {
            $gymIds = Gym::where('city_id', $this->userData->city_id)->pluck('id');
            $this->cashRegisters = CashRegister::whereIn('gym_id', $gymIds)->count();
            
            $paymentsSum = UserPayment::where('status', 'paid')
                ->whereIn('cash_register_id', function($query) use ($gymIds) {
                    $query->select('id')->from('cash_registers')->whereIn('gym_id', $gymIds);
                })->sum('amount_cents');
            $revenuesSum = Revenue::whereIn('cash_register_id', function($query) use ($gymIds) {
                    $query->select('id')->from('cash_registers')->whereIn('gym_id', $gymIds);
                })->sum('price');
            $expensesSum = Expense::whereIn('gym_id', $gymIds)->sum('amount_cents');
            $this->cashRegistersBalance = ($paymentsSum + (int)$revenuesSum - (int)$expensesSum) / 100;

            $cashRegistersList = CashRegister::whereIn('gym_id', $gymIds)
                ->withSum(['userPayments as payments_sum' => function($query) {
                    $query->where('status', 'paid');
                }], 'amount_cents')->withSum('revenues as revenues_sum', 'price')->get();

            $expensesByGym = $gymIds->isEmpty()
                ? collect()
                : Expense::query()
                    ->whereIn('gym_id', $gymIds)
                    ->selectRaw('gym_id, SUM(amount_cents) as total_cents')
                    ->groupBy('gym_id')
                    ->pluck('total_cents', 'gym_id');

            $this->cashRegistersList = $cashRegistersList->map(function($reg) use ($expensesByGym) {
                    $expenseCents = (int) ($expensesByGym[$reg->gym_id] ?? 0);
                    $reg->balance = ((int)$reg->payments_sum + (int)$reg->revenues_sum - $expenseCents) / 100;
                    return $reg;
                });
        } elseif ($this->userRole[0] === 'gymManager') {
            $gymId = $this->userData->gym_id;
            $this->cashRegisters = CashRegister::where('gym_id', $gymId)->count();
            
            $paymentsSum = UserPayment::where('status', 'paid')
                ->whereIn('cash_register_id', function($query) use ($gymId) {
                    $query->select('id')->from('cash_registers')->where('gym_id', $gymId);
                })->sum('amount_cents');
            $revenuesSum = Revenue::whereIn('cash_register_id', function($query) use ($gymId) {
                    $query->select('id')->from('cash_registers')->where('gym_id', $gymId);
                })->sum('price');
            $expensesSum = Expense::where('gym_id', $gymId)->sum('amount_cents');
            $this->cashRegistersBalance = ($paymentsSum + (int)$revenuesSum - (int)$expensesSum) / 100;

            $cashRegistersList = CashRegister::where('gym_id', $gymId)
                ->withSum(['userPayments as payments_sum' => function($query) {
                    $query->where('status', 'paid');
                }], 'amount_cents')->withSum('revenues as revenues_sum', 'price')->get();

            $expensesByGym = Expense::query()
                ->where('gym_id', $gymId)
                ->selectRaw('gym_id, SUM(amount_cents) as total_cents')
                ->groupBy('gym_id')
                ->pluck('total_cents', 'gym_id');

            $this->cashRegistersList = $cashRegistersList->map(function($reg) use ($expensesByGym) {
                    $expenseCents = (int) ($expensesByGym[$reg->gym_id] ?? 0);
                    $reg->balance = ((int)$reg->payments_sum + (int)$reg->revenues_sum - $expenseCents) / 100;
                    return $reg;
                });
        } else {
            $this->cashRegisters = 0;
            $this->cashRegistersBalance = 0;
            $this->cashRegistersList = collect();
        }

        $canViewRevenue = false;
        $totalRevenue = 0;
        $paymentsMonth = ['total_cents' => 0, 'paid_cents' => 0, 'paid_count' => 0, 'due_count' => 0];
        $dailyAttendance = (object)['presents' => 0, 'total' => 0];
        $coursesCount = 0;
        $fullCoursesCount = 0;
        $eventsCount = 0;
        $expiringSoonCount = 0;
        $recentActivities = collect();

        // Fetch Sessions for Calendar
        $calendarSessions = [];
        $sessionsQuery = TrainingSession::query();
        
        if ($this->userRole[0] === 'gymManager') {
            $sessionsQuery->whereHas('trainingPackage', function($q) {
                $q->where('gym_id', $this->userData->gym_id);
            });
        } elseif ($this->userRole[0] === 'cityManager') {
            $sessionsQuery->whereHas('trainingPackage.gym', function($q) {
                $q->where('city_id', $this->userData->city_id);
            });
        }
        
        // --- Recent Activities Logic (Unified) ---
        $actUserQuery = User::role('user');
        $actPaymentQuery = UserPayment::with('user')->where('status', 'paid');
        $actAttendanceQuery = Attendance::with(['user', 'trainingSession'])->where('status', 'present');

        if ($this->userRole[0] === 'gymManager') {
            $gymId = $this->userData->gym_id;
            $actUserQuery->where('gym_id', $gymId);
            $actPaymentQuery->whereHas('user', fn($q) => $q->where('gym_id', $gymId));
            $actAttendanceQuery->whereHas('user', fn($q) => $q->where('gym_id', $gymId));
        } elseif ($this->userRole[0] === 'cityManager') {
            $cityId = $this->userData->city_id;
            $actUserQuery->where('city_id', $cityId);
            $actPaymentQuery->whereHas('user', fn($q) => $q->where('city_id', $cityId));
            $actAttendanceQuery->whereHas('user', fn($q) => $q->where('city_id', $cityId));
        } elseif ($this->userRole[0] === 'coach') {
            $coachId = $this->userID;
            $actUserQuery->where(function($q) use ($coachId) {
                $q->whereHas('courses.coaches', fn($sq) => $sq->where('users.id', $coachId))
                  ->orWhereHas('TrainingSessions', fn($sq) => $sq->where('users.id', $coachId));
            });
            $actPaymentQuery->whereRaw('0 = 1'); // Coaches don't see payments usually
            $actAttendanceQuery->whereHas('trainingSession.users', fn($q) => $q->where('users.id', $coachId));
        }

        $newUsers = $actUserQuery->latest()->limit(5)->get()->map(fn($u) => [
            'type' => 'user', 'icon' => 'fas fa-user-plus', 'color' => 'primary',
            'title' => __('New Registration'), 'description' => $u->name, 'time' => $u->created_at,
        ]);
        $newPayments = $actPaymentQuery->latest()->limit(5)->get()->map(fn($p) => [
            'type' => 'payment', 'icon' => 'fas fa-money-bill-wave', 'color' => 'success',
            'title' => __('Payment Received'), 'description' => ($p->user->name ?? 'User') . ' - ' . ($p->amount_cents / 100) . ' TND', 'time' => $p->created_at,
        ]);
        $newAtt = $actAttendanceQuery->latest()->limit(5)->get()->map(fn($a) => [
            'type' => 'attendance', 'icon' => 'fas fa-check-circle', 'color' => 'info',
            'title' => __('Attendance Marked'), 'description' => ($a->user->name ?? 'User') . ' - ' . ($a->trainingSession->name ?? ''), 'time' => $a->created_at,
        ]);

        $recentActivities = collect()->concat($newUsers)->concat($newPayments)->concat($newAtt)
            ->sortByDesc('time')->values()->take(10);

        if ($this->userRole[0] === 'coach') {
            $sessionsQuery->whereHas('users', function($q) {
                $q->where('users.id', $this->userID);
            });
        }

        $sessions = $sessionsQuery->whereBetween('day', [now()->startOfMonth()->subMonth(), now()->endOfMonth()->addMonth()])->get();
        foreach ($sessions as $s) {
            $calendarSessions[] = [
                'id' => $s->id,
                'title' => $s->name,
                'start' => $s->day . 'T' . $s->starts_at,
                'end' => $s->day . 'T' . $s->finishes_at,
                'backgroundColor' => 'var(--primary-color)',
                'borderColor' => 'var(--primary-color)',
                'url' => route('TrainingSessions.show_training_session', ['session' => $s->id]),
            ];
        }

        // Filter Logic
        $filterType = $request->input('filter_type', 'month');
        $customDate = $request->input('custom_date');
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $label = "";

        if ($filterType === 'today') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
            $prevStartDate = now()->subDay()->startOfDay();
            $prevEndDate = now()->subDay()->endOfDay();
            $label = __('Today');
        } elseif ($filterType === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
            $prevStartDate = now()->subWeek()->startOfWeek();
            $prevEndDate = now()->subWeek()->endOfWeek();
            $label = __('This Week');
        } elseif ($filterType === 'date' && $customDate) {
            $startDate = Carbon::parse($customDate)->startOfDay();
            $endDate = Carbon::parse($customDate)->endOfDay();
            $prevStartDate = Carbon::parse($customDate)->subDay()->startOfDay();
            $prevEndDate = Carbon::parse($customDate)->subDay()->endOfDay();
            $label = Carbon::parse($customDate)->format('d/m/Y');
        } elseif ($filterType === 'custom_range' && $startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($startDateInput)->endOfDay();
            $diff = $startDate->diffInDays($endDate);
            $prevStartDate = Carbon::parse($startDateInput)->subDays($diff + 1)->startOfDay();
            $prevEndDate = Carbon::parse($startDateInput)->subDay()->endOfDay();
            $label = Carbon::parse($startDateInput)->format('d/m') . ' - ' . Carbon::parse($endDateInput)->format('d/m');
        } else {
            // Default to month or if partial range data
            $filterType = 'month';
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            $prevStartDate = now()->subMonth()->startOfMonth();
            $prevEndDate = now()->subMonth()->endOfMonth();
            $label = now()->translatedFormat('F Y');
        }

        // Trends & Sparklines data
        $revenueTrend = 0;
        $paymentsTrend = 0;
        $attendanceTrend = 0;
        $revenueHistory = [];
        $paymentsHistory = [];
        $attendanceHistory = [];
        $monthlyRevenue = [];

        // Last 7 days for sparklines
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenueHistory[$date] = 0;
            $paymentsHistory[$date] = 0;
            $attendanceHistory[$date] = 0;
        }

        switch ($this->userRole['0']) {
            case 'admin':
                $canViewRevenue = Auth::user()->can('view_revenue_total');
                
                // Current Period
                $revenueTableSum = (int) Revenue::whereBetween('created_at', [$startDate, $endDate])->sum('price');
                $userPaymentSum = (int) UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents');
                $totalRevenue = ($revenueTableSum + $userPaymentSum) / 100;
                
                // Previous Period for Trends
                $prevRevenueTableSum = (int) Revenue::whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('price');
                $prevUserPaymentSum = (int) UserPayment::whereBetween('created_at', [$prevStartDate, $prevEndDate])->where('status', 'paid')->sum('amount_cents');
                $prevTotalRevenue = ($prevRevenueTableSum + $prevUserPaymentSum) / 100;
                $revenueTrend = $prevTotalRevenue > 0 ? (($totalRevenue - $prevTotalRevenue) / $prevTotalRevenue) * 100 : 100;

                $paymentsMonth = [
                    'total_cents' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount_cents'),
                    'paid_cents' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents'),
                    'due_cents' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->sum('amount_cents'),
                    'paid_count' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->count(),
                    'due_count' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->count(),
                ];

                $prevPaymentsTotal = UserPayment::whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('amount_cents');
                $paymentsTrend = $prevPaymentsTotal > 0 ? ((($paymentsMonth['total_cents'] ?? 0) - $prevPaymentsTotal) / $prevPaymentsTotal) * 100 : 100;
                
                $dailyAttendance = (object)[
                    'presents' => CourseAttendance::whereBetween('attendance_date', [$startDate, $endDate])->where('status', 'present')->count(),
                    'total' => CourseAttendance::whereBetween('attendance_date', [$startDate, $endDate])->count(),
                ];

                $prevAttendanceCount = CourseAttendance::whereBetween('attendance_date', [$prevStartDate, $prevEndDate])->where('status', 'present')->count();
                $attendanceTrend = $prevAttendanceCount > 0 ? (($dailyAttendance->presents - $prevAttendanceCount) / $prevAttendanceCount) * 100 : 100;

                // Sparklines Data (Admin)
                $revHistory = Revenue::where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(price) as total'))
                    ->groupBy('date')->pluck('total', 'date')->toArray();
                $payHistory = UserPayment::where('status', 'paid')->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_cents) as total'))
                    ->groupBy('date')->pluck('total', 'date')->toArray();
                
                foreach ($revenueHistory as $date => $val) {
                    $revenueHistory[$date] = (($revHistory[$date] ?? 0) + ($payHistory[$date] ?? 0)) / 100;
                    $paymentsHistory[$date] = ($payHistory[$date] ?? 0) / 100;
                }

                $attHistory = CourseAttendance::where('status', 'present')->where('attendance_date', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('attendance_date as date'), DB::raw('count(*) as count'))
                    ->groupBy('date')->pluck('count', 'date')->toArray();
                foreach ($attendanceHistory as $date => $val) {
                    $attendanceHistory[$date] = $attHistory[$date] ?? 0;
                }

                $coursesCount = Course::count();
                $fullCoursesCount = Course::whereNotNull('capacity')
                    ->withCount('participations')
                    ->get()
                    ->filter(function($c) { return $c->participations_count >= $c->capacity; })
                    ->count();
                $eventsCount = GymEvent::count();
                
                $expiringSoonCount = UserPayment::where('status', 'paid')
                    ->whereBetween('end_date', [now(), now()->addDays(7)])
                    ->count();

                $urgentUnpaidPayments = UserPayment::with('user')
                    ->where('status', 'unpaid')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                $paymentsMonth = [
                    'total_cents' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount_cents'),
                    'paid_cents' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents'),
                    'due_cents' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->sum('amount_cents'),
                    'paid_count' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->count(),
                    'due_count' => UserPayment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->count(),
                ];
                
                $dailyAttendance = (object)[
                    'presents' => CourseAttendance::whereBetween('attendance_date', [$startDate, $endDate])->where('status', 'present')->count(),
                    'total' => CourseAttendance::whereBetween('attendance_date', [$startDate, $endDate])->count(),
                ];

                $recentPayments = UserPayment::with('user')
                    ->where('status', 'paid')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                // Peak Hours Data (Heatmap)
                $peakHoursData = DB::table('attendances')
                    ->join('training_sessions', 'attendances.training_session_id', '=', 'training_sessions.id')
                    ->select(DB::raw('HOUR(training_sessions.starts_at) as hour'), DB::raw('count(*) as count'))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();

                // Monthly Revenue for last 6 months
                $monthlyRevenue = [];
                for ($i = 5; $i >= 0; $i--) {
                    $mStart = now()->subMonths($i)->startOfMonth();
                    $mEnd = now()->subMonths($i)->endOfMonth();
                    $rSum = (int) Revenue::whereBetween('created_at', [$mStart, $mEnd])->sum('price');
                    $pSum = (int) UserPayment::whereBetween('created_at', [$mStart, $mEnd])->where('status', 'paid')->sum('amount_cents');
                    $monthlyRevenue[$mStart->translatedFormat('M')] = ($rSum + $pSum) / 100;
                }
                break;

            case 'cityManager':
                $canViewRevenue = Auth::user()->can('view_revenue_city');
                $cityId = $this->userData->city_id;
                $userIds = User::where('city_id', $cityId)->pluck('id');
                
                $revenueTableSum = (int) Revenue::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->sum('price');
                $userPaymentSum = (int) UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents');
                $totalRevenue = ($revenueTableSum + $userPaymentSum) / 100;

                // Previous Period for Trends
                $prevRevenueTableSum = (int) Revenue::whereIn('user_id', $userIds)->whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('price');
                $prevUserPaymentSum = (int) UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$prevStartDate, $prevEndDate])->where('status', 'paid')->sum('amount_cents');
                $prevTotalRevenue = ($prevRevenueTableSum + $prevUserPaymentSum) / 100;
                $revenueTrend = $prevTotalRevenue > 0 ? (($totalRevenue - $prevTotalRevenue) / $prevTotalRevenue) * 100 : 100;

                $paymentsMonth = [
                    'total_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->sum('amount_cents'),
                    'paid_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents'),
                    'due_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->sum('amount_cents'),
                    'paid_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->count(),
                    'due_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->count(),
                ];

                $prevPaymentsTotal = UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('amount_cents');
                $paymentsTrend = $prevPaymentsTotal > 0 ? ((($paymentsMonth['total_cents'] ?? 0) - $prevPaymentsTotal) / $prevPaymentsTotal) * 100 : 100;
                
                $dailyAttendance = (object)[
                    'presents' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->where('status', 'present')->count(),
                    'total' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->count(),
                ];

                $prevAttendanceCount = CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$prevStartDate, $prevEndDate])->where('status', 'present')->count();
                $attendanceTrend = $prevAttendanceCount > 0 ? (($dailyAttendance->presents - $prevAttendanceCount) / $prevAttendanceCount) * 100 : 100;

                // Sparklines Data (City Manager)
                $revHistory = Revenue::whereIn('user_id', $userIds)->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(price) as total'))
                    ->groupBy('date')->pluck('total', 'date')->toArray();
                $payHistory = UserPayment::whereIn('user_id', $userIds)->where('status', 'paid')->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_cents) as total'))
                    ->groupBy('date')->pluck('total', 'date')->toArray();
                
                foreach ($revenueHistory as $date => $val) {
                    $revenueHistory[$date] = (($revHistory[$date] ?? 0) + ($payHistory[$date] ?? 0)) / 100;
                    $paymentsHistory[$date] = ($payHistory[$date] ?? 0) / 100;
                }

                $attHistory = CourseAttendance::whereIn('user_id', $userIds)->where('status', 'present')->where('attendance_date', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('attendance_date as date'), DB::raw('count(*) as count'))
                    ->groupBy('date')->pluck('count', 'date')->toArray();
                foreach ($attendanceHistory as $date => $val) {
                    $attendanceHistory[$date] = $attHistory[$date] ?? 0;
                }
                
                $this->gyms = Gym::where('city_id', $cityId)->count();
                $this->gymsManagers = User::role('gymManager')->where('city_id', $cityId)->count();
                $this->coaches = User::role('coach')->where('city_id', $cityId)->count();
                $this->users = User::role('user')->where('city_id', $cityId)->count();
                
                $coursesCount = Course::whereHas('participations.user', function($q) use ($cityId) {
                    $q->where('city_id', $cityId);
                })->distinct()->count();
                $fullCoursesCount = Course::whereHas('participations.user', function($q) use ($cityId) {
                        $q->where('city_id', $cityId);
                    })
                    ->whereNotNull('capacity')
                    ->withCount('participations')
                    ->get()
                    ->filter(function($c) { return $c->participations_count >= $c->capacity; })
                    ->count();
                $eventsCount = GymEvent::count();
                $expiringSoonCount = UserPayment::whereIn('user_id', $userIds)
                    ->where('status', 'paid')
                    ->whereBetween('end_date', [now(), now()->addDays(7)])
                    ->count();

                $urgentUnpaidPayments = UserPayment::with('user')
                    ->whereIn('user_id', $userIds)
                    ->where('status', 'unpaid')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                $paymentsMonth = [
                    'total_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->sum('amount_cents'),
                    'paid_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents'),
                    'due_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->sum('amount_cents'),
                    'paid_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->count(),
                    'due_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->count(),
                ];

                $dailyAttendance = (object)[
                    'presents' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->where('status', 'present')->count(),
                    'total' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->count(),
                ];

                // Peak Hours Data (Heatmap)
                $peakHoursData = DB::table('attendances')
                    ->join('training_sessions', 'attendances.training_session_id', '=', 'training_sessions.id')
                    ->join('users', 'attendances.user_id', '=', 'users.id')
                    ->where('users.city_id', $cityId)
                    ->select(DB::raw('HOUR(training_sessions.starts_at) as hour'), DB::raw('count(*) as count'))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();

                // Monthly Revenue for last 6 months
                $monthlyRevenue = [];
                for ($i = 5; $i >= 0; $i--) {
                    $mStart = now()->subMonths($i)->startOfMonth();
                    $mEnd = now()->subMonths($i)->endOfMonth();
                    $rSum = (int) Revenue::whereIn('user_id', $userIds)->whereBetween('created_at', [$mStart, $mEnd])->sum('price');
                    $pSum = (int) UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$mStart, $mEnd])->where('status', 'paid')->sum('amount_cents');
                    $monthlyRevenue[$mStart->translatedFormat('M')] = ($rSum + $pSum) / 100;
                }
                break;

            case 'gymManager':
                $canViewRevenue = Auth::user()->can('view_revenue_gym');
                $gymId = $this->userData->gym_id;
                $userIds = User::where('gym_id', $gymId)->pluck('id');
                
                $revenueTableSum = (int) Revenue::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->sum('price');
                $userPaymentSum = (int) UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents');
                $totalRevenue = ($revenueTableSum + $userPaymentSum) / 100;

                // Previous Period for Trends
                $prevRevenueTableSum = (int) Revenue::whereIn('user_id', $userIds)->whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('price');
                $prevUserPaymentSum = (int) UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$prevStartDate, $prevEndDate])->where('status', 'paid')->sum('amount_cents');
                $prevTotalRevenue = ($prevRevenueTableSum + $prevUserPaymentSum) / 100;
                $revenueTrend = $prevTotalRevenue > 0 ? (($totalRevenue - $prevTotalRevenue) / $prevTotalRevenue) * 100 : 100;

                $paymentsMonth = [
                    'total_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->sum('amount_cents'),
                    'paid_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents'),
                    'due_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->sum('amount_cents'),
                    'paid_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->count(),
                    'due_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->count(),
                ];

                $prevPaymentsTotal = UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('amount_cents');
                $paymentsTrend = $prevPaymentsTotal > 0 ? ((($paymentsMonth['total_cents'] ?? 0) - $prevPaymentsTotal) / $prevPaymentsTotal) * 100 : 100;
                
                $dailyAttendance = (object)[
                    'presents' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->where('status', 'present')->count(),
                    'total' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->count(),
                ];

                $prevAttendanceCount = CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$prevStartDate, $prevEndDate])->where('status', 'present')->count();
                $attendanceTrend = $prevAttendanceCount > 0 ? (($dailyAttendance->presents - $prevAttendanceCount) / $prevAttendanceCount) * 100 : 100;

                // Sparklines Data (Gym Manager)
                $revHistory = Revenue::whereIn('user_id', $userIds)->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(price) as total'))
                    ->groupBy('date')->pluck('total', 'date')->toArray();
                $payHistory = UserPayment::whereIn('user_id', $userIds)->where('status', 'paid')->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_cents) as total'))
                    ->groupBy('date')->pluck('total', 'date')->toArray();
                
                foreach ($revenueHistory as $date => $val) {
                    $revenueHistory[$date] = (($revHistory[$date] ?? 0) + ($payHistory[$date] ?? 0)) / 100;
                    $paymentsHistory[$date] = ($payHistory[$date] ?? 0) / 100;
                }

                $attHistory = CourseAttendance::whereIn('user_id', $userIds)->where('status', 'present')->where('attendance_date', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('attendance_date as date'), DB::raw('count(*) as count'))
                    ->groupBy('date')->pluck('count', 'date')->toArray();
                foreach ($attendanceHistory as $date => $val) {
                    $attendanceHistory[$date] = $attHistory[$date] ?? 0;
                }
                
                $this->coaches = User::role('coach')->where('gym_id', $gymId)->count();
                $this->users = User::role('user')->where('gym_id', $gymId)->count();
                
                $coursesCount = Course::whereHas('participations.user', function($q) use ($gymId) {
                    $q->where('gym_id', $gymId);
                })->distinct()->count();
                $fullCoursesCount = Course::whereHas('participations.user', function($q) use ($gymId) {
                        $q->where('gym_id', $gymId);
                    })
                    ->whereNotNull('capacity')
                    ->withCount('participations')
                    ->get()
                    ->filter(function($c) { return $c->participations_count >= $c->capacity; })
                    ->count();
                $eventsCount = GymEvent::count();
                $expiringSoonCount = UserPayment::whereIn('user_id', $userIds)
                    ->where('status', 'paid')
                    ->whereBetween('end_date', [now(), now()->addDays(7)])
                    ->count();

                $urgentUnpaidPayments = UserPayment::with('user')
                    ->whereIn('user_id', $userIds)
                    ->where('status', 'unpaid')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                $paymentsMonth = [
                    'total_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->sum('amount_cents'),
                    'paid_cents' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->sum('amount_cents'),
                    'paid_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid')->count(),
                    'due_count' => UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$startDate, $endDate])->where('status', 'unpaid')->count(),
                ];

                $dailyAttendance = (object)[
                    'presents' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->where('status', 'present')->count(),
                    'total' => CourseAttendance::whereIn('user_id', $userIds)->whereBetween('attendance_date', [$startDate, $endDate])->count(),
                ];

                // Peak Hours Data (Heatmap)
                $peakHoursData = DB::table('attendances')
                    ->join('training_sessions', 'attendances.training_session_id', '=', 'training_sessions.id')
                    ->join('users', 'attendances.user_id', '=', 'users.id')
                    ->where('users.gym_id', $gymId)
                    ->select(DB::raw('HOUR(training_sessions.starts_at) as hour'), DB::raw('count(*) as count'))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();

                // Monthly Revenue for last 6 months
                $monthlyRevenue = [];
                for ($i = 5; $i >= 0; $i--) {
                    $mStart = now()->subMonths($i)->startOfMonth();
                    $mEnd = now()->subMonths($i)->endOfMonth();
                    $rSum = (int) Revenue::whereIn('user_id', $userIds)->whereBetween('created_at', [$mStart, $mEnd])->sum('price');
                    $pSum = (int) UserPayment::whereIn('user_id', $userIds)->whereBetween('created_at', [$mStart, $mEnd])->where('status', 'paid')->sum('amount_cents');
                    $monthlyRevenue[$mStart->translatedFormat('M')] = ($rSum + $pSum) / 100;
                }
                break;

            case 'coach':
                $userOfGym = User::with(['trainingSessions'])->where('id', $this->userID)->first();
                if (count($userOfGym->trainingSessions) <= 0) {
                    return view('empty');
                }
                
                $studentIds = Participation::whereIn('course_id', $userOfGym->trainingSessions->pluck('course_id'))->pluck('user_id')->unique();
                $totalStudents = count($studentIds);
                
                $todaySessions = TrainingSession::whereHas('users', function($q) {
                        $q->where('users.id', $this->userID);
                    })
                    ->whereDate('day', now())
                    ->orderBy('starts_at', 'asc')
                    ->get();

                $dailyAttendance = (object)[
                    'presents' => CourseAttendance::whereIn('user_id', $studentIds)->whereBetween('attendance_date', [$startDate, $endDate])->where('status', 'present')->count(),
                    'total' => CourseAttendance::whereIn('user_id', $studentIds)->whereBetween('attendance_date', [$startDate, $endDate])->count(),
                ];

                $upcomingExams = \App\Models\Belt::whereIn('user_id', $studentIds)
                    ->where('exam_at', '>=', now())
                    ->with('user')
                    ->orderBy('exam_at', 'asc')
                    ->limit(5)
                    ->get();

                // Peak Hours Data (Heatmap)
                $peakHoursData = DB::table('attendances')
                    ->join('training_sessions', 'attendances.training_session_id', '=', 'training_sessions.id')
                    ->whereIn('training_session_id', $userOfGym->trainingSessions->pluck('id'))
                    ->select(DB::raw('HOUR(training_sessions.starts_at) as hour'), DB::raw('count(*) as count'))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();

                return view("welcome", [
                    'trainingSessions' => $userOfGym->trainingSessions,
                    'upcomingExams' => $upcomingExams,
                    'totalStudents' => $totalStudents,
                    'dailyAttendance' => $dailyAttendance,
                    'canViewRevenue' => false,
                    'revenueInDollars' => '0,00 TND',
                    'paymentsMonth' => $paymentsMonth,
                    'coursesCount' => $coursesCount,
                    'eventsCount' => $eventsCount,
                    'revenueTrend' => $revenueTrend,
                    'paymentsTrend' => $paymentsTrend,
                    'attendanceTrend' => $attendanceTrend,
                    'revenueHistory' => array_values($revenueHistory),
                    'paymentsHistory' => array_values($paymentsHistory),
                    'attendanceHistory' => array_values($attendanceHistory),
                    'cities' => 0,
                    'citiesManagers' => 0,
                    'gyms' => 0,
                    'gymsManagers' => 0,
                    'coaches' => 0,
                    'users' => 0,
                    'cashRegisters' => $this->cashRegisters,
                    'cashRegistersBalance' => $this->cashRegistersBalance,
                    'cashRegistersList' => $this->cashRegistersList,
                    'filterType' => $filterType,
                    'filterLabel' => $label,
                    'customDate' => $customDate,
                    'startDateInput' => $startDateInput,
                    'endDateInput' => $endDateInput,
                    'peakHoursData' => $peakHoursData,
                    'calendarSessions' => $calendarSessions,
                    'expiringSoonCount' => $expiringSoonCount,
                    'todaySessions' => $todaySessions ?? [],
                    'urgentUnpaidPayments' => $urgentUnpaidPayments ?? [],
                    'monthlyRevenue' => $monthlyRevenue,
                    'recentActivities' => $recentActivities ?? collect(),
                ]);
        }

        // Peak Hours Data (Heatmap)
        // (already handled in switch)

        return view("welcome", [
            'cities' => $this->cities,
            'citiesManagers' => $this->citiesManagers,
            'gyms' => $this->gyms,
            'gymsManagers' => $this->gymsManagers,
            'coaches' => $this->coaches,
            'users' => $this->users,
            'cashRegisters' => $this->cashRegisters,
            'cashRegistersBalance' => $this->cashRegistersBalance,
            'cashRegistersList' => $this->cashRegistersList,
            'revenueInDollars' => number_format($totalRevenue, 2, ',', ' ') . ' TND',
            'canViewRevenue' => $canViewRevenue,
            'paymentsMonth' => $paymentsMonth,
            'dailyAttendance' => $dailyAttendance,
            'coursesCount' => $coursesCount,
            'fullCoursesCount' => $fullCoursesCount,
            'eventsCount' => $eventsCount,
            'revenueTrend' => $revenueTrend,
            'paymentsTrend' => $paymentsTrend,
            'attendanceTrend' => $attendanceTrend,
            'revenueHistory' => array_values($revenueHistory),
            'paymentsHistory' => array_values($paymentsHistory),
            'attendanceHistory' => array_values($attendanceHistory),
            'recentPayments' => $recentPayments ?? [],
            'recentActivities' => $recentActivities,
            'filterType' => $filterType,
            'filterLabel' => $label,
            'calendarSessions' => $calendarSessions,
            'expiringSoonCount' => $expiringSoonCount,
            'customDate' => $customDate,
            'startDateInput' => $startDateInput,
            'endDateInput' => $endDateInput,
            'peakHoursData' => $peakHoursData ?? collect(),
            'urgentUnpaidPayments' => $urgentUnpaidPayments ?? [],
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }
}
