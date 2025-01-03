<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    public function index(Request $request)
{
    $query = Service::query();

    // Filter berdasarkan pencarian
    if ($request->has('search') && $request->search) {
        $query->where('name', 'LIKE', '%' . $request->search . '%');
    }

    $services = $query->paginate(10);
    return view('admin.services.index', compact('services'));
}


    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashName = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/services'), $hashName);
            $data['image'] = 'images/services/' . $hashName;
        }

        Service::create($data);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $this->deleteImageIfExists($service->image);

            $image = $request->file('image');
            $hashName = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/services'), $hashName);
            $data['image'] = 'images/services/' . $hashName;
        }

        $service->update($data);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->deleteImageIfExists($service->image);

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    private function deleteImageIfExists($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
