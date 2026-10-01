<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Group;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groupId = $request->get('group_id');

        $month = (string) $request->get('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        $query = Schedule::query()
            ->select(['id', 'group_id', 'day', 'start_time', 'end_time', 'location', 'notes'])
            ->with(['group:id,name']);

        if (Schema::hasColumn('schedules', 'course_id')) {
            $query->whereNull('course_id');
        }

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        $schedules = $query->orderBy('day')->orderBy('start_time')->get();
        $groups = Group::query()->orderBy('name')->get(['id', 'name']);

        $groupedSchedules = $schedules->groupBy('group_id');
        $groupedSchedulesByDay = $groupedSchedules->map(function ($groupSchedules) {
            return $groupSchedules
                ->groupBy('day')
                ->map(fn ($items) => $items->sortBy('start_time')->values());
        });

        $monthOccurrencesByGroup = [];
        foreach ($groupedSchedulesByDay as $gId => $byDay) {
            $occ = [];
            $cursor = $monthStart->copy();
            while ($cursor->lessThanOrEqualTo($monthEnd)) {
                $dayName = $days[$cursor->dayOfWeekIso - 1] ?? null;
                if ($dayName !== null) {
                    $daySchedules = $byDay->get($dayName, collect());
                    foreach ($daySchedules as $sch) {
                        $occ[] = [
                            'date' => $cursor->toDateString(),
                            'day' => $dayName,
                            'start_time' => $sch->start_time,
                            'end_time' => $sch->end_time,
                            'location' => $sch->location,
                            'notes' => $sch->notes,
                            'schedule_id' => $sch->id,
                        ];
                    }
                }
                $cursor->addDay();
            }
            $monthOccurrencesByGroup[$gId] = $occ;
        }

        return view('schedules.index', [
            'schedules' => $schedules,
            'groups' => $groups,
            'groupedSchedules' => $groupedSchedules,
            'groupId' => $groupId,
            'month' => $month,
            'monthOccurrencesByGroup' => $monthOccurrencesByGroup,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::all();
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        return view('schedules.create', compact('groups', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Schedule::create($request->all());

        return redirect()->route('schedules.index', ['group_id' => $request->group_id])
            ->with('success', __('messages.schedule_added'));
    }

    public function edit(Schedule $schedule)
    {
        $groups = Group::all();
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        return view('schedules.edit', compact('schedule', 'groups', 'days'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $schedule->update($request->all());

        return redirect()->route('schedules.index', ['group_id' => $schedule->group_id])
            ->with('success', __('messages.schedule_updated'));
    }

    public function destroy(Schedule $schedule)
    {
        $groupId = $schedule->group_id;
        $schedule->delete();
        
        return redirect()->route('schedules.index', ['group_id' => $groupId])
            ->with('success', __('messages.schedule_deleted'));
    }
}
