<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        if ($request->user()->role !== User::ROLE_ADMIN) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin($request);
        $users = User::orderBy('name')->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);
        return view('users.form', [
            'user' => null,
            'roles' => User::ROLES,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'organisation' => 'nullable|string|max:100',
            'timezone' => 'required|string|max:50',
            'role' => 'required|in:' . implode(',', User::ROLES),
            'password' => 'nullable|string|min:8',
            'approved' => 'boolean',
            'track_activity' => 'boolean',
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
            'organisation' => $request->input('organisation'),
            'timezone' => $request->input('timezone'),
            'track_activity' => $request->boolean('track_activity'),
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

    public function edit(Request $request, User $user)
    {
        $this->authorizeAdmin($request);
        return view('users.form', [
            'user' => $user,
            'roles' => User::ROLES,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'organisation' => 'nullable|string|max:100',
            'timezone' => 'required|string|max:50',
            'role' => 'required|in:' . implode(',', User::ROLES),
            'password' => 'nullable|string|min:8',
            'approved' => 'boolean',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->organisation = $request->input('organisation');
        $user->timezone = $request->input('timezone');
        $user->track_activity = $request->boolean('track_activity');
        $user->role = $request->input('role');
        $user->approved = $request->boolean('approved');

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} updated.");
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeAdmin($request);
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User {$name} deleted.");
    }
}
