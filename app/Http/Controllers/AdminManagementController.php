<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    // ── List all admin users + roles ──
    public function index()
    {
        $admins      = User::where('role', 'admin')->with('roleModel')->get();
        $roles       = Role::withCount('users')->get();

        return view('admin.admin-management.index', compact('admins', 'roles'));
    }

    // ── Show role permissions (edit page) ──
    public function editRole($id)
    {
        $role        = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy('group');

        return view('admin.admin-management.edit-role', compact('role', 'permissions'));
    }

    // ── Update role permissions ──
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Super admin cannot be edited
        if ($role->name === 'super_admin') {
            return back()->with('error', 'Super Admin permissions cannot be modified.');
        }

        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);

        return back()->with('success', '✅ Permissions updated for ' . $role->display_name);
    }

    // ── Assign role to admin user ──
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', '✅ Role assigned to ' . $user->name);
    }

    // ── Create new admin user ──
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => strtolower(str_replace(' ', '_', $request->name)) . rand(100, 999),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'role_id'  => $request->role_id,
        ]);

        return back()->with('success', '✅ New admin created successfully.');
    }

    // ── Remove admin access ──
    public function removeAdmin($id)
    {
        $user = User::findOrFail($id);

        // Cannot remove super admin
        if ($user->roleModel?->name === 'super_admin') {
            return back()->with('error', 'Cannot remove Super Admin access.');
        }

        $user->update(['role' => 'user', 'role_id' => null]);

        return back()->with('success', $user->name . ' admin access removed.');
    }
}