<?php

namespace App\Http\Controllers;

use App\Models\Belt;
use App\Models\BeltNoteCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BeltController extends Controller
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
                    'level' => $i
                ]);
                $opts[$value] = $label;
            }
        }
        foreach (range(1, 6) as $i) {
            $value = "Noire $i";
            $label = __('messages.belt_black_label', [
                'level' => $i
            ]);
            $opts[$value] = $label;
        }
        return $opts;
    }
    public function list(Request $request)
    {
        $query = Belt::latest()->with('user');

        if ($request->filled('belt_name')) {
            $query->where('belt_name', $request->get('belt_name'));
        }

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->whereHas('user', function($sq) use ($q) {
                $sq->where('name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            });
        }

        $belts = $query->paginate(10)->appends($request->query());
        $beltOptions = $this->beltOptions();
        
        return view('belt.list', compact('belts', 'beltOptions'));
    }

    public function create()
    {
        $users = User::role('user')
            ->with(['belts' => function($q){ $q->orderByDesc('achieved_at'); }])
            ->orderBy('name')
            ->get(['id','name', 'gym_id']);

        $gymIds = $users->pluck('gym_id')->unique()->filter();
        $cashRegisters = \App\Models\CashRegister::whereIn('gym_id', $gymIds)->get();

        $beltOptions = $this->beltOptions();
        return view('belt.create', compact('users','beltOptions', 'cashRegisters'));
    }

    public function store(Request $request)
    {
        $options = array_keys($this->beltOptions());
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'belt_name' => ['required','string', Rule::in($options)],
            'achieved_at' => 'required|date|before_or_equal:today',
            'exam_at' => 'nullable|date|before_or_equal:achieved_at',
            'price' => 'nullable|numeric|min:0',
            'cash_register_id' => 'nullable|exists:cash_registers,id'
        ], [
            'user_id.required' => __('messages.user_required'),
            'user_id.exists' => __('messages.user_exists'),
            'belt_name.required' => __('messages.belt_required'),
            'belt_name.in' => __('messages.belt_invalid'),
            'achieved_at.required' => __('messages.achieved_at_required'),
            'achieved_at.date' => __('messages.date_invalid'),
            'achieved_at.before_or_equal' => __('messages.date_future'),
            'exam_at.date' => __('messages.exam_date_invalid'),
            'exam_at.before_or_equal' => __('messages.exam_date_before'),
            'price.numeric' => __('messages.price_numeric'),
            'price.min' => __('messages.price_min')
        ]);

        $priceCents = $request->filled('price') ? (int) round(((float) $request->price) * 100) : null;

        Belt::create([
            'user_id' => $request->user_id,
            'belt_name' => $request->belt_name,
            'achieved_at' => $request->achieved_at,
            'exam_at' => $request->exam_at,
            'price_cents' => $priceCents,
            'cash_register_id' => $request->cash_register_id,
        ]);

        return redirect()->route('belt.list')->with('success', __('messages.belt_added'));
    }

    public function show(Belt $belt)
    {
        $belt->load('user');

        $categories = BeltNoteCategory::query()
            ->where('belt_name', $belt->belt_name)
            ->with(['notes', 'subcategories.notes', 'subcategories.childrenRecursive'])
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('belt.show', compact('belt', 'categories'));
    }

    public function edit(Belt $belt)
    {
        $users = User::role('user')->orderBy('name')->get(['id','name']);
        return view('belt.edit', compact('belt','users'));
    }

    public function update(Request $request, Belt $belt)
    {
        $options = array_keys($this->beltOptions());
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'belt_name' => ['required','string', Rule::in($options)],
            'achieved_at' => 'required|date|before_or_equal:today',
            'exam_at' => 'nullable|date|before_or_equal:achieved_at',
            'price' => 'nullable|numeric|min:0'
        ]);
        $priceCents = $request->filled('price') ? (int) round(((float) $request->price) * 100) : null;
        $belt->update([
            'user_id' => $request->user_id,
            'belt_name' => $request->belt_name,
            'achieved_at' => $request->achieved_at,
            'exam_at' => $request->exam_at,
            'price_cents' => $priceCents,
        ]);
        return redirect()->route('belt.list')->with('success', __('messages.belt_updated'));
    }

    public function destroy(Belt $belt)
    {
        $belt->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => 1]);
        }
        return redirect()->route('belt.list')->with('success', __('messages.belt_deleted'));
    }
    public function grading()
    {
        $users = User::role('user')
            ->with(['belts' => function($q){ $q->orderByDesc('achieved_at'); }])
            ->orderBy('name')
            ->get(['id','name', 'gym_id']);

        $gymIds = $users->pluck('gym_id')->unique()->filter();
        $cashRegisters = \App\Models\CashRegister::whereIn('gym_id', $gymIds)->get();

        $beltOptions = $this->beltOptions();
        return view('belt.grading', compact('users','beltOptions', 'cashRegisters'));
    }

    public function storeGrading(Request $request)
    {
        $options = array_keys($this->beltOptions());
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'belt_name' => ['required','string', Rule::in($options)],
            'achieved_at' => 'required|date|before_or_equal:today',
            'exam_at' => 'nullable|date|before_or_equal:achieved_at',
            'price' => 'nullable|numeric|min:0',
            'cash_register_id' => 'nullable|exists:cash_registers,id'
        ], [
            'user_ids.required' => __('messages.user_ids_required'),
            'belt_name.required' => __('messages.belt_required'),
            'achieved_at.required' => __('messages.achieved_at_required'),
        ]);

        $priceCents = $request->filled('price') ? (int) round(((float) $request->price) * 100) : null;

        foreach ($request->user_ids as $userId) {
            Belt::create([
                'user_id' => $userId,
                'belt_name' => $request->belt_name,
                'achieved_at' => $request->achieved_at,
                'exam_at' => $request->exam_at,
                'price_cents' => $priceCents,
                'cash_register_id' => $request->cash_register_id,
            ]);
        }

        return redirect()->route('belt.list')->with('success', __('messages.grade_recorded_count', ['count' => count($request->user_ids)]));
    }
}
