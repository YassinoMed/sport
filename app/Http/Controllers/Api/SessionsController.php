<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingSession;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class SessionsController extends Controller
{
    public function index()
    {
        $sessions = TrainingSession::all();
        return response()->json([
            'success' => true,
            'data' => $sessions,
            'message' => __('messages.sessions_retrieved')
        ]);
    }
    public function showSession($sessionId)
    {
        $session = TrainingSession::find($sessionId);
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => __('messages.no_sessions_found')
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $session->name,
                'day' => $session->day,
                'starts_at' => $session->starts_at,
                'finishes_at' => $session->finishes_at
            ],
            'message' => __('messages.session_retrieved')
        ]);
    }

    public function remaining_training_sessions()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_sessions' => Auth()->user()->total_sessions,
                'remain_session' => Auth()->user()->remain_session
            ],
            'message' => __('messages.remaining_sessions_retrieved')
        ]);
    }

    public function attend_training_session($sessionId, Request $request)
    {
        $user_id = Auth()->user()->id;
        $session = TrainingSession::find($sessionId);
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => __('messages.no_sessions_found')
            ], 404);
        }
        $user = user::find($user_id);
        $remain_session = Auth()->user()->remain_session;
        $attendTime = Carbon::parse($request->attendance_at)->format('Y-m-d');

        if ($attendTime !== $session->day) {
            return response()->json([
                'success' => false,
                'message' => __('messages.session_date_invalid')
            ], 400);
        }

        if ($remain_session >= 1) {
            $request->validate([
                'attendance_at' => 'required',
            ]);

            $attend = new Attendance([
                'attendance_at' => $request->attendance_at,
                'user_id' => $user_id,
                'training_session_id' => $sessionId
            ]);
            $attend->save();
            $remain_session--;
            $user->update(['remain_session' => $remain_session]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'session' => $session,
                ],
                'message' => __('messages.session_attended')
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('messages.no_remaining_sessions')
            ], 400);
        }
    }

    public function getSessionsForUser(Request $request)
    {
        $user = Auth()->user();
        $history_attendances = Attendance::select(DB::raw('training_sessions.name as training_session_name,gyms.name as gym_name,Date(attendances.attendance_at
        ) as attendance_date,Time(attendances.attendance_at) as attendance_time'))
            ->join('users', 'users.id', '=', 'attendances.user_id')->join('training_sessions', 'training_sessions.id', '=', 'attendances.training_session_id')
            ->join('gyms', "gyms.id", '=', 'users.gym_id')
            ->where('attendances.user_id', '=', $user->id)
            ->where('users.id', '=', $user->id)

            ->get();
        return response()->json(
            $history_attendances
        );
    }
}
