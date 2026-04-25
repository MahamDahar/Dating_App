<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http;

class HelpController extends Controller {
    public function index() {
        $faqs = Faq::active()
            ->orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');  // group by category for sections

        return view('user.help.index', compact('faqs'));
    }
}
