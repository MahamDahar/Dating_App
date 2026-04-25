<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\Http\Request;

class AdminLegalController extends Controller {

    public function index() {
        $pages = LegalPage::all()->keyBy('type');
        return view('admin.legal.index', compact('pages'));
    }

    public function update(Request $request, $type) {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        LegalPage::where('type', $type)->update([
            'title'           => $request->title,
            'content'         => $request->content,
            'last_updated_at' => now(),
        ]);

        return back()->with('success', ucwords(str_replace('_', ' ', $type)) . ' updated successfully!');
    }
}
