<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Organization;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
{
    $contacts = Contact::with('organization')

        ->when($request->filled('search'), function ($query) use ($request) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('organization', function ($org) use ($search) {

                      $org->where('name', 'like', "%{$search}%");

                  });

            });

        })

        ->orderBy('last_name')
        ->paginate(10)
        ->withQueryString();

    return view('contacts.index', [

        'contacts' => $contacts,

        'totalContacts' => Contact::count(),

        'primaryContacts' => Contact::where('is_primary', true)->count(),

        'internalContacts' => Contact::whereHas('organization', function ($query) {
            $query->where('is_internal', true);
        })->count(),

        'externalContacts' => Contact::whereHas('organization', function ($query) {
            $query->where('is_internal', false);
        })->count(),

    ]);
}

    public function create()
{
    $organizations = Organization::orderBy('name')->get();

    return view('contacts.create', compact('organizations'));
}

    public function store(Request $request)
{
    $validated = $request->validate([

        'organization_id' => 'required|exists:organizations,id',

        'first_name' => 'required|max:255',

        'middle_name' => 'nullable|max:255',

        'last_name' => 'required|max:255',

        'suffix' => 'nullable|max:50',

        'position' => 'required|max:255',

        'office' => 'nullable|max:255',

        'email' => 'nullable|email',

        'mobile' => 'nullable|max:50',

        'telephone' => 'nullable|max:50',

        'is_primary' => 'boolean',

        'is_active' => 'boolean',

        'remarks' => 'nullable',

    ]);

    $validated['is_primary'] = $request->boolean('is_primary');
    $validated['is_active'] = $request->boolean('is_active');

    Contact::create($validated);

    return redirect()
        ->route('contacts.index')
        ->with('success', 'Contact created successfully.');
}

    public function show(Contact $contact)
    {
        //
    }

    public function edit(Contact $contact)
{
    $organizations = Organization::orderBy('name')->get();

    return view('contacts.edit', compact('contact', 'organizations'));
}

    public function update(Request $request, Contact $contact)
{
    $validated = $request->validate([

        'organization_id' => 'required|exists:organizations,id',

        'first_name' => 'required|max:255',

        'middle_name' => 'nullable|max:255',

        'last_name' => 'required|max:255',

        'suffix' => 'nullable|max:50',

        'position' => 'required|max:255',

        'office' => 'nullable|max:255',

        'email' => 'nullable|email',

        'mobile' => 'nullable|max:50',

        'telephone' => 'nullable|max:50',

        'is_primary' => 'boolean',

        'is_active' => 'boolean',

        'remarks' => 'nullable',

    ]);

    $validated['is_primary'] = $request->boolean('is_primary');
    $validated['is_active'] = $request->boolean('is_active');

    $contact->update($validated);

    return redirect()
        ->route('contacts.index')
        ->with('success', 'Contact updated successfully.');
}

    public function destroy(Contact $contact)
{
    $contact->delete();

    return redirect()
        ->route('contacts.index')
        ->with('success', 'Contact deleted successfully.');
}
}