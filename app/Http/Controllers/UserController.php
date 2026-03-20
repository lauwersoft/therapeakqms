<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->role !== User::ROLE_ADMIN) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('users.form', [
            'user' => null,
            'roles' => User::ROLES,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:' . implode(',', User::ROLES),
            'password' => 'nullable|string|min:8',
            'approved' => 'boolean',
        ]);

        $password = $request->input('password');
        $generated = false;

        if (empty($password)) {
            $password = Str::random(16);
            $generated = true;
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'password' => $password,
            'approved' => $request->boolean('approved'),
            'email_verified_at' => now(),
        ]);

        $message = "User {$user->name} created.";
        if ($generated) {
            $message .= " Generated password: {$password}";
        }

        return redirect()->route('users.index')
            ->with('success', $message);
    }

    public function edit(User $user)
    {
        return view('users.form', [
            'user' => $user,
            'roles' => User::ROLES,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:' . implode(',', User::ROLES),
            'password' => 'nullable|string|min:8',
            'approved' => 'boolean',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->approved = $request->boolean('approved');

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} updated.");
    }

    public function destroy(User $user, Request $request)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User {$name} deleted.");
    }
}
