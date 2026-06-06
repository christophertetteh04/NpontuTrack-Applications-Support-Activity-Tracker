<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!\Auth::user()->isAdmin()) {
                abort(403, 'Only administrators can manage users.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:users',
            'email'       => 'required|email|unique:users',
            'phone'       => 'nullable|string|max:20',
            'department'  => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'role'        => 'required|in:admin,team_lead,staff',
            'password'    => 'required|string|min:8|confirmed',
        ]);

        User::create(array_merge($data, ['password' => Hash::make($data['password'])]));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'department' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'role'       => 'required|in:admin,team_lead,staff',
            'is_active'  => 'nullable|boolean',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }
}
