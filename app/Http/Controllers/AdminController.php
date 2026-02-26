<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
         $totalUsers = User::count();
        return view('admin.dashboard',  compact('totalUsers'));
    }

    public function userList(Request $request)
    {
        $users = User::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $users->where(function($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                      ->orWhere('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('city', 'like', "%$search%");
            });
        }

        $users = $users->paginate(2)->withQueryString();

        return view('admin.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect()->route('frontend.index')
                         ->with('success', 'Logged out successfully');
    }

    public function settings()
    {
        $user = auth()->user();
        return view('admin.settings', compact('user'));
    }

    // ADMIN APNI PROFILE EDIT KARNE KE LIYE
    public function editOwn()
    {
        $user = auth()->user();
        return view('admin.edit-profile', compact('user'));
    }

    // ADMIN APNI PROFILE UPDATE KARNE KE LIYE
    public function updateOwn(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string',
            'looking_for' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string',
            'city' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'username', 'name', 'email', 'role', 'birthday',
            'gender', 'looking_for', 'marital_status', 'city'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    // DUSRE USER KO EDIT KARNE KE LIYE
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    // DUSRE USER KO UPDATE KARNE KE LIYE
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only(['username', 'name', 'email', 'role', 'birthday', 'gender', 'looking_for', 'marital_status', 'city']);

        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }
}