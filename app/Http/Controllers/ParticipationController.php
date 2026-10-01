<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participation;
use App\Models\Course;
use App\Models\User;
use App\Models\Waitlist;
use Illuminate\Support\Facades\DB;

class ParticipationController extends Controller
{
    /**
     * Display a listing of participations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $courseId = request('course_id');
        $userId = request('user_id');

        $query = Participation::with(['user:id,name,email', 'course:id,name,description'])
            ->select(DB::raw('participations.*, users.name as user_name, users.email as user_email, courses.name as course_name, courses.description as course_description'))
            ->join('users', 'users.id', '=', 'participations.user_id')
            ->join('courses', 'courses.id', '=', 'participations.course_id');

        if ($courseId) {
            $query->where('participations.course_id', $courseId);
        }
        if ($userId) {
            $query->where('participations.user_id', $userId);
        }

        $participations = $query->orderBy('participations.created_at', 'desc')
            ->paginate(20);

        return view('participation.list', [
            'participations' => $participations,
            'filters' => [
                'course_id' => $courseId,
                'user_id' => $userId,
            ]
        ]);
    }

    /**
     * Show the form for creating a new participation.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $q = request('q');
        $cityId = request('city_id');
        $gymId = request('gym_id');
        $beltName = request('belt_name');
        $categoryId = request('category_id');
        $preselectedCourseId = request('course_id');

        $usersQuery = User::role('user')
            ->withoutBanned()
            ->with(['city','gym','belts']);
        if ($q) {
            $usersQuery->where(function($sub) use ($q){
                $sub->where('name','like',"%$q%")
                    ->orWhere('email','like',"%$q%");
            });
        }
        if ($cityId) { $usersQuery->where('city_id', $cityId); }
        if ($gymId) { $usersQuery->where('gym_id', $gymId); }
        if ($beltName) {
            $usersQuery->whereHas('belts', function($q2) use ($beltName){ $q2->where('belt_name', $beltName); });
        }
        if ($categoryId) { $usersQuery->where('category_id', $categoryId); }

        $users = $usersQuery->orderBy('name')->get(['id','name','email','city_id','gym_id']);
        $courses = Course::withCount('participations')->orderBy('name')->get();

        $cities = \App\Models\City::orderBy('name')->get(['id','name']);
        $gyms = \App\Models\Gym::orderBy('name')->get(['id','name']);
        $beltNames = \App\Models\Belt::select('belt_name')->distinct()->orderBy('belt_name')->pluck('belt_name');
        $categories = \App\Models\Category::orderBy('name')->get(['id','name']);
        
        return view('participation.create', [
            'users' => $users,
            'courses' => $courses,
            'cities' => $cities,
            'gyms' => $gyms,
            'beltNames' => $beltNames,
            'filters' => [
                'q' => $q,
                'city_id' => $cityId,
                'gym_id' => $gymId,
                'belt_name' => $beltName,
                'category_id' => $categoryId,
                'course_id' => $preselectedCourseId,
            ],
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created participation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['sometimes','integer','exists:users,id'],
            'user_ids' => ['sometimes','array'],
            'user_ids.*' => ['integer','exists:users,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'status' => ['nullable', 'in:registered,completed,pending,cancelled'],
            'participated_at' => ['nullable', 'date'],
        ]);

        $userIds = $request->user_ids ?: ($request->has('user_id') ? [$request->user_id] : []);
        if (empty($userIds)) {
            return redirect()->back()->withErrors(['error' => __('messages.select_at_least_one_user')]);
        }

        $course = Course::withCount('participations')->findOrFail($request->course_id);

        if ($course->capacity) {
            $newUsersCount = 0;
            $fullCapacityReached = false;
            foreach ($userIds as $uid) {
                $exists = Participation::where('user_id', $uid)
                    ->where('course_id', $request->course_id)
                    ->exists();
                if (!$exists) {
                    $newUsersCount++;
                }
            }

            if (($course->participations_count + $newUsersCount) > $course->capacity) {
                // Si l'utilisateur est seul, on peut lui proposer la liste d'attente
                if (count($userIds) === 1) {
                    $uid = $userIds[0];
                    $inWaitlist = Waitlist::where('user_id', $uid)->where('course_id', $course->id)->exists();
                    
                    if (!$inWaitlist) {
                        return redirect()->back()->with('error_with_waitlist', [
                            'message' => "La capacité maximale du cours ({$course->capacity}) est atteinte.",
                            'course_id' => $course->id,
                            'user_id' => $uid
                        ]);
                    }
                }

                return redirect()->back()->withErrors([
                    'error' => "La capacité maximale du cours ({$course->capacity}) a été atteinte. Places restantes : " . max(0, $course->capacity - $course->participations_count)
                ]);
            }
        }

        $created = 0; $skipped = 0;
        foreach ($userIds as $uid) {
            $exists = Participation::where('user_id', $uid)
                ->where('course_id', $request->course_id)
                ->exists();
            if ($exists) { $skipped++; continue; }

            Participation::create([
                'user_id' => $uid,
                'course_id' => $request->course_id,
                'status' => $request->status ?? 'registered',
                'participated_at' => $request->participated_at,
            ]);
            $created++;
        }

        return redirect()->route('participation.list')->with('success', __('messages.participations_created', ['created' => $created, 'skipped' => $skipped]));
    }

    public function addToWaitlist(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $exists = Waitlist::where('user_id', $request->user_id)
            ->where('course_id', $request->course_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Vous êtes déjà en liste d\'attente pour ce cours.');
        }

        Waitlist::create([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'status' => 'pending',
            'priority' => Waitlist::where('course_id', $request->course_id)->count() + 1
        ]);

        return redirect()->back()->with('success', 'Vous avez été ajouté à la liste d\'attente.');
    }

    /**
     * Display the specified participation.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $participation = Participation::with(['user', 'course'])->findOrFail($id);
        
        return view('participation.show', [
            'participation' => $participation,
        ]);
    }

    /**
     * Show the form for editing the specified participation.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $participation = Participation::findOrFail($id);
        $users = User::all();
        $courses = Course::all();
        
        return view('participation.edit', [
            'participation' => $participation,
            'users' => $users,
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified participation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $participation = Participation::findOrFail($id);
        
        $request->validate([
            'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'course_id' => ['sometimes', 'required', 'integer', 'exists:courses,id'],
            'status' => ['nullable', 'in:registered,attended,absent'],
            'participated_at' => ['nullable', 'date'],
        ]);

        // Vérifier les doublons si user_id ou course_id sont modifiés
        if ($request->has('user_id') || $request->has('course_id')) {
            $userId = $request->user_id ?? $participation->user_id;
            $courseId = $request->course_id ?? $participation->course_id;
            
            $existingParticipation = Participation::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('id', '!=', $id)
                ->first();
                
            if ($existingParticipation) {
                return redirect()->back()->withErrors(['error' => __('messages.participation_exists')]);
            }
        }

        $participation->update($request->only(['user_id', 'course_id', 'status', 'participated_at']));

        return redirect()->route('participation.list')->with('success', __('messages.participation_updated'));
    }

    /**
     * Remove the specified participation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $participation = Participation::findOrFail($id);
        $participation->delete();

        return redirect()->route('participation.list')->with('success', __('messages.participation_deleted'));
    }
}
