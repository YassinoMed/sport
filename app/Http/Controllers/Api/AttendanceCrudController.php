<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Attendance::all(),
            'message' => __('messages.attendance_retrieved')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_at' => ['required', 'date'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'training_session_id' => ['required', 'integer', 'exists:training_sessions,id'],
        ]);
        $attendance = Attendance::create($data);
        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => __('messages.attendance_created')
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => __('messages.attendance_retrieved')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $data = $request->validate([
            'attendance_at' => ['sometimes', 'required', 'date'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'training_session_id' => ['nullable', 'integer', 'exists:training_sessions,id'],
        ]);
        $attendance->update($data);
        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => __('messages.attendance_updated')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return response()->json([
            'success' => true,
            'message' => __('messages.attendance_deleted')
        ], 200);
    }
}
