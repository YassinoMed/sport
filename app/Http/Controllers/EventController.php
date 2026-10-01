<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Revenue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use ArPHP\I18N\Arabic;

class EventController extends Controller
{
    /**
     * List events
     */
    public function index()
    {
        $events = Event::withCount('registrations')
            ->orderBy('start_at', 'asc')
            ->paginate(20);

        return view('event.index', [
            'events' => $events,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('event.create');
    }

    /**
     * Store new event
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:0',
            'registration_fee' => 'required|numeric|min:0',
            'belt_requirement' => 'nullable|string|max:100',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            // 'is_public' handled as checkbox separately
        ]);

        $wcNames = $request->input('wc_name', []);
        $wcMins = $request->input('wc_min', []);
        $wcMaxs = $request->input('wc_max', []);
        $weightCategories = [];
        for ($i = 0; $i < count($wcNames); $i++) {
            $name = trim((string)($wcNames[$i] ?? ''));
            $min = $wcMins[$i] ?? null;
            $max = $wcMaxs[$i] ?? null;
            if ($name === '' && ($min === null || $max === null)) {
                continue;
            }
            if ($name === '' || !is_numeric($min) || !is_numeric($max)) {
                return back()->withErrors(['weight_categories' => __('messages.weight_category_invalid')])->withInput();
            }
            $min = (float)$min; $max = (float)$max;
            if ($min < 0 || $max <= 0 || $min >= $max || $max > 300) {
                return back()->withErrors(['weight_categories' => __('messages.weight_category_min_max_invalid')])->withInput();
            }
            $weightCategories[] = [
                'name' => $name,
                'min' => $min,
                'max' => $max,
            ];
        }

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'capacity' => (int) $request->capacity,
            // store fee in cents (TND)
            'registration_fee' => (int) round($request->registration_fee * 100),
            'belt_requirement' => $request->belt_requirement,
            'weight_categories' => $weightCategories ?: null,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'created_by' => Auth::id(),
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('events.show', $event->id)
            ->with('success', __('messages.event_created'));
    }

    /**
     * Show event details
     */
    public function show($id)
    {
        $event = Event::with(['registrations.user'])->withCount('registrations')->findOrFail($id);

        return view('event.show', [
            'event' => $event,
        ]);
    }

    /**
     * Registration form
     */
    public function register($id)
    {
        $event = Event::withCount('registrations')->findOrFail($id);
        $monthDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();

        $q = request('q');
        $birthFrom = request('birth_from');
        $birthTo = request('birth_to');
        $categoryId = request('category_id');
        $weightMin = request('weight_min');
        $weightMax = request('weight_max');
        $beltName = request('belt_name');

        $usersQuery = User::role('user')
            ->with([
                'payments' => function($qq) use ($monthDate){ $qq->where('billing_month', $monthDate); },
                'category',
                'belts' => function($qq){ $qq->orderByDesc('achieved_at'); },
            ]);
        if ($q) {
            $usersQuery->where(function($qq) use ($q){
                $qq->where('name','like',"%$q%");
            });
        }
        if ($birthFrom || $birthTo) {
            if ($birthFrom && $birthTo) {
                $usersQuery->whereBetween('birth_date', [$birthFrom, $birthTo]);
            } elseif ($birthFrom) {
                $usersQuery->where('birth_date', '>=', $birthFrom);
            } else {
                $usersQuery->where('birth_date', '<=', $birthTo);
            }
        }
        if ($categoryId) { $usersQuery->where('category_id', $categoryId); }
        if ($weightMin || $weightMax) {
            $usersQuery->where(function ($q) use ($weightMin, $weightMax) {
                $q->whereNull('weight_kg');
                if ($weightMin && $weightMax) {
                    $q->orWhereBetween('weight_kg', [(float) $weightMin, (float) $weightMax]);
                } elseif ($weightMin) {
                    $q->orWhere('weight_kg', '>=', (float) $weightMin);
                } else {
                    $q->orWhere('weight_kg', '<=', (float) $weightMax);
                }
            });
        }
        if ($beltName) {
            $usersQuery->whereHas('belts', function($qq) use ($beltName){ $qq->where('belt_name', $beltName); });
        }

        $users = $usersQuery->orderBy('name')->get(['id','name','birth_date','weight_kg','category_id']);

        $categories = Category::orderBy('name')->get(['id','name']);
        $beltNames = \App\Models\Belt::select('belt_name')->distinct()->orderBy('belt_name')->pluck('belt_name');

        return view('event.register', [
            'event' => $event,
            'users' => $users,
            'categories' => $categories,
            'beltNames' => $beltNames,
            'filters' => [
                'q' => $q,
                'birth_from' => $birthFrom,
                'birth_to' => $birthTo,
                'category_id' => $categoryId,
                'weight_min' => $weightMin,
                'weight_max' => $weightMax,
                'belt_name' => $beltName,
            ],
        ]);
    }

    /**
     * Store registration
     */
    public function storeRegistration(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Support both single user_id and multiple user_ids[]
        $userIds = $request->input('user_ids', []);
        if (empty($userIds) && $request->has('user_id')) {
            $userIds = [$request->input('user_id')];
        }

        if (empty($userIds)) {
            return back()->withErrors(['user_ids' => __('messages.select_at_least_one_athlete')]);
        }

        // Validate user_ids exist
        $existingUsers = User::whereIn('id', $userIds)->pluck('id')->toArray();
        if (count($existingUsers) !== count($userIds)) {
            return back()->withErrors(['user_ids' => __('messages.invalid_athletes_selected')]);
        }

        $notes = $request->input('notes');
        $registeredCount = 0;
        $skippedCount = 0;
        $capacityReached = false;

        foreach ($userIds as $userId) {
            // capacity check (0 means unlimited)
            $current = $event->registrations()->count();
            if ($event->capacity > 0 && $current >= $event->capacity) {
                $capacityReached = true;
                break;
            }

            // prevent duplicate registrations
            $exists = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->exists();
            if ($exists) {
                $skippedCount++;
                continue;
            }

            EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $userId,
                'paid_amount' => 0,
                'status' => 'confirmed',
                'notes' => $notes,
            ]);
            $registeredCount++;
        }

        // Build success message
        $capacityMsg = $capacityReached ? __('messages.capacity_reached') : '';
        return redirect()->route('events.show', $event->id)
            ->with('success', __('messages.event_registrations_summary', [
                'registered' => $registeredCount,
                'skipped' => $skippedCount,
                'capacity_msg' => $capacityMsg
            ]));
    }

    /**
     * Mark registration as paid and record revenue
     */
    public function markRegistrationPaid(Request $request, $id, $registration)
    {
        $event = Event::findOrFail($id);
        $registration = EventRegistration::where('id', $registration)
            ->where('event_id', $event->id)
            ->firstOrFail();

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'cash_register_id' => 'nullable|exists:cash_registers,id',
        ]);

        $amountInCents = (int) round($request->amount * 100);
        $registration->paid_amount = $amountInCents;
        $registration->status = 'confirmed';
        $registration->save();

        $paymentId = 'event_' . $event->id . '_' . $registration->user_id;
        $revenue = Revenue::where('payment_id', $paymentId)->first();

        if ($revenue) {
            $revenue->update([
                'price' => $amountInCents,
                'cash_register_id' => $request->cash_register_id,
            ]);
        } else {
            Revenue::create([
                'price' => $amountInCents,
                'payment_id' => $paymentId,
                'visa_number' => 'Inscription événement (TND)',
                'payment_method' => 'event_tnd',
                'user_id' => $registration->user_id,
                'training_package_id' => null,
                'cash_register_id' => $request->cash_register_id,
            ]);
        }

        return redirect()->route('events.show', $event->id)
            ->with('success', __('messages.payment_updated'));
    }

    /**
     * Export event attendance sheet as PDF with Arabic support
     */
    public function exportPdf($id)
    {
        try {
            $event = Event::with(['registrations.user.category'])->findOrFail($id);
            
            // Render the view to HTML
            $html = view('event.pdf', [
                'event' => $event,
                'registrations' => $event->registrations,
            ])->render();
            
            // Process Arabic text using ar-php library
            $arabic = new Arabic();
            $p = $arabic->arIdentify($html);
            
            for ($i = count($p)-1; $i >= 0; $i-=2) {
                $utf8ar = $arabic->utf8Glyphs(substr($html, $p[$i-1], $p[$i] - $p[$i-1]));
                $html = substr_replace($html, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
            }
            
            // Generate PDF with processed HTML
            $pdf = \PDF::loadHTML($html);
            $pdf->setPaper('a4', 'portrait');
            
            $filename = 'feuille_presence_' . \Str::slug($event->name) . '_' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', __('messages.pdf_error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Show live results for an event
     */
    public function liveResults($id)
    {
        $event = Event::with(['combats.athleteA', 'combats.athleteB', 'combats.winner'])->findOrFail($id);
        
        return view('event.live', [
            'event' => $event,
            'combats' => $event->combats()->orderBy('scheduled_at', 'asc')->get()
        ]);
    }
}
