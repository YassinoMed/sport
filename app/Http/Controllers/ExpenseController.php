<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Course;
use App\Models\ExpenseCategory;
use App\Models\ExpenseSubcategory;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->string('month')->toString();
        $gymId = $request->integer('gym_id');
        $coachId = $request->integer('coach_id');

        if ($month) {
            [$year, $mon] = explode('-', $month);
            $start = Carbon::createFromDate((int)$year, (int)$mon, 1)->startOfDay();
            $end = Carbon::createFromDate((int)$year, (int)$mon, 1)->endOfMonth()->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $month = $start->format('Y-m');
        }

        $gyms = Gym::select('id','name')->orderBy('name')->get();
        $categories = ExpenseCategory::orderBy('name')->get(['id','name']);
        $coaches = \App\Models\User::role('coach')->orderBy('name')->get(['id','name']);

        $query = Expense::with(['category','subcategory','gym','coach'])->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()]);
        if ($gymId) { $query->where('gym_id', $gymId); }
        if ($coachId) { $query->where('coach_id', $coachId); }
        if ($request->filled('expense_category_id')) { $query->where('expense_category_id', $request->integer('expense_category_id')); }

        $expenses = $query->orderByDesc('expense_date')->paginate(20)->appends($request->query());

        $totalCents = (int) $query->clone()->sum('amount_cents');
        $byCategory = Expense::selectRaw('expense_category_id, SUM(amount_cents) as amount_cents')
            ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])
            ->when($gymId, function($q) use ($gymId){ $q->where('gym_id', $gymId); })
            ->groupBy('expense_category_id')
            ->get()
            ->map(function($row){
                $cat = ExpenseCategory::find($row->expense_category_id);
                return ['label' => $cat?->name ?? '—', 'amount_cents' => (int) $row->amount_cents];
            });

        return view('expenses.index', [
            'month' => $month,
            'gyms' => $gyms,
            'categories' => $categories,
            'coaches' => $coaches,
            'selectedGymId' => $gymId,
            'selectedCoachId' => $coachId,
            'expenses' => $expenses,
            'total_cents' => $totalCents,
            'by_category' => $byCategory,
        ]);
    }

    public function create()
    {
        $gyms = Gym::select('id','name')->orderBy('name')->get();
        $categories = ExpenseCategory::with('subcategories')->orderBy('name')->get();
        $subcategories = ExpenseSubcategory::with('category')->orderBy('name')->get();
        $coaches = \App\Models\User::role('coach')->orderBy('name')->get(['id','name']);
        $defaultDate = Carbon::now()->format('Y-m-d');
        return view('expenses.create', compact('gyms','categories','subcategories','coaches','defaultDate'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_subcategory_id' => 'nullable|exists:expense_subcategories,id',
            'gym_id' => 'nullable|exists:gyms,id',
            'coach_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'method' => 'required|string',
            'notes' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('expenses', 'public');
        }

        $amountCents = (int) round(((float)$data['amount']) * 100);
        Expense::create([
            'expense_category_id' => $data['expense_category_id'],
            'expense_subcategory_id' => $data['expense_subcategory_id'] ?? null,
            'gym_id' => $data['gym_id'] ?? null,
            'coach_id' => $data['coach_id'] ?? null,
            'amount_cents' => $amountCents,
            'expense_date' => $data['expense_date'],
            'method' => $data['method'],
            'notes' => $data['notes'] ?? null,
            'attachment_path' => $attachmentPath,
        ]);
        return redirect()->route('expenses.index')->with('success', __('messages.expense_created'));
    }

    public function show(Expense $expense)
    {
        $expense->load(['category','subcategory','gym','coach']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $gyms = Gym::select('id','name')->orderBy('name')->get();
        $categories = ExpenseCategory::orderBy('name')->get(['id','name']);
        $subcategories = ExpenseSubcategory::orderBy('name')->get(['id','name','expense_category_id']);
        $coaches = \App\Models\User::role('coach')->orderBy('name')->get(['id','name']);
        return view('expenses.edit', compact('expense','gyms','categories','subcategories','coaches'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_subcategory_id' => 'nullable|exists:expense_subcategories,id',
            'gym_id' => 'nullable|exists:gyms,id',
            'coach_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'method' => 'required|string',
            'notes' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($expense->attachment_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($expense->attachment_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->attachment_path);
            }
            $expense->attachment_path = $request->file('attachment')->store('expenses', 'public');
        }

        $expense->update([
            'expense_category_id' => $data['expense_category_id'],
            'expense_subcategory_id' => $data['expense_subcategory_id'] ?? null,
            'gym_id' => $data['gym_id'] ?? null,
            'coach_id' => $data['coach_id'] ?? null,
            'amount_cents' => (int) round(((float)$data['amount']) * 100),
            'expense_date' => $data['expense_date'],
            'method' => $data['method'],
            'notes' => $data['notes'] ?? null,
            // attachment_path is updated separately above if a new file is uploaded
        ]);
        
        // Save the attachment path change if we updated it manually
        if ($request->hasFile('attachment')) {
            $expense->save();
        }

        return redirect()->route('expenses.index')->with('success', __('messages.expense_updated'));
    }

    public function coachReport(Request $request, $coachId)
    {
        $coach = \App\Models\User::role('coach')->with(['city'])->findOrFail($coachId);
        $month = $request->string('month')->toString();
        if ($month) {
            [$year, $mon] = explode('-', $month);
            $start = Carbon::createFromDate((int)$year, (int)$mon, 1)->startOfDay();
            $end = Carbon::createFromDate((int)$year, (int)$mon, 1)->endOfMonth()->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $month = $start->format('Y-m');
        }

        $coachCourses = Course::query()
            ->whereHas('coaches', function ($q) use ($coach) {
                $q->where('users.id', $coach->id);
            })
            ->with(['gym'])
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();
        $presentCount = \App\Models\Attendance::where('user_id', $coach->id)
            ->whereBetween('attendance_at', [$start->toDateString(), $end->toDateString()])
            ->where('status','present')
            ->count();
        $absenceCount = \App\Models\Attendance::where('user_id', $coach->id)
            ->whereBetween('attendance_at', [$start->toDateString(), $end->toDateString()])
            ->where('status','absent')
            ->count();

        $coachExpenses = Expense::with(['category'])
            ->where('coach_id', $coach->id)
            ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])
            ->orderByDesc('expense_date')
            ->get();
        $salaryTotalCents = (int) $coachExpenses->sum('amount_cents');

        return view('expenses.coach_report', [
            'coach' => $coach,
            'month' => $month,
            'start' => $start,
            'end' => $end,
            'coachCourses' => $coachCourses,
            'presentCount' => $presentCount,
            'absenceCount' => $absenceCount,
            'coachExpenses' => $coachExpenses,
            'salaryTotalCents' => $salaryTotalCents,
        ]);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', __('messages.expense_deleted'));
    }
}
