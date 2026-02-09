<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function login()
    {
        return view('frontend.login');
    }

    public function register()
    {
        return view('frontend.register');
    }

     public function membership()
    {
        return view('frontend.membership');
    }

    public function community()
    {
        return view('frontend.community');
    }

    public function blogs()
    {
        return view('frontend.blogs');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function about()
    {
        return view('frontend.about');
    }

}
