<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    

   
    // Show signup form
    public function show()
    {
        return view('frontend.register'); // blade file for your form
    }

    // Handle form submission
    public function register(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // confirmed checks password_confirmation
            'birthday' => 'nullable|date',
            'gender' => 'required|in:Man,Woman',
            'looking_for' => 'required|in:Man,Woman',
            'marital_status' => 'required|in:Single,Married',
            'city' => 'nullable|max:255',
        ]);

        // 2. Save to database
        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // very important
            'role' => 'user',
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'looking_for' => $request->looking_for,
            'marital_status' => $request->marital_status,
            'city' => $request->city,
        ]);

        // 3. Redirect after success
        return redirect()->route('frontend.index')->with('success', 'Account created successfully!');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // ✅ role-based redirect
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')
                             ->with('success', 'Welcome Admin!');
        }

        return redirect()->route('frontend.index')
                         ->with('success', 'Login successful!');
    }

    return back()->with('error', 'Invalid email or password');
}
}




