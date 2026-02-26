<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function index()
    {
        return view('user.premium.plans');
    }

    public function checkout(Request $request)
    {
        // Stripe logic comes later
    }

    public function success()
    {
        return view('user.premium.success');
    }

    public function cancel()
    {
        return redirect()->route('user.premium.plans');
    }
}