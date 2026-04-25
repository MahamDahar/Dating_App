<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller {

    public function index() {
        $faqs = Faq::orderBy('category')->orderBy('order')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create() {
        return view('admin.faqs.create');
    }

    public function store(Request $request) {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'category' => 'required|string|max:100',
            'order'    => 'integer|min:0',
        ]);

        Faq::create($request->all());
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq) {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq) {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'category' => 'required|string|max:100',
            'order'    => 'integer|min:0',
        ]);

        $faq->update($request->all());
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq) {
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }

    // Toggle active/inactive
    public function toggle(Faq $faq) {
        $faq->update(['is_active' => !$faq->is_active]);
        return back()->with('success', 'FAQ status updated.');
    }
}
