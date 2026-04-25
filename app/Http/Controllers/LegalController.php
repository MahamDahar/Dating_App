<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\Http\Request;

class LegalController extends Controller {
    public function index() {
        $pages = LegalPage::all()->keyBy('type');
        return view('user.legal.index', compact('pages'));
    }
}