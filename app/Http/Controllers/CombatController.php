<?php

namespace App\Http\Controllers;

use App\Models\Combat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CombatController extends Controller
{
    public function index()
    {
        $combats = Combat::query()
            ->with(['athleteA:id,name,weight_kg', 'athleteB:id,name,weight_kg', 'creator:id,name'])
            ->orderByDesc('scheduled_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('combats.index', compact('combats'));
    }

    public function create(Request $request)
    {
        $weights = User::query()
            ->withoutBanned()
            ->role('user')
            ->whereNotNull('weight_kg')
            ->select('weight_kg')
            ->distinct()
            ->orderBy('weight_kg')
            ->pluck('weight_kg');

        $selectedWeight = $request->query('weight_kg');

        $athletes = collect();
        if ($selectedWeight !== null && $selectedWeight !== '') {
            $athletes = User::query()
                ->withoutBanned()
                ->role('user')
                ->where('weight_kg', $selectedWeight)
                ->orderBy('name')
                ->get(['id', 'name', 'weight_kg']);
        }

        return view('combats.create', compact('athletes', 'weights', 'selectedWeight'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'athlete_a_id' => ['required', 'integer', 'exists:users,id'],
            'athlete_b_id' => ['required', 'integer', 'exists:users,id', 'different:athlete_a_id'],
            'scheduled_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $athleteA = User::query()
            ->withoutBanned()
            ->role('user')
            ->find($validated['athlete_a_id']);
        $athleteB = User::query()
            ->withoutBanned()
            ->role('user')
            ->find($validated['athlete_b_id']);

        if (!$athleteA || !$athleteB) {
            return back()->withInput()->withErrors(['athlete_a_id' => __('messages.athlete_not_found')]);
        }

        if ($athleteA->weight_kg === null || $athleteB->weight_kg === null) {
            return back()->withInput()->withErrors(['athlete_a_id' => __('messages.weight_required')]);
        }

        $weightA = number_format((float) $athleteA->weight_kg, 2, '.', '');
        $weightB = number_format((float) $athleteB->weight_kg, 2, '.', '');

        if ($weightA !== $weightB) {
            return back()->withInput()->withErrors(['athlete_b_id' => __('messages.weights_must_match')]);
        }

        $scheduledAt = null;
        if (!empty($validated['scheduled_at'])) {
            $scheduledAt = Carbon::parse($validated['scheduled_at']);
        }

        Combat::create([
            'athlete_a_id' => (int) $athleteA->id,
            'athlete_b_id' => (int) $athleteB->id,
            'weight_kg' => $weightA,
            'scheduled_at' => $scheduledAt,
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('combats.index')->with('success', __('messages.combat_created'));
    }
}
