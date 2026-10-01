<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryAttendanceController extends Controller
{
    /**
     * Afficher la liste des catégories avec statistiques
     */
    public function index()
    {
        $categories = Category::withCount(['users' => function($query) {
            $query->role('user')->withoutBanned();
        }])
        ->orderBy('name')
        ->get()
        ->map(function($category) {
            // Statistiques de présence pour cette catégorie
            $stats = CategoryAttendance::where('category_id', $category->id)
                ->selectRaw("
                    COUNT(CASE WHEN status = 'present' THEN 1 END) as presents,
                    COUNT(CASE WHEN status = 'absent' THEN 1 END) as absents,
                    COUNT(CASE WHEN status = 'excused' THEN 1 END) as excused,
                    MAX(attendance_date) as last_attendance_date
                ")
                ->first();
            
            $category->presents = $stats->presents ?? 0;
            $category->absents = $stats->absents ?? 0;
            $category->excused = $stats->excused ?? 0;
            $category->last_attendance_date = $stats->last_attendance_date;
            $category->total_users = $category->users_count;
            
            return $category;
        });

        return view('category-attendance.index', compact('categories'));
    }

    /**
     * Afficher le formulaire pour marquer les présences d'une catégorie
     */
    public function create($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $monthDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        
        $users = User::role('user')
            ->withoutBanned()
            ->where('category_id', $categoryId)
            ->with(['categoryAttendances' => function($q){
                $q->orderByDesc('attendance_date')->limit(5);
            }]);

        if (\Illuminate\Support\Facades\Schema::hasTable('user_payments')) {
            $users->with(['payments' => function ($q) use ($monthDate) {
                $q->where('billing_month', $monthDate);
            }]);
        }

        $users = $users->orderBy('name')->get();

        return view('category-attendance.create', compact('category', 'users'));
    }

    /**
     * Enregistrer les présences pour une catégorie
     */
    public function store(Request $request, $categoryId)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.user_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        $category = Category::findOrFail($categoryId);
        $date = $request->attendance_date;

        DB::transaction(function() use ($request, $categoryId, $date) {
            foreach ($request->attendances as $attendance) {
                CategoryAttendance::updateOrCreate(
                    [
                        'user_id' => $attendance['user_id'],
                        'category_id' => $categoryId,
                        'attendance_date' => $date,
                    ],
                    [
                        'status' => $attendance['status'],
                        'notes' => $attendance['notes'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('category-attendance.show', [$categoryId, $date])
            ->with('success', __('messages.attendance_recorded'));
    }

    /**
     * Afficher les présences pour une catégorie à une date donnée
     */
    public function show($categoryId, $date)
    {
        $category = Category::findOrFail($categoryId);
        
        $attendances = CategoryAttendance::with('user')
            ->where('category_id', $categoryId)
            ->where('attendance_date', $date)
            ->get();

        if ($attendances->isEmpty()) {
            return redirect()->route('category-attendance.create', $categoryId)
                ->with('info', __('messages.no_attendance_recorded'));
        }

        return view('category-attendance.show', compact('category', 'attendances', 'date'));
    }

    /**
     * Afficher le formulaire d'édition des présences
     */
    public function edit($categoryId, $date)
    {
        $category = Category::findOrFail($categoryId);
        
        $users = User::role('user')
            ->withoutBanned()
            ->where('category_id', $categoryId)
            ->with(['categoryAttendances' => function($q) use ($date) {
                $q->where('attendance_date', $date);
            }])
            ->orderBy('name')
            ->get();

        return view('category-attendance.edit', compact('category', 'users', 'date'));
    }

    /**
     * Mettre à jour les présences
     */
    public function update(Request $request, $categoryId, $date)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.user_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function() use ($request, $categoryId, $date) {
            foreach ($request->attendances as $attendance) {
                CategoryAttendance::updateOrCreate(
                    [
                        'user_id' => $attendance['user_id'],
                        'category_id' => $categoryId,
                        'attendance_date' => $date,
                    ],
                    [
                        'status' => $attendance['status'],
                        'notes' => $attendance['notes'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('category-attendance.show', [$categoryId, $date])
            ->with('success', __('messages.attendance_updated'));
    }

    /**
     * Supprimer les présences pour une date donnée
     */
    public function destroy($categoryId, $date)
    {
        CategoryAttendance::where('category_id', $categoryId)
            ->where('attendance_date', $date)
            ->delete();

        return redirect()->route('category-attendance.index')
            ->with('success', __('messages.attendance_deleted'));
    }

    /**
     * Afficher les statistiques pour une catégorie
     */
    public function statistics($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $stats = DB::table('category_attendances')
            ->join('users', 'category_attendances.user_id', '=', 'users.id')
            ->where('category_attendances.category_id', $categoryId)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(CASE WHEN category_attendances.status = "present" THEN 1 END) as presents'),
                DB::raw('COUNT(CASE WHEN category_attendances.status = "absent" THEN 1 END) as absents'),
                DB::raw('COUNT(CASE WHEN category_attendances.status = "excused" THEN 1 END) as excused'),
                DB::raw('COUNT(*) as total_sessions')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->get();

        return view('category-attendance.statistics', compact('category', 'stats'));
    }

    public function ajaxUpdate(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent,excused',
        ]);

        CategoryAttendance::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id'],
                'attendance_date' => $data['attendance_date'],
            ],
            [
                'status' => $data['status'],
            ]
        );

        return response()->json(['success' => true, 'message' => __('messages.attendance_updated')]);
    }
}
