<?php

namespace App\Http\Controllers;

use App\Models\GroupUser;
use Illuminate\Http\Request;

class GroupUserController extends Controller
{
    public function index()
    {
        $groups = GroupUser::orderBy('group_user_id')->get();
        return view('group_users.index', compact('groups'));
    }

    public function create()
    {
        return view('group_users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_user_name' => 'required|string|max:255',
            'is_super_user' => 'nullable|boolean'
        ]);

        GroupUser::create([
            'group_user_name' => $request->group_user_name,
            'is_super_user' => $request->is_super_user ?? 0
        ]);

        return redirect()->route('group_users.index')
            ->with('success','Group created successfully');
    }

    public function edit(GroupUser $group_user)
    {
        return view('group_users.edit', compact('group_user'));
    }

    public function update(Request $request, GroupUser $group_user)
    {
        $request->validate([
            'group_user_name' => 'required|string|max:255',
            'is_super_user' => 'nullable|boolean'
        ]);

        $group_user->update([
            'group_user_name' => $request->group_user_name,
            'is_super_user' => $request->is_super_user ?? 0
        ]);

        return redirect()->route('group_users.index')
            ->with('success','Group updated successfully');
    }

    public function destroy(GroupUser $group_user)
    {
        $group_user->delete();

        return redirect()->route('group_users.index')
            ->with('success','Group deleted successfully');
    }
}
