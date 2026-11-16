<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnels = Personnel::with('user')
            ->orderBy('name')
            ->paginate(15);

        return view('personnels.index', compact('personnels'));
    }

    public function create()
    {
        $staffUsers = User::where('role', 'Staff')
            ->where('is_active', true)
            ->whereDoesntHave('personnel')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('personnels.create', compact('staffUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', 'Staff')
                    ->where('is_active', true),
                Rule::unique('personnels', 'user_id'),
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Personnel::create($validated);

        return redirect()
            ->route('personnels.index')
            ->with('success', 'Personnel added successfully.');
    }

    public function edit(Personnel $personnel)
    {
        $staffUsers = User::where('role', 'Staff')
            ->where('is_active', true)
            ->where(function ($query) use ($personnel) {
                $query->whereDoesntHave('personnel')
                    ->orWhereHas('personnel', function ($query) use ($personnel) {
                        $query->whereKey($personnel->id);
                    });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('personnels.edit', compact('personnel', 'staffUsers'));
    }

    public function update(Request $request, Personnel $personnel)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', 'Staff')
                    ->where('is_active', true),
                Rule::unique('personnels', 'user_id')
                    ->ignore($personnel->id),
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $personnel->update($validated);

        return redirect()
            ->route('personnels.index')
            ->with('success', 'Personnel updated successfully.');
    }

    public function destroy(Personnel $personnel)
    {
        if ($personnel->requests()->exists()) {
            return redirect()
                ->route('personnels.index')
                ->with('error', 'This personnel is assigned to existing requests. Deactivate them instead.');
        }

        $personnel->delete();

        return redirect()
            ->route('personnels.index')
            ->with('success', 'Personnel deleted successfully.');
    }

    public function toggleStatus(Personnel $personnel)
    {
        $personnel->update([
            'is_active' => ! $personnel->is_active,
        ]);

        return redirect()
            ->route('personnels.index')
            ->with(
                'success',
                $personnel->is_active
                    ? 'Personnel activated successfully.'
                    : 'Personnel deactivated successfully.'
            );
    }
}