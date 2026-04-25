<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class AdminSubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->paginate(15);
        return view('admin.subscriptions.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscriptions.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePlan($request);

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', '✅ Plan created successfully.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('admin.subscriptions.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $validated = $this->validatePlan($request, $plan->id);

        $plan->update($validated);

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', '✅ Plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        // Note: foreign keys (user_subscriptions.plan_id) should cascade.
        $plan->delete();

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', '✅ Plan deleted successfully.');
    }

    private function validatePlan(Request $request, ?int $ignoreId = null): array
    {
        $ignoreId = $ignoreId ?? 0;

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug,' . $ignoreId,
            'description' => 'nullable|string|max:5000',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'badge_color' => 'nullable|string|max:50',
            'badge_icon' => 'nullable|string|max:100',
            'features' => 'nullable|string',
            'is_active' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $featuresRaw = trim((string) $request->input('features', ''));
        $features = null;
        if ($featuresRaw !== '') {
            // Accept JSON array or simple newline/comma separated list.
            $decoded = json_decode($featuresRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $features = $decoded;
            } else {
                $parts = preg_split("/\r\n|\n|\r|,/", $featuresRaw);
                $features = array_values(array_filter(array_map(fn($v) => trim((string) $v), $parts)));
            }
        }

        return [
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'price' => (float) $request->input('price'),
            'duration_days' => (int) $request->input('duration_days'),
            'badge_color' => $request->input('badge_color') ?: 'secondary',
            'badge_icon' => $request->input('badge_icon') ?: 'star-outline',
            'features' => $features,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => (int) $request->input('sort_order'),
        ];
    }
}

