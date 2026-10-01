<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * List all groups with member count.
     */
    public function index()
    {
        $groups = Group::withCount('users')->orderBy('name')->paginate(15);
        return view('group.index', compact('groups'));
    }

    /**
     * Show the create group form.
     */
    public function create()
    {
        $users = User::role('user')->orderBy('name')->get(['id', 'name']);
        return view('group.create', compact('users'));
    }

    /**
     * Store a new group with optional members.
     * Allows many-to-many: an athlete can belong to multiple groups.
     * Prevents duplicates via sync (no duplicate pivot rows).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string|max:1000',
            'user_ids'    => 'nullable|array',
            'user_ids.*'  => 'integer|exists:users,id',
        ]);

        $group = Group::create($request->only('name', 'description'));

        $userIds = $request->input('user_ids', []);
        if (is_array($userIds) && !empty($userIds)) {
            // sync attaches without duplicates (pivot has unique constraint)
            $group->users()->sync($userIds);
        }

        return redirect()->route('group.index')
            ->with('success', __('messages.group_created'));
    }

    /**
     * Display a group with its members.
     */
    public function show(Group $group)
    {
        $group->load('users.groups');
        $availableUsers = User::role('user')
            ->whereNotIn('id', $group->users->pluck('id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('group.show', compact('group', 'availableUsers'));
    }

    /**
     * Show the edit group form.
     */
    public function edit(Group $group)
    {
        $users = User::role('user')->orderBy('name')->get(['id', 'name']);
        $memberIds = $group->users()->pluck('users.id')->all();
        return view('group.edit', compact('group', 'users', 'memberIds'));
    }

    /**
     * Update group details and members.
     * Many-to-many: does NOT remove athlete from other groups.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string|max:1000',
            'user_ids'    => 'nullable|array',
            'user_ids.*'  => 'integer|exists:users,id',
        ]);

        $group->update($request->only('name', 'description'));

        $userIds = $request->input('user_ids', []);
        if (!is_array($userIds)) {
            $userIds = [];
        }

        // sync replaces current pivot rows for this group only
        // does NOT affect the athlete's membership in other groups
        $group->users()->sync($userIds);

        return redirect()->route('group.show', $group->id)
            ->with('success', __('messages.group_updated'));
    }

    /**
     * Delete a group (cascade removes pivot rows).
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('group.index')
            ->with('success', __('messages.group_deleted'));
    }

    /**
     * Add a single athlete to this group.
     * Validates athlete existence and prevents duplicates.
     */
    public function addMember(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $userId = $request->input('user_id');

        // Check if already a member (prevent duplicate)
        if ($group->users()->where('users.id', $userId)->exists()) {
            return redirect()->route('group.show', $group->id)
                ->with('warning', __('messages.athlete_already_in_group'));
        }

        $group->users()->attach($userId);

        return redirect()->route('group.show', $group->id)
            ->with('success', __('messages.athlete_added_to_group'));
    }

    /**
     * Remove a single athlete from this group.
     * Validates athlete existence and membership.
     */
    public function removeMember(Group $group, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('group.show', $group->id)
                ->with('error', __('messages.athlete_not_found'));
        }

        if (!$group->users()->where('users.id', $userId)->exists()) {
            return redirect()->route('group.show', $group->id)
                ->with('warning', __('messages.athlete_not_in_group'));
        }

        $group->users()->detach($userId);

        return redirect()->route('group.show', $group->id)
            ->with('success', __('messages.athlete_removed_from_group'));
    }

    /**
     * Assign all active athletes to Groupe A (keeping their other group memberships).
     */
    public function assignAllToGroupA()
    {
        $group = Group::firstOrCreate(
            ['name' => 'Groupe A'],
            ['description' => 'Groupe par défaut']
        );

        $userIds = User::role('user')->withoutBanned()->pluck('id')->all();

        if (!empty($userIds)) {
            // syncWithoutDetaching: adds missing users without removing existing ones
            // and without removing them from other groups
            $group->users()->syncWithoutDetaching($userIds);
        }

        return redirect()->route('group.index')
            ->with('success', __('messages.athletes_associated_group_a'));
    }
}
