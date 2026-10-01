<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserPaymentController extends Controller
{
    public function index($userId)
    {
        $user = User::findOrFail($userId);
        $payments = UserPayment::where('user_id', $user->id)
            ->orderByDesc('billing_month')
            ->paginate(12);

        $allPayments = UserPayment::where('user_id', $user->id)->orderBy('billing_month')->get(['billing_month','status']);
        $statuses = [];
        if ($allPayments->count() > 0) {
            $start = \Illuminate\Support\Carbon::parse($allPayments->first()->billing_month)->startOfMonth();
            $end = \Illuminate\Support\Carbon::parse($allPayments->last()->billing_month)->startOfMonth();
            $cursor = $start->copy();
            $paidMap = [];
            foreach ($allPayments as $p) {
                $paidMap[\Illuminate\Support\Carbon::parse($p->billing_month)->format('Y-m')] = $p->status;
            }
            while ($cursor->lte($end)) {
                $key = $cursor->format('Y-m');
                $statuses[] = [
                    'month' => $key,
                    'status' => isset($paidMap[$key]) ? $paidMap[$key] : 'due',
                ];
                $cursor->addMonth();
            }
        }

        return view('user_payments.index', compact('user','payments','statuses'));
    }

    public function create($userId)
    {
        $user = User::findOrFail($userId);
        $defaultMonth = Carbon::now()->startOfMonth()->format('Y-m');
        $packages = \App\Models\TrainingPackage::orderBy('name')->get();
        
        $query = \App\Models\CashRegister::where('status', 'active');
        
        // Filter based on logged-in user's role
        if (auth()->user()->hasRole('gymManager')) {
            $query->where('gym_id', auth()->user()->gym_id);
        } elseif (auth()->user()->hasRole('cityManager')) {
            $gymIds = \App\Models\Gym::where('city_id', auth()->user()->city_id)->pluck('id');
            $query->whereIn('gym_id', $gymIds);
        } elseif ($user->gym_id) {
            // For admin, we could filter by user's gym but it's better to show all
            // or at least prioritize the user's gym. 
            // Let's keep it simple: if admin, show all active registers.
            // But if we want to be helpful, we can filter by user gym if provided.
            // $query->where('gym_id', $user->gym_id);
        }

        $cashRegisters = $query->orderBy('name')->get();

        // Fallback: if empty, show all active registers (especially for admin)
        if ($cashRegisters->isEmpty()) {
            $cashRegisters = \App\Models\CashRegister::where('status', 'active')->orderBy('name')->get();
        }

        return view('user_payments.create', compact('user','defaultMonth', 'packages', 'cashRegisters'));
    }

    public function store(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'billing_month' => 'required|date_format:Y-m',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'package_id' => 'nullable|exists:training_packages,id',
            'cash_register_id' => 'nullable|exists:cash_registers,id',
            'method' => 'required|string',
            'status' => 'required|in:paid,due,refund',
            'paid_at' => 'nullable',
            'notes' => 'nullable|string|max:255',
        ]);

        $billingMonthDate = Carbon::createFromFormat('Y-m', $validated['billing_month'])->startOfMonth();
        $amountCents = (int) round(((float) $validated['amount']) * 100);

        // Auto-generate invoice number if paid
        $invoiceNumber = null;
        if ($validated['status'] === 'paid') {
            $invoiceNumber = 'INV-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        }

        UserPayment::updateOrCreate(
            ['user_id' => $user->id, 'billing_month' => $billingMonthDate->toDateString()],
            [
                'package_id' => $validated['package_id'] ?? null,
                'cash_register_id' => $validated['cash_register_id'] ?? null,
                'invoice_number' => $invoiceNumber,
                'amount_cents' => $amountCents,
                'start_date' => $validated['start_date'] ?? $billingMonthDate->copy()->startOfMonth(),
                'end_date' => $validated['end_date'] ?? $billingMonthDate->copy()->endOfMonth(),
                'status' => $validated['status'],
                'method' => $validated['method'],
                'paid_at' => $validated['paid_at'] ? Carbon::parse($validated['paid_at']) : now(),
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return redirect()->route('user-payments.index', $user->id)->with('success', __('messages.payment_created'));
    }

    public function edit($paymentId)
    {
        $payment = UserPayment::with('user')->findOrFail($paymentId);
        $user = $payment->user;
        $billing_month = Carbon::parse($payment->billing_month)->format('Y-m');
        $packages = \App\Models\TrainingPackage::orderBy('name')->get();
        
        $query = \App\Models\CashRegister::where('status', 'active');
        
        if (auth()->user()->hasRole('gymManager')) {
            $query->where('gym_id', auth()->user()->gym_id);
        } elseif (auth()->user()->hasRole('cityManager')) {
            $gymIds = \App\Models\Gym::where('city_id', auth()->user()->city_id)->pluck('id');
            $query->whereIn('gym_id', $gymIds);
        }

        $cashRegisters = $query->orderBy('name')->get();

        if ($cashRegisters->isEmpty()) {
            $cashRegisters = \App\Models\CashRegister::where('status', 'active')->orderBy('name')->get();
        }
        
        return view('user_payments.edit', compact('payment', 'user', 'billing_month', 'packages', 'cashRegisters'));
    }

    public function update(Request $request, $paymentId)
    {
        $payment = UserPayment::findOrFail($paymentId);
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'billing_month' => 'required|date_format:Y-m',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'package_id' => 'nullable|exists:training_packages,id',
            'cash_register_id' => 'nullable|exists:cash_registers,id',
            'method' => 'required|string',
            'status' => 'required|in:paid,due,refund',
            'paid_at' => 'nullable',
            'notes' => 'nullable|string|max:255',
        ]);

        $billingMonthDate = Carbon::createFromFormat('Y-m', $validated['billing_month'])->startOfMonth();
        $amountCents = (int) round(((float) $validated['amount']) * 100);

        // Generate invoice number if it becomes paid and doesn't have one
        $invoiceNumber = $payment->invoice_number;
        if ($validated['status'] === 'paid' && !$invoiceNumber) {
            $invoiceNumber = 'INV-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        }

        $payment->update([
            'billing_month' => $billingMonthDate->toDateString(),
            'start_date' => $validated['start_date'] ?? $payment->start_date,
            'end_date' => $validated['end_date'] ?? $payment->end_date,
            'package_id' => $validated['package_id'] ?? $payment->package_id,
            'cash_register_id' => $validated['cash_register_id'] ?? $payment->cash_register_id,
            'invoice_number' => $invoiceNumber,
            'amount_cents' => $amountCents,
            'status' => $validated['status'],
            'method' => $validated['method'],
            'paid_at' => $validated['paid_at'] ? Carbon::parse($validated['paid_at']) : $payment->paid_at,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('user-payments.index', $payment->user_id)->with('success', __('messages.payment_updated'));
    }

    public function downloadInvoice($paymentId)
    {
        $payment = UserPayment::with(['user', 'trainingPackage'])->findOrFail($paymentId);
        
        if ($payment->status !== 'paid') {
            return back()->with('error', __('messages.invoice_only_paid'));
        }

        if (!$payment->invoice_number) {
            $payment->update([
                'invoice_number' => 'INV-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)))
            ]);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user_payments.invoice', compact('payment'));
        return $pdf->download($payment->invoice_number . '.pdf');
    }

    public function destroy($paymentId)
    {
        $payment = UserPayment::findOrFail($paymentId);
        $userId = $payment->user_id;
        $payment->delete();

        return redirect()->route('user-payments.index', $userId)->with('success', __('messages.payment_deleted'));
    }
}
