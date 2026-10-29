<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'role' => ['nullable', 'string', 'in:super-admin,admin,teacher,accountant,receptionist,student,parent'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // 1. Ensure Super-Admin role is attached if user is an admin
        if ($user->user_type === 'admin' || $request->input('role') === 'super-admin' || $request->input('role') === 'admin') {
            try {
                $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
                
                // Ensure dashboard.view and basic permissions exist and are attached
                $dashPerm = Permission::firstOrCreate(['name' => 'dashboard.view', 'guard_name' => 'web']);
                if (!$superAdminRole->hasPermissionTo('dashboard.view')) {
                    $superAdminRole->givePermissionTo($dashPerm);
                }

                if (!$user->hasRole('super-admin')) {
                    $user->assignRole($superAdminRole);
                }
            } catch (\Throwable $e) {
                // Fail-safe gracefully logged
            }
        }

        // 2. Synchronize selected role if requested from the login role switcher
        $selectedRole = $request->input('role');
        if ($selectedRole) {
            $roleName = ($selectedRole === 'admin') ? 'super-admin' : $selectedRole;
            try {
                $roleModel = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($roleModel);
                }
            } catch (\Throwable $e) {
                // Fail-safe
            }
        }

        // 3. Clear cached permissions for instant authorization
        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        } catch (\Throwable $e) {
            // Ignored if registrar not booted
        }

        // 4. Role-Based Navigation Routing
        $targetRole = $selectedRole ?? $user->user_type;

        if ($targetRole === 'teacher' || $user->user_type === 'teacher' || $user->hasRole('teacher')) {
            return redirect()->intended(route('teacher.dashboard'));
        }

        if ($targetRole === 'student' || $user->user_type === 'student' || $user->hasRole('student')) {
            return redirect()->intended(route('student.dashboard'));
        }

        if ($targetRole === 'parent' || $user->user_type === 'parent' || $user->hasRole('parent')) {
            return redirect()->intended(route('parent.dashboard'));
        }

        // Default to Executive Admin Dashboard
        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
