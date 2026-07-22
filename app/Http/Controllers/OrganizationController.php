<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
{
    $organizations = Organization::query()

        ->when($request->search, function ($query) use ($request) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('short_name', 'like', "%{$request->search}%")
                  ->orWhere('type', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('telephone', 'like', "%{$request->search}%");

            });

        })

        ->when($request->filled('internal'), function ($query) use ($request) {

            $query->where('is_internal', $request->internal);

        })

        ->when($request->filled('type'), function ($query) use ($request) {

            $query->where('type', $request->type);

        })

        ->orderBy('name')

        ->paginate(10)

        ->withQueryString();

    return view('organizations.index', [

        'organizations' => $organizations,

        'totalOrganizations' => Organization::count(),

        'internalOrganizations' => Organization::where('is_internal', true)->count(),

        'externalOrganizations' => Organization::where('is_internal', false)->count(),

    ]);
}

    public function create()
    {
        return view('organizations.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([

        'name' => 'required|max:255',

        'short_name' => 'nullable|max:50',

        'type' => 'required|max:100',

        'address' => 'nullable',

        'telephone' => 'nullable|max:100',

        'email' => 'nullable|email',

        'website' => 'nullable|url',

    ]);

    Organization::create([

        'name' => $validated['name'],

        'short_name' => $validated['short_name'] ?? null,

        'type' => $validated['type'],

        'address' => $validated['address'] ?? null,

        'telephone' => $validated['telephone'] ?? null,

        'email' => $validated['email'] ?? null,

        'website' => $validated['website'] ?? null,

        'is_internal' => $request->has('is_internal'),

        'is_active' => $request->has('is_active'),

    ]);

    return redirect()
        ->route('organizations.index')
        ->with('success', 'Organization created successfully.');
}

    public function show(Organization $organization)
    {
        //
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
{
    $validated = $request->validate([

        'name' => 'required|max:255',

        'short_name' => 'nullable|max:50',

        'type' => 'required|max:100',

        'address' => 'nullable',

        'telephone' => 'nullable|max:100',

        'email' => 'nullable|email',

        'website' => 'nullable|url',

    ]);

    $organization->update([

        'name' => $validated['name'],

        'short_name' => $validated['short_name'] ?? null,

        'type' => $validated['type'],

        'address' => $validated['address'] ?? null,

        'telephone' => $validated['telephone'] ?? null,

        'email' => $validated['email'] ?? null,

        'website' => $validated['website'] ?? null,

        'is_internal' => $request->has('is_internal'),

        'is_active' => $request->has('is_active'),

    ]);

    return redirect()
        ->route('organizations.index')
        ->with('success', 'Organization updated successfully.');
}

    public function destroy(Organization $organization)
{
    $organization->delete();

    return redirect()
        ->route('organizations.index')
        ->with('success', 'Organization deleted successfully.');
}
}