<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%$search%")
                  ->orWhere('action', 'like', "%$search%")
                  ->orWhere('ip_address', 'like', "%$search%")
                  ->orWhere('url', 'like', "%$search%");
            });
        }

        $logs = $query->paginate(50);

        return view('activity_logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = ActivityLog::findOrFail($id);
        return view('activity_logs.show', compact('log'));
    }
}
