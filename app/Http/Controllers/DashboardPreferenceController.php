<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardPreferenceController extends Controller
{
    private array $availableStats = [
        'incoming',
        'ongoing',
        'completed',
        'total',
        'assigned',
        'archived',
    ];

    public function update(Request $request)
    {
        $validated = $request->validate([
            'visible_stats' => ['required', 'array', 'min:1'],
            'visible_stats.*' => [
                'required',
                'string',
                'distinct',
                Rule::in($this->availableStats),
            ],
        ]);

        $request->user()->dashboardPreference()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['visible_stats' => $validated['visible_stats']]
        );

        return redirect()
            ->route('dashboard')
            ->with('success', 'Dashboard preferences saved successfully.');
    }
}