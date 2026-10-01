<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\CourseAttendance;
use App\Models\Gym;
use App\Models\User;
use App\Models\Course;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\UserPayment;
use App\Models\ExpenseCategory as ExpenseCategoryModel;
use App\Models\Expense as ExpenseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  INDEX — Main reports dashboard (uses shared composeData)
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        try {
            $data = $this->composeData($request, includeLists: true);
            return view('reports.index', $data);
        } catch (\Exception $e) {
            Log::error('Report index error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'filters' => $request->query(),
            ]);
            return back()->with('error', __('messages.report_error') . ': ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  EXPORT PDF
    // ─────────────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        try {
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return redirect()
                    ->route('reports.index', $request->query())
                    ->with('error', __('messages.pdf_not_available'));
            }

            $data = $this->composeData($request, includeLists: false);

            // Render the view to HTML
            $html = view('reports.pdf', $data)->render();

            // Process Arabic text with ar-php if available
            $html = $this->processArabicText($html);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('a4', 'portrait');

            $filename = sprintf(
                'rapport_%s_%s.pdf',
                $data['month'],
                $data['selectedGymId'] ?: 'all'
            );

            Log::info('PDF report generated', [
                'month'  => $data['month'],
                'gym_id' => $data['selectedGymId'],
                'file'   => $filename,
            ]);

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Report PDF Export Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'filters' => $request->query(),
            ]);
            return redirect()
                ->route('reports.index', $request->query())
                ->with('error', 'Erreur PDF: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  EXPORT EXCEL
    // ─────────────────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        try {
            if (!class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
                return redirect()
                    ->route('reports.index', $request->query())
                    ->with('error', __('messages.excel_not_available'));
            }

            $data = $this->composeData($request, includeLists: false);
            $view = view('reports.excel', $data);
            $filename = sprintf(
                'rapport_%s_%s.xlsx',
                $data['month'],
                $data['selectedGymId'] ?: 'all'
            );

            Log::info('Excel report generated', [
                'month'  => $data['month'],
                'gym_id' => $data['selectedGymId'],
                'file'   => $filename,
            ]);

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\ReportsViewExport($view),
                $filename
            );
        } catch (\Exception $e) {
            Log::error('Report Excel Export Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return redirect()
                ->route('reports.index', $request->query())
                ->with('error', 'Erreur Excel: ' . $e->getMessage());
        }
    }

    // =================================================================
    //  PRIVATE: Arabic text processing for PDF
    // =================================================================
    private function processArabicText(string $html): string
    {
        try {
            if (!class_exists(\ArPHP\I18N\Arabic::class)) {
                Log::warning('ArPHP library not available — skipping Arabic processing');
                return $html;
            }

            $arabic = new \ArPHP\I18N\Arabic();
            $p = $arabic->arIdentify($html);

            if (empty($p) || count($p) < 2) {
                return $html;
            }

            for ($i = count($p) - 1; $i >= 1; $i -= 2) {
                $start  = $p[$i - 1];
                $length = $p[$i] - $p[$i - 1];
                $utf8ar = $arabic->utf8Glyphs(substr($html, $start, $length));
                $html   = substr_replace($html, $utf8ar, $start, $length);
            }
        } catch (\Exception $e) {
            Log::warning('Arabic text processing failed', ['error' => $e->getMessage()]);
        }

        return $html;
    }

    // =================================================================
    //  PRIVATE: Shared data composition for all outputs
    // =================================================================
    private function composeData(Request $request, bool $includeLists = false): array
    {
        // ── 1. Parse all filters ──────────────────────────────────
        $filters = $this->parseFilters($request);

        // ── 2. Date range ─────────────────────────────────────────
        [$month, $start, $end] = $this->parseDateRange($filters['month']);

        // ── 3. Reference data (cached) ────────────────────────────
        $gyms = Cache::remember('gyms:list', 600, fn () =>
            Gym::select('id', 'name')->orderBy('name')->get()
        );

        $cities = Cache::remember('cities:list', 600, fn () =>
            \App\Models\City::select('id', 'name')->orderBy('name')->get()
        );

        $coaches = Cache::remember('coaches:list', 600, fn () =>
            User::role('coach')->select('id', 'name')->orderBy('name')->get()
        );

        $courses = Cache::remember('courses:list', 600, fn () =>
            Course::select('id', 'name', 'gym_id')->orderBy('name')->get()
        );

        $cashRegisters = collect();
        if ($filters['gymId']) {
            $cashRegisters = \App\Models\CashRegister::where('gym_id', $filters['gymId'])
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        // For PDF/Excel, load all cash registers so we can display the name
        $allCashRegisters = Cache::remember('cash_registers:all', 600, fn () =>
            \App\Models\CashRegister::select('id', 'name', 'gym_id')->orderBy('name')->get()
        );

        // ── 4. Section visibility toggles ─────────────────────────
        $showSummary    = (int) $request->input('show_summary', 1) === 1;
        $showRevenue    = (int) $request->input('show_revenue', 1) === 1;
        $showAttendance = (int) $request->input('show_attendance', 1) === 1;
        $showExpenses   = (int) $request->input('show_expenses', 1) === 1;
        $showCoachPay   = (int) $request->input('show_coach_pay', 1) === 1;
        $showUserPay    = (int) $request->input('show_user_pay', 1) === 1;
        $showTopCoaches = (int) $request->input('show_top_coaches', 1) === 1;
        $showTopGyms    = (int) $request->input('show_top_gyms', 1) === 1;

        // ── 5. Revenue calculation ────────────────────────────────
        $cacheKey = $this->buildCacheKey('reports:revenue', $month, $filters);
        $revenueCalc = Cache::remember($cacheKey, 300, function () use ($start, $end, $filters) {
            return $this->calculateRevenue($start, $end, $filters);
        });

        $revenue = [
            'month'      => $month,
            'start'      => $start,
            'end'        => $end,
            'total_cents' => $revenueCalc['revenueTotal'],
            'sources'    => [
                [
                    'label'        => __('messages.package_purchase'),
                    'transactions' => $revenueCalc['packageRevenueCount'],
                    'amount_cents' => $revenueCalc['packageRevenueSum'],
                ],
                [
                    'label'        => __('messages.event_registration'),
                    'transactions' => $revenueCalc['eventRevenueCount'],
                    'amount_cents' => $revenueCalc['eventRevenueSum'],
                ],
                [
                    'label'        => __('messages.belt_exam'),
                    'transactions' => $revenueCalc['beltRevenueCount'],
                    'amount_cents' => $revenueCalc['beltRevenueSum'],
                ],
            ],
        ];

        // ── 6. Attendance ─────────────────────────────────────────
        $attendanceCacheKey = $this->buildCacheKey('reports:attendance', $month, $filters);
        $attendance = Cache::remember($attendanceCacheKey, 300, function () use ($start, $end, $filters) {
            return $this->calculateAttendance($start, $end, $filters);
        });

        // ── 7. Expenses ───────────────────────────────────────────
        [$expenseTotal, $expensesByCategory] = $this->calculateExpenses($start, $end, $filters);
        $netTotal = $revenueCalc['revenueTotal'] - $expenseTotal;

        // ── 8. User payments (Always include lists for the new tabular report) ──────────────────────────────────────
        $userPayments    = $this->calculateUserPayments($start, $end, $filters);
        $userPaymentsList = $this->getUserPaymentsList($start, $end, $filters);

        // ── 9. Coach payments (Always include lists for the new tabular report) ─────────────────────────────────────
        [$coachPayments, $coachPaymentsList] = $this->calculateCoachPayments(
            $start, $end, $filters, true // $includeLists = true 
        );

        // ── 10. Summary statistics ────────────────────────────────
        $summary = [];
        if ($showSummary) {
            $summary = $this->buildSummary($revenueCalc['revenueTotal'], $expenseTotal, $netTotal);
        }

        // ── 11. Top coaches & gyms (Scoped by date) ────────────────────────────────
        $coachesTop = collect();
        if ($showTopCoaches) {
            $coachesTop = User::role('coach')
                ->withCount([
                    'TrainingSessions as training_sessions_count' => function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    },
                    'attendances as attendances_count' => function ($q) use ($start, $end) {
                        $q->whereBetween('attendance_at', [$start, $end]);
                    }
                ])
                ->orderByDesc('training_sessions_count')
                ->limit(10)
                ->get(['id', 'name', 'email']);
        }

        $gymsTop = collect();
        if ($showTopGyms) {
            $gymsTop = Gym::withCount(['users' => function ($q) use ($start, $end) {
                // Count users joined in the period (or active, depending on the business rule, here joined)
                $q->whereBetween('created_at', [$start, $end]);
            }])
                ->orderByDesc('users_count')
                ->limit(10)
                ->get(['id', 'name']);
        }

        // ── 12. Return unified data array ─────────────────────────
        return [
            // Date
            'month' => $month,
            'start' => $start,
            'end'   => $end,

            // Reference data
            'gyms'           => $gyms,
            'cities'         => $cities,
            'coaches'        => $coaches,
            'courses'        => $courses,
            'cashRegisters'  => $cashRegisters->isNotEmpty() ? $cashRegisters : $allCashRegisters,

            // Active filters
            'selectedGymId'          => $filters['gymId'],
            'selectedCityId'         => $filters['cityId'],
            'selectedCashRegisterId' => $filters['cashRegisterId'],
            'selectedCourseId'       => $filters['courseId'],
            'selectedCoachId'        => $filters['coachId'],
            'paymentStatus'          => $filters['paymentStatus'],
            'attendanceStatus'       => $filters['attendanceStatus'],
            'userStatus'             => $filters['userStatus'],
            'amountMin'              => $filters['amountMin'],
            'amountMax'              => $filters['amountMax'],

            // Financial data
            'revenue' => $revenue,
            'expense' => [
                'total_cents'     => $expenseTotal,
                'by_category'     => $expensesByCategory,
                'net_total_cents' => $netTotal,
            ],
            'payments' => [
                'users'   => $userPayments,
                'coaches' => $coachPayments,
            ],

            // Lists (for detail tables)
            'userPaymentsList'  => $userPaymentsList,
            'coachPaymentsList' => $coachPaymentsList,

            // Statistics
            'summary'    => $summary,
            'coachesTop' => $coachesTop,
            'gymsTop'    => $gymsTop,

            // Section visibility flags
            'show_summary'     => $showSummary,
            'show_revenue'     => $showRevenue,
            'show_attendance'  => $showAttendance,
            'show_expenses'    => $showExpenses,
            'show_coach_pay'   => $showCoachPay,
            'show_user_pay'    => $showUserPay,
            'show_top_coaches' => $showTopCoaches,
            'show_top_gyms'    => $showTopGyms,

            // Attendance data (must be after reference data)
            'attendance' => $attendance,
        ];
    }

    // =================================================================
    //  PRIVATE HELPERS
    // =================================================================

    /**
     * Parse & validate all filter parameters from the request.
     */
    private function parseFilters(Request $request): array
    {
        return [
            'month'            => $request->string('month')->toString(),
            'gymId'            => $request->filled('gym_id') ? $request->integer('gym_id') : null,
            'cityId'           => $request->filled('city_id') ? $request->integer('city_id') : null,
            'cashRegisterId'   => $request->filled('cash_register_id') ? $request->integer('cash_register_id') : null,
            'paymentStatus'    => $request->filled('payment_status') ? $request->string('payment_status')->toString() : null,
            'coachId'          => $request->filled('coach_id') ? $request->integer('coach_id') : null,
            'attendanceStatus' => $request->filled('attendance_status') ? $request->string('attendance_status')->toString() : null,
            'courseId'          => $request->filled('course_id') ? $request->integer('course_id') : null,
            'userStatus'       => $request->filled('user_status') ? $request->string('user_status')->toString() : null,
            'amountMin'        => $request->has('amount_min') && $request->input('amount_min') !== null && $request->input('amount_min') !== ''
                ? (float) $request->input('amount_min')
                : null,
            'amountMax'        => $request->has('amount_max') && $request->input('amount_max') !== null && $request->input('amount_max') !== ''
                ? (float) $request->input('amount_max')
                : null,
        ];
    }

    /**
     * Parse a "Y-m" string into [month, start, end].
     */
    private function parseDateRange(?string $month): array
    {
        if ($month && str_contains($month, '-')) {
            $parts = explode('-', $month);
            $start = Carbon::createFromDate((int)$parts[0], (int)$parts[1], 1)->startOfDay();
            $end   = Carbon::createFromDate((int)$parts[0], (int)$parts[1], 1)->endOfMonth()->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();
            $month = $start->format('Y-m');
        }

        return [$month, $start, $end];
    }

    /**
     * Build a deterministic cache key from all active filters.
     */
    private function buildCacheKey(string $prefix, string $month, array $filters): string
    {
        $parts = [
            $prefix,
            $month,
            $filters['gymId'] ?: 'all',
            $filters['cityId'] ?: 'all',
            $filters['cashRegisterId'] ?: 'all',
            $filters['courseId'] ?: 'all',
            $filters['coachId'] ?: 'all',
            $filters['attendanceStatus'] ?: 'all',
        ];
        return implode(':', $parts);
    }

    /**
     * Apply gym/city scope to a query via user relationship.
     */
    private function applyGymCityScope($query, array $filters): void
    {
        if ($filters['gymId'] || $filters['cityId']) {
            $query->whereHas('user', function ($q) use ($filters) {
                if ($filters['gymId']) {
                    $q->where('gym_id', $filters['gymId']);
                }
                if ($filters['cityId']) {
                    $q->where('city_id', $filters['cityId']);
                }
            });
        }
    }

    /**
     * Calculate revenue breakdown by source.
     * Returns fresh data — designed to be wrapped by Cache::remember().
     */
    private function calculateRevenue(Carbon $start, Carbon $end, array $filters): array
    {
        // Package + Event revenue
        $revenueBase = Revenue::query()->whereBetween('created_at', [$start, $end]);
        if ($filters['cashRegisterId']) {
            $revenueBase->where('cash_register_id', $filters['cashRegisterId']);
        }
        $this->applyGymCityScope($revenueBase, $filters);

        $packageRevenueSum   = (int) $revenueBase->clone()->whereNotNull('training_package_id')->sum('price');
        $eventRevenueSum     = (int) $revenueBase->clone()->whereNull('training_package_id')->sum('price');
        $packageRevenueCount = (int) $revenueBase->clone()->whereNotNull('training_package_id')->count();
        $eventRevenueCount   = (int) $revenueBase->clone()->whereNull('training_package_id')->count();

        // Belt exam revenue
        $beltQuery = \App\Models\Belt::query()
            ->whereBetween('achieved_at', [$start->toDateString(), $end->toDateString()])
            ->whereNotNull('price_cents');

        if ($filters['cashRegisterId']) {
            $beltQuery->where('cash_register_id', $filters['cashRegisterId']);
        }
        if ($filters['gymId'] || $filters['cityId']) {
            $beltQuery->whereHas('user', function ($q) use ($filters) {
                if ($filters['gymId']) { $q->where('gym_id', $filters['gymId']); }
                if ($filters['cityId']) { $q->where('city_id', $filters['cityId']); }
            });
        }

        $beltRevenueSum   = (int) $beltQuery->clone()->sum('price_cents');
        $beltRevenueCount = (int) $beltQuery->clone()->count();

        $revenueTotal = $packageRevenueSum + $eventRevenueSum + $beltRevenueSum;

        return compact(
            'packageRevenueSum', 'eventRevenueSum',
            'packageRevenueCount', 'eventRevenueCount',
            'beltRevenueSum', 'beltRevenueCount',
            'revenueTotal'
        );
    }

    /**
     * Calculate attendance per course.
     * Returns a Collection designed to be cached.
     */
    private function calculateAttendance(Carbon $start, Carbon $end, array $filters)
    {
        // Pre-load all course coaches in one query to avoid N+1
        $courseCoachMap = [];
        try {
            $coursesWithCoaches = Course::with('coaches:id,name')->get();
            foreach ($coursesWithCoaches as $c) {
                $courseCoachMap[$c->id] = $c->coaches->pluck('name')->implode(', ') ?: '-';
            }
        } catch (\Exception $e) {
            Log::warning('Could not load course coaches', ['error' => $e->getMessage()]);
        }

        $query = CourseAttendance::query()
            ->join('participations', 'course_attendances.participation_id', '=', 'participations.id')
            ->join('courses', 'participations.course_id', '=', 'courses.id')
            ->leftJoin('gyms', 'courses.gym_id', '=', 'gyms.id')
            ->whereBetween('course_attendances.attendance_date', [$start->toDateString(), $end->toDateString()])
            ->select([
                'courses.id as course_id',
                'courses.name as course_name',
                DB::raw('COUNT(*) as total_sessions'),
                DB::raw("SUM(CASE WHEN course_attendances.status = 'present' THEN 1 ELSE 0 END) as presents"),
                DB::raw("SUM(CASE WHEN course_attendances.status = 'absent' THEN 1 ELSE 0 END) as absents"),
                DB::raw("SUM(CASE WHEN course_attendances.status = 'excused' THEN 1 ELSE 0 END) as excused"),
                DB::raw("GROUP_CONCAT(DISTINCT course_attendances.attendance_date ORDER BY course_attendances.attendance_date ASC SEPARATOR ', ') as session_dates"),
                'gyms.name as gym_name',
                'gyms.id as gym_id',
            ])
            ->groupBy('courses.id', 'courses.name', 'gyms.name', 'gyms.id');

        if ($filters['gymId']) {
            $query->where('courses.gym_id', $filters['gymId']);
        }
        if ($filters['courseId']) {
            $query->where('courses.id', $filters['courseId']);
        }
        if ($filters['attendanceStatus'] && in_array($filters['attendanceStatus'], ['present', 'absent', 'excused'])) {
            $query->where('course_attendances.status', $filters['attendanceStatus']);
        }

        return $query->get()->map(function ($row) use ($courseCoachMap) {
            $total   = (int) $row->total_sessions;
            $present = (int) $row->presents;
            $rate    = $total > 0 ? round(($present / $total) * 100, 2) : 0.0;

            return [
                'course_id'     => $row->course_id,
                'course_name'   => $row->course_name,
                'gym_id'        => $row->gym_id,
                'gym_name'      => $row->gym_name,
                'total_sessions' => $total,
                'presents'      => $present,
                'absents'       => (int) $row->absents,
                'excused'       => (int) $row->excused,
                'presence_rate' => $rate,
                'session_dates' => $row->session_dates,
                'coach_names'   => $courseCoachMap[$row->course_id] ?? '-',
            ];
        });
    }

    /**
     * Calculate expenses by category.
     */
    private function calculateExpenses(Carbon $start, Carbon $end, array $filters): array
    {
        $query = ExpenseModel::query()
            ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()]);

        if ($filters['gymId']) { $query->where('gym_id', $filters['gymId']); }
        if ($filters['coachId']) { $query->where('coach_id', $filters['coachId']); }

        $expenseTotal = (int) $query->clone()->sum('amount_cents');

        // Pre-load all category names in one query
        $categoryNames = ExpenseCategoryModel::pluck('name', 'id');

        $expensesByCategory = $query->clone()
            ->selectRaw('expense_category_id, SUM(amount_cents) as amount_cents')
            ->groupBy('expense_category_id')
            ->get()
            ->map(function ($row) use ($categoryNames) {
                return [
                    'label'        => $categoryNames[$row->expense_category_id] ?? '—',
                    'amount_cents' => (int) $row->amount_cents,
                ];
            });

        return [$expenseTotal, $expensesByCategory];
    }

    /**
     * Calculate user payment summary.
     */
    private function calculateUserPayments(Carbon $start, Carbon $end, array $filters): array
    {
        $base = $this->buildUserPaymentsQuery($start, $end, $filters);

        return [
            'total_cents' => (int) $base->clone()->sum('amount_cents'),
            'paid_cents'  => (int) $base->clone()->where('status', 'paid')->sum('amount_cents'),
            'paid_count'  => (int) $base->clone()->where('status', 'paid')->count(),
            'due_count'   => (int) $base->clone()->where('status', 'due')->count(),
        ];
    }

    /**
     * Get detailed user payments list for tables.
     */
    private function getUserPaymentsList(Carbon $start, Carbon $end, array $filters)
    {
        return $this->buildUserPaymentsQuery($start, $end, $filters)
            ->with([
                'cashRegister',
                'user.gym',
                'user.city',
                'user.category',
                'user.belts' => fn ($q) => $q->orderByDesc('achieved_at'),
            ])
            ->orderByDesc('amount_cents')
            ->get(['id', 'user_id', 'cash_register_id', 'amount_cents', 'billing_month', 'status', 'method', 'paid_at', 'notes']);
    }

    /**
     * Build the base user payments query with all filters applied.
     */
    private function buildUserPaymentsQuery(Carbon $start, Carbon $end, array $filters)
    {
        $query = UserPayment::query()
            ->whereBetween('billing_month', [$start->toDateString(), $end->toDateString()]);

        if ($filters['cashRegisterId']) {
            $query->where('cash_register_id', $filters['cashRegisterId']);
        }
        if ($filters['gymId'] || $filters['cityId']) {
            $query->whereHas('user', function ($q) use ($filters) {
                if ($filters['gymId']) { $q->where('gym_id', $filters['gymId']); }
                if ($filters['cityId']) { $q->where('city_id', $filters['cityId']); }
            });
        }
        if ($filters['paymentStatus']) {
            $query->where('status', $filters['paymentStatus']);
        }
        if ($filters['userStatus'] === 'active') {
            $query->whereHas('user', fn ($q) => $q->withoutBanned());
        } elseif ($filters['userStatus'] === 'banned') {
            $query->whereHas('user', function ($q) {
                if (method_exists($q->getModel(), 'scopeOnlyBanned')) {
                    $q->onlyBanned();
                } else {
                    $q->whereRaw('1=0');
                }
            });
        }
        if ($filters['amountMin'] !== null || $filters['amountMax'] !== null) {
            if ($filters['amountMin'] !== null && $filters['amountMax'] !== null) {
                $query->whereBetween('amount_cents', [
                    (int) round($filters['amountMin'] * 100),
                    (int) round($filters['amountMax'] * 100),
                ]);
            } elseif ($filters['amountMin'] !== null) {
                $query->where('amount_cents', '>=', (int) round($filters['amountMin'] * 100));
            } else {
                $query->where('amount_cents', '<=', (int) round($filters['amountMax'] * 100));
            }
        }

        return $query;
    }

    /**
     * Calculate coach payments (salary expenses filtered by category).
     */
    private function calculateCoachPayments(Carbon $start, Carbon $end, array $filters, bool $includeLists): array
    {
        $coachCategoryIds = ExpenseCategoryModel::whereIn('name', [
            'Coach', 'Paie Coach', 'Salaire Coach', 'Salaire',
        ])->pluck('id');

        $query = ExpenseModel::query()
            ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()]);

        if ($filters['gymId']) {
            $query->where('gym_id', $filters['gymId']);
        }
        if ($coachCategoryIds->count() > 0) {
            $query->whereIn('expense_category_id', $coachCategoryIds->all());
        } else {
            $query->whereRaw('1 = 0'); // No categories match — return empty
        }

        $coachPayments = [
            'total_cents'  => (int) $query->clone()->sum('amount_cents'),
            'transactions' => (int) $query->clone()->count(),
        ];

        $coachPaymentsList = collect();
        if ($includeLists) {
            $coachPaymentsList = $query->clone()
                ->with(['coach', 'category', 'gym'])
                ->orderByDesc('expense_date')
                ->get(['id', 'coach_id', 'expense_category_id', 'gym_id', 'amount_cents', 'expense_date', 'method', 'notes']);
        }

        return [$coachPayments, $coachPaymentsList];
    }

    /**
     * Build the global summary statistics.
     */
    private function buildSummary(int $revenueTotal, int $expenseTotal, int $netTotal): array
    {
        $usersTotal  = (int) User::role('user')->count();
        $usersActive = (int) User::role('user')->withoutBanned()->count();

        return [
            'users_total'         => $usersTotal,
            'users_active'        => $usersActive,
            'users_banned'        => $usersTotal - $usersActive,
            'coaches_total'       => (int) User::role('coach')->count(),
            'gyms_total'          => (int) Gym::count(),
            'courses_total'       => (int) Course::count(),
            'events_total'        => (int) Event::count(),
            'event_regs_total'    => (int) EventRegistration::count(),
            'revenue_total_cents' => $revenueTotal,
            'expense_total_cents' => $expenseTotal,
            'net_total_cents'     => $netTotal,
        ];
    }
}
