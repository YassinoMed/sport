<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\TrainingSession;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;




class TrainingController extends Controller
{
    private function checkTrainingSessionAccess($sessionId)
    {
        $user = auth()->user();
        $session = TrainingSession::findOrFail($sessionId);

        if ($user->hasRole('admin')) {
            return $session;
        }

        if ($user->hasRole('cityManager')) {
            $exists = DB::table('training_packages')
                ->join('gyms_training_packages', 'gyms_training_packages.training_package_id', '=', 'training_packages.id')
                ->join('gyms', 'gyms.id', '=', 'gyms_training_packages.gym_id')
                ->where('training_packages.id', $session->training_package_id)
                ->where('gyms.city_id', $user->city_id)
                ->exists();
            if (!$exists) abort(403);
            return $session;
        }

        if ($user->hasRole('gymManager') || $user->hasRole('coach')) {
            if ($user->gym_id) {
                $exists = DB::table('training_packages')
                    ->join('gyms_training_packages', 'gyms_training_packages.training_package_id', '=', 'training_packages.id')
                    ->where('training_packages.id', $session->training_package_id)
                    ->where('gyms_training_packages.gym_id', $user->gym_id)
                    ->exists();
                if (!$exists) abort(403);
                return $session;
            }
        }

        abort(403);
    }

    #=======================================================================================#
    #			                             index                                         	#
    #=======================================================================================#
    public function index()
    {
        $user = auth()->user();
        $query = TrainingSession::orderByDesc('id');

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->whereExists(function ($q) use ($user) {
                $q->select(DB::raw(1))
                  ->from('training_packages')
                  ->join('gyms_training_packages', 'gyms_training_packages.training_package_id', '=', 'training_packages.id')
                  ->join('gyms', 'gyms.id', '=', 'gyms_training_packages.gym_id')
                  ->whereColumn('training_packages.id', 'training_sessions.training_package_id')
                  ->where('gyms.city_id', $user->city_id);
            });
        } elseif ($user->hasRole('gymManager') || $user->hasRole('coach')) {
            if ($user->gym_id) {
                $query->whereExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))
                      ->from('training_packages')
                      ->join('gyms_training_packages', 'gyms_training_packages.training_package_id', '=', 'training_packages.id')
                      ->whereColumn('training_packages.id', 'training_sessions.training_package_id')
                      ->where('gyms_training_packages.gym_id', $user->gym_id);
                });
            }
        }

        $trainingSessions = $query->paginate(20);
        if ($trainingSessions->count() <= 0) {
            return view('empty');
        }
        return view('TrainingSessions.listSessions', ['trainingSessions' => $trainingSessions]);
    }

    #=======================================================================================#
    #			                             create                                        	#
    #=======================================================================================#
    public function create()
    {
        $trainingSessions = TrainingSession::all();

        $users = User::all();

        foreach ($users as $user) {
            if ($user->hasRole('coach')) {
                $coaches[] = $user;
            }
        }
        return view('TrainingSessions.training_session', [
            'trainingSessions' => $trainingSessions,
            'coaches' => $coaches,
        ]);
    }
    #=======================================================================================#
    #			                             store                                         	#
    #=======================================================================================#
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'day' => ['required', 'date', 'after_or_equal:today'],
            'starts_at' => ['required'],
            'finishes_at' => ['required'],

        ]);



        $validate_old_seesions = TrainingSession::where('day', '=', $request->day)->where("starts_at", "!=", null)->where("finishes_at", "!=", null)->where(function ($q) use ($request) {
            $q->whereRaw("starts_at = '$request->starts_at' and finishes_at ='$request->finishes_at'")
                ->orwhereRaw("starts_at < '$request->starts_at' and finishes_at > '$request->finishes_at'")
                ->orwhereRaw("starts_at > '$request->starts_at' and starts_at < '$request->finishes_at'")
                ->orwhereRaw("finishes_at > '$request->starts_at' and finishes_at < '$request->finishes_at'")
                ->orwhereRaw("'$request->starts_at' > '$request->finishes_at'")
                ->orwhereRaw("'starts_at' > 'finishes_at'")
                ->orwhereRaw("starts_at > '$request->starts_at' and finishes_at < '$request->finishes_at'");
        })->get()->toArray();


        if (count($validate_old_seesions) > 0)
            return back()->withErrors(__('messages.check_time'))->withInput();
        $requestData = request()->all();
        $session = TrainingSession::create($requestData);
        $user_id = $request->input('user_id');
        $id = $session->id;
        $data = array('user_id' => $user_id, "training_session_id" => $id);
        DB::table('training_session_user')->insert($data);

        return redirect()->route('TrainingSessions.listSessions')->with('success', __('messages.session_created'));
    }

    public function planStore(Request $request)
    {
        $request->validate([
            'name' => ['required','string','min:2'],
            'user_id' => ['required','integer','exists:users,id'],
            'starts_at' => ['required'],
            'finishes_at' => ['required'],
            'start_date' => ['required','date','after_or_equal:today'],
            'end_date' => ['required','date','after_or_equal:start_date'],
            'pattern' => ['required','in:weekly,monthly'],
            'days_of_week' => ['array'],
            'days_of_month' => ['array'],
        ]);

        $name = $request->string('name');
        $coachId = $request->integer('user_id');
        $startsAt = $request->input('starts_at');
        $finishesAt = $request->input('finishes_at');
        $start = Carbon::parse($request->input('start_date'))->startOfDay();
        $end = Carbon::parse($request->input('end_date'))->endOfDay();
        $pattern = $request->input('pattern');
        $weekDays = collect($request->input('days_of_week', []))->map(function($v){ return (int)$v; })->all();
        $monthDays = collect($request->input('days_of_month', []))->map(function($v){ return (int)$v; })->all();

        $created = 0; $skipped = 0; $conflicts = 0;
        $cursor = $start->copy();
        while ($cursor->lessThanOrEqualTo($end)) {
            $shouldCreate = false;
            if ($pattern === 'weekly' && in_array((int)$cursor->dayOfWeekIso, $weekDays, true)) {
                $shouldCreate = true;
            }
            if ($pattern === 'monthly' && in_array((int)$cursor->day, $monthDays, true)) {
                $shouldCreate = true;
            }
            if ($shouldCreate) {
                $exists = TrainingSession::whereDate('day', $cursor->toDateString())
                    ->whereNotNull('starts_at')->whereNotNull('finishes_at')
                    ->where(function($q) use ($startsAt,$finishesAt){
                        $q->whereRaw("starts_at = ? and finishes_at = ?", [$startsAt,$finishesAt])
                          ->orWhereRaw("starts_at < ? and finishes_at > ?", [$startsAt,$finishesAt])
                          ->orWhereRaw("starts_at > ? and starts_at < ?", [$startsAt,$finishesAt])
                          ->orWhereRaw("finishes_at > ? and finishes_at < ?", [$startsAt,$finishesAt]);
                    })->exists();
                if ($exists) { $conflicts++; $cursor->addDay(); continue; }

                $session = TrainingSession::create([
                    'name' => $name,
                    'day' => $cursor->toDateString(),
                    'starts_at' => $startsAt,
                    'finishes_at' => $finishesAt,
                ]);
                DB::table('training_session_user')->insert([
                    'user_id' => $coachId,
                    'training_session_id' => $session->id,
                ]);
                $created++;
            }
            $cursor->addDay();
        }

        return redirect()->route('TrainingSessions.listSessions')
            ->with('success', __('messages.plan_created', ['created' => $created, 'conflicts' => $conflicts]));
    }
    #=======================================================================================#
    #			                             show                                         	#
    #=======================================================================================#
    public function show($id)
    {
        $trainingSession = $this->checkTrainingSessionAccess($id);
        $trainingSession->load('users');
        return view('TrainingSessions.show_training_session', [
            'trainingSession' => $trainingSession,
        ]);
    }
    #=======================================================================================#
    #			                             edit                                         	#
    #=======================================================================================#
    public function edit($id)
    {
        $trainingSession = $this->checkTrainingSessionAccess($id);
        $trainingSessions = TrainingSession::all();

        return view('TrainingSessions.edit_training_session', ['trainingSession' => $trainingSession, 'trainingSessions' => $trainingSessions]);
    }
    #=======================================================================================#
    #			                             update                                         #
    #=======================================================================================#
    public function update(Request $request, $id)
    {
        $trainingSession = $this->checkTrainingSessionAccess($id);
        $request->validate([
            'name' => ['required', 'string'],
            'day' => ['required', 'string'],
            'starts_at' => [
                'required'
            ],
            'finishes_at' => [
                'required'
            ],

        ]);

        $validate_old_seesions = TrainingSession::where('day', '=', $request->day)->where("starts_at", "!=", null)->where("finishes_at", "!=", null)->where(function ($q) use ($request) {
            $q->whereRaw("starts_at = ? and finishes_at = ?", [$request->starts_at, $request->finishes_at])
                ->orwhereRaw("starts_at < ? and finishes_at > ?", [$request->starts_at, $request->finishes_at])
                ->orwhereRaw("starts_at > ? and starts_at < ?", [$request->starts_at, $request->finishes_at])
                ->orwhereRaw("finishes_at > ? and finishes_at < ?", [$request->starts_at, $request->finishes_at])
                ->orwhereRaw("starts_at > ? and finishes_at < ?", [$request->starts_at, $request->finishes_at]);
        })->where('id', '!=', $id)->get()->toArray();

        if (count($validate_old_seesions) > 0)
            return back()->withErrors(__('messages.time_invalid'))->withInput();


        if (DB::table('training_session_user')->where('training_session_id', $id)->exists()) {
            return back()->withErrors(__('messages.session_has_users'))->withInput();
        }

        $trainingSession->update([
            'name' => $request->name,
            'day' => $request->day,
            'starts_at' => $request->starts_at,
            'finishes_at' => $request->finishes_at,
        ]);
        return redirect()->route('TrainingSessions.listSessions')->with('success', __('messages.session_updated'));
    }
    #=======================================================================================#
    #			                             destroy                                       	#
    #=======================================================================================#
    public function deleteSession($id)
    {
        $trainingSession = $this->checkTrainingSessionAccess($id);

        if (!DB::table('training_session_user')->where('training_session_id', $id)->exists()) {
            $trainingSession->delete();
            return response()->json([
                'success' => __('messages.session_deleted')
            ]);
        } else {
            return response()->json(['failed' => __('messages.session_has_users_delete')]);
        }
    }
}
