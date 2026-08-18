<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminInviteNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display a list of all admin users.
     */
    public function index(): View
    {
        $admins = User::whereIn('role', [
            UserRole::SuperAdmin->value,
            UserRole::SponsorshipAdmin->value,
            UserRole::UpdateAdmin->value,
        ])->orderBy('name')->get();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create(): View
    {
        $roles = UserRole::adminRoles();

        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created admin user and send an invite email to set their password.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in(array_map(fn ($r) => $r->value, UserRole::adminRoles()))],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)),
            'role' => $validated['role'],
        ]);

        // Generate a password reset token and send the invite email
        $token = Password::broker()->createToken($user);
        $user->notify(new AdminInviteNotification($token));

        return redirect()->route('admin.admins.index')
            ->with('status', 'Admin user created and invite email sent.');
    }

    /**
     * Show the form for editing an admin user's role.
     */
    public function edit(User $user): View
    {
        // Only allow editing admin users
        if (! $user->isAdmin()) {
            abort(404);
        }

        $roles = UserRole::adminRoles();

        return view('admin.admins.edit', compact('user', 'roles'));
    }

    /**
     * Update an admin user's details and role.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        if (! $user->isAdmin()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(array_map(fn ($r) => $r->value, UserRole::adminRoles()))],
        ]);

        $user->update($validated);

        return redirect()->route('admin.admins.index')
            ->with('status', 'Admin user updated successfully.');
    }

    /**
     * Remove an admin user (demotes to sponsor or deletes).
     */
    public function destroy(User $user): RedirectResponse
    {
        if (! $user->isAdmin()) {
            abort(404);
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You cannot remove your own admin account.');
        }

        $user->delete();

        return redirect()->route('admin.admins.index')
            ->with('status', 'Admin user removed.');
    }
}
