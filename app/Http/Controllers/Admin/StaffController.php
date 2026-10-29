<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:staff.view')->only(['index', 'show']);
        $this->middleware('permission:staff.create')->only(['create', 'store']);
        $this->middleware('permission:staff.edit')->only(['edit', 'update']);
        $this->middleware('permission:staff.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $staff = Staff::with('user')->latest()->paginate(25);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['student', 'parent'])->get();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email|max:191',
            'phone' => 'required|string|unique:users,phone|max:32',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'designation' => 'required|string|max:100',
            'qualification' => 'nullable|string|max:150',
            'subjects' => 'nullable|array',
            'joining_date' => 'required|date|before_or_equal:today',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,suspended,resigned',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $role = Role::findById($validated['role_id']);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'user_type' => $role->name === 'teacher' ? 'teacher' : ($role->name === 'accountant' ? 'accountant' : 'admin'),
                'is_active' => $validated['status'] === 'active',
            ]);

            $user->assignRole($role);

            $code = 'STF-' . str_pad(Staff::count() + 1, 3, '0', STR_PAD_LEFT);

            Staff::create([
                'user_id' => $user->id,
                'employee_code' => $code,
                'designation' => $validated['designation'],
                'qualification' => $validated['qualification'] ?? null,
                'subjects' => $validated['subjects'] ?? [],
                'joining_date' => $validated['joining_date'],
                'salary' => $validated['salary'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('admin.staff.index')->with('success', "Staff member onboarded with Code: {$code}");
        });
    }

    public function show(Staff $staff)
    {
        $staff->load(['user', 'batches.course']);
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $staff->load('user');
        $roles = Role::whereNotIn('name', ['student', 'parent'])->get();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:100',
            'qualification' => 'nullable|string|max:150',
            'subjects' => 'nullable|array',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,suspended,resigned',
            'notes' => 'nullable|string',
        ]);

        $staff->update($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Staff details updated.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        $staff->user()->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff profile suspended.');
    }
}
