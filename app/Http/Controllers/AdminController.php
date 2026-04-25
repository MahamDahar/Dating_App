<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MarriageMatch;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();

        try { $totalSubscribers = User::where('is_premium', true)->count(); }
        catch (\Exception $e) { $totalSubscribers = 0; }

        try { $totalMatches = MarriageMatch::count(); }
        catch (\Exception $e) { $totalMatches = 0; }

        try { $totalRevenue = User::where('is_premium', true)->count() * 4; }
        catch (\Exception $e) { $totalRevenue = 0; }

        // Active Users Today
        try { $activeUsersToday = User::where('role','user')->whereDate('updated_at', Carbon::today())->count(); }
        catch (\Exception $e) { $activeUsersToday = 0; }

        // New Signups Today
        try { $newSignupsToday = User::where('role','user')->whereDate('created_at', Carbon::today())->count(); }
        catch (\Exception $e) { $newSignupsToday = 0; }

        // Gender Ratio
        try {
            $totalMen   = User::where('role','user')->where('gender','Man')->count();
            $totalWomen = User::where('role','user')->where('gender','Woman')->count();
        } catch (\Exception $e) { $totalMen = 0; $totalWomen = 0; }

        // Verified Users
        try { $verifiedUsers = User::where('role','user')->whereNotNull('email_verified_at')->count(); }
        catch (\Exception $e) { $verifiedUsers = 0; }

        return view('admin.dashboard', compact(
            'totalUsers','totalSubscribers','totalMatches','totalRevenue',
            'activeUsersToday','newSignupsToday',
            'totalMen','totalWomen','verifiedUsers'
        ));
    }

    public function userList(Request $request)
    {
        $users = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $users->where(function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                      ->orWhere('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('city', 'like', "%$search%");
            });
        }
        $users = $users->paginate(5)->withQueryString();
        return view('admin.index', compact('users'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function loginAsUser($id)
    {
        $user = User::findOrFail($id);
        session(['admin_user_id' => Auth::id()]);
        Auth::login($user);
        return redirect()->route('user.discover')
            ->with('success', 'Logged in as ' . $user->name . '. Click "Return to Admin" to go back.');
    }

    public function returnToAdmin()
    {
        $adminId = session('admin_user_id');
        if (!$adminId) return redirect()->route('admin.dashboard');
        $admin = User::findOrFail($adminId);
        session()->forget('admin_user_id');
        Auth::login($admin);
        return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('frontend.index')->with('success', 'Logged out successfully');
    }

    public function settings()
    {
        return view('admin.settings', ['user' => auth()->user()]);
    }

    public function editOwn()
    {
        return view('admin.edit-profile', ['user' => auth()->user()]);
    }

    public function updateOwn(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'username'       => 'required|string|max:255|unique:users,username,' . $user->id,
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'       => 'nullable|string|min:6|confirmed',
            'role'           => 'required|string',
            'birthday'       => 'nullable|date',
            'gender'         => 'nullable|string',
            'looking_for'    => 'nullable|string|max:255',
            'marital_status' => 'nullable|string',
            'city'           => 'nullable|string|max:255',
        ]);
        $data = $request->only(['username','name','email','role','birthday','gender','looking_for','marital_status','city']);
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $user->update($data);
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function edit($id)
    {
        return view('admin.edit-user', ['user' => User::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->only(['username','name','email','role','birthday','gender','looking_for','marital_status','city']);
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }
}