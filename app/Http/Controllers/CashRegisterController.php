<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Gym;

class CashRegisterController extends Controller
{
    public function index()
    {
        $cashRegisters = CashRegister::with('gym')
            ->withSum(['userPayments as payments_sum' => function($query) {
                $query->where('status', 'paid');
            }], 'amount_cents')
            ->withSum('revenues as revenues_sum', 'price')
            ->paginate(10);

        $gymIds = $cashRegisters->pluck('gym_id')->filter()->unique()->values();
        $expensesByGym = $gymIds->isEmpty()
            ? collect()
            : Expense::query()
                ->whereIn('gym_id', $gymIds)
                ->selectRaw('gym_id, SUM(amount_cents) as total_cents')
                ->groupBy('gym_id')
                ->pluck('total_cents', 'gym_id');

        foreach ($cashRegisters as $register) {
            $expenseCents = (int) ($expensesByGym[$register->gym_id] ?? 0);
            $register->balance = ((int)$register->payments_sum + (int)$register->revenues_sum - $expenseCents) / 100;
        }

        return view('cash_registers.index', compact('cashRegisters'));
    }

    public function create()
    {
        $gyms = Gym::all();
        return view('cash_registers.create', compact('gyms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gym_id' => 'required|exists:gyms,id',
            'status' => 'required|in:active,inactive',
        ]);

        CashRegister::create($validated);

        return redirect()->route('cash-registers.index')->with('success', __('messages.cash_register_created'));
    }

    public function edit($id)
    {
        $cashRegister = CashRegister::findOrFail($id);
        $gyms = Gym::all();
        return view('cash_registers.edit', compact('cashRegister', 'gyms'));
    }

    public function update(Request $request, $id)
    {
        $cashRegister = CashRegister::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gym_id' => 'required|exists:gyms,id',
            'status' => 'required|in:active,inactive',
        ]);

        $cashRegister->update($validated);

        return redirect()->route('cash-registers.index')->with('success', __('messages.cash_register_updated'));
    }

    public function destroy($id)
    {
        $cashRegister = CashRegister::findOrFail($id);
        $cashRegister->delete();

        return redirect()->route('cash-registers.index')->with('success', __('messages.cash_register_deleted'));
    }

    public function getByGym($gymId)
    {
        $registers = CashRegister::where('gym_id', $gymId)
            ->where('status', 'active')
            ->get(['id', 'name']);
            
        return response()->json($registers);
    }
}
