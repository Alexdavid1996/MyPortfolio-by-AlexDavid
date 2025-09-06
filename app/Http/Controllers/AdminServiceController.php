<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServicePage;

class AdminServiceController extends Controller
{
    public function index()
    {
        $page = ServicePage::first();
        $services = Service::all();
        return view('admin.services.index', compact('page', 'services'));
    }

    public function updatePage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        $validated['active'] = (int) $request->boolean('active');

        $page = ServicePage::first();
        if ($page) {
            $page->update($validated);
        } else {
            ServicePage::create($validated);
        }

        return redirect()->route('admin.services.index')->with('status', 'Service page updated');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_title' => 'required|string|max:255',
            'service_description' => 'required|array',
            'service_description.*' => 'nullable|string',
            'price' => 'required|string|max:255',
        ]);

        $description = collect($validated['service_description'])
            ->filter(fn($item) => trim($item) !== '')
            ->join("\n");

        Service::create([
            'service_title' => $validated['service_title'],
            'service_description' => $description,
            'price' => $validated['price'],
        ]);

        return redirect()->route('admin.services.index')->with('status', 'Service added');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_title' => 'required|string|max:255',
            'service_description' => 'required|array',
            'service_description.*' => 'nullable|string',
            'price' => 'required|string|max:255',
        ]);

        $description = collect($validated['service_description'])
            ->filter(fn($item) => trim($item) !== '')
            ->join("\n");

        $service->update([
            'service_title' => $validated['service_title'],
            'service_description' => $description,
            'price' => $validated['price'],
        ]);

        return redirect()->route('admin.services.index')->with('status', 'Service updated');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('status', 'Service deleted');
    }
}

