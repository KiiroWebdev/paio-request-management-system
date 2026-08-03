<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = Service::query()

            ->when($request->search, function ($query) use ($request) {

                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');

            })

            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalServices = Service::count();

        $activeServices = Service::where('is_active', true)->count();

        $inactiveServices = Service::where('is_active', false)->count();

        return view('services.index', compact(
            'services',
            'totalServices',
            'activeServices',
            'inactiveServices'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'sort_order' => 'nullable|integer|min:0',

        ]);

        Service::create([

            'name' => $validated['name'],

            'slug' => $this->generateUniqueSlug($validated['name']),

            'description' => $validated['description'] ?? null,

            'sort_order' => $validated['sort_order'] ?? 0,

            'is_active' => $request->boolean('is_active'),

        ]);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'sort_order' => 'nullable|integer|min:0',

        ]);

        $service->update([

            'name' => $validated['name'],

            'slug' => $this->generateUniqueSlug(
    $validated['name'],
    $service->id
),

            'description' => $validated['description'] ?? null,

            'sort_order' => $validated['sort_order'] ?? 0,

            'is_active' => $request->boolean('is_active'),

        ]);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
    /**
 * Generate a unique slug for a service.
 */
private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
{
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $counter = 1;

    while (
        Service::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
    ) {
        $slug = "{$originalSlug}-{$counter}";
        $counter++;
    }

    return $slug;
}
}
