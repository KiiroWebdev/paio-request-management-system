<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $users = User::orderBy('name')->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
 * Show the form for creating a new user.
 */
public function create(): View
{
    return view('users.create');
}

/**
 * Store a newly created user.
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        'role' => ['required', 'in:Admin,Staff'],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],
        'role' => $validated['role'],
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'User created successfully.');
}

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified user.
     */
    /**
 * Show the form for editing the specified user.
 */
public function edit(User $user): View
{
    return view('users.edit', compact('user'));
}

/**
 * Update the specified user.
 */
public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],

        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            'unique:users,email,' . $user->id,
        ],

        'role' => [
            'required',
            'in:Admin,Staff',
        ],

        'password' => [
            'nullable',
            'confirmed',
            \Illuminate\Validation\Rules\Password::defaults(),
        ],
    ]);

    // Prevent an Admin from removing their own Admin role.
    if ($user->id === auth()->id() && $validated['role'] !== 'Admin') {
        return back()
            ->withInput()
            ->withErrors([
                'role' => 'You cannot remove your own Admin role.',
            ]);
    }

    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->role = $validated['role'];

    // Only change the password if one was provided.
    if (!empty($validated['password'])) {
        $user->password = $validated['password'];
    }

    $user->save();

    return redirect()
        ->route('users.index')
        ->with('success', 'User updated successfully.');
}

    /**
     * Remove the specified user.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
 * Activate or deactivate a user.
 */
public function toggleStatus(User $user)
{
    // Prevent an Admin from deactivating their own account.
    if ($user->id === auth()->id()) {
        return back()->withErrors([
            'user' => 'You cannot deactivate your own account.',
        ]);
    }

    $user->is_active = ! $user->is_active;
    $user->save();

    $status = $user->is_active ? 'activated' : 'deactivated';

    return redirect()
        ->route('users.index')
        ->with('success', "User {$status} successfully.");
}
}