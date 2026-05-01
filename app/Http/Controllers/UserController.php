<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\GroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('groupUser')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $groups = GroupUser::all();
        return view('users.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'group_user_id' => 'required',
            'is_enable' => 'required',
        ], [
            'username.unique' => 'Username นี้ถูกใช้แล้ว',
            'email.unique' => 'Email นี้ถูกใช้แล้ว',
            'password.min' => 'Password ต้องอย่างน้อย 8 ตัวอักษร'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'group_user_id' => $request->group_user_id,
            'is_enable' => $request->is_enable,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('users.index');
    }
    public function edit(User $user)
    {
        $groups = GroupUser::all();
        return view('users.edit', compact('user', 'groups'));
    }

    public function update(Request $request, User $user)
    {
         $request->validate([
    'username' => [
        'required',
        Rule::unique('users')->ignore($user->id)
    ],
    'email' => [
        'required',
        'email',
        Rule::unique('users')->ignore($user->id)
    ]
]);
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'group_user_id' => $request->group_user_id,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
