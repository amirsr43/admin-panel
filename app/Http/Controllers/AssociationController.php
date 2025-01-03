<?php

namespace App\Http\Controllers;

use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AssociationController extends Controller
{
    public function index(Request $request)
    {
        $query = Association::query();

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $associations = $query->paginate(10);
        return view('admin.associations.index', compact('associations'));
    }

    public function create()
    {
        return view('admin.associations.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['name']);

        // Proses penyimpanan gambar
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $hashName = md5(time() . $logo->getClientOriginalName()) . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/associations'), $hashName);
            $data['logo'] = 'images/associations/' . $hashName;
        }

        Association::create($data);

        return redirect()->route('associations.index')->with('success', 'Association created successfully.');
    }

    public function edit(Association $association)
    {
        return view('admin.associations.edit', compact('association'));
    }

    public function update(Request $request, Association $association)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['name']);

        if ($request->hasFile('logo')) {
            $this->deleteImageIfExists($association->logo);

            $logo = $request->file('logo');
            $hashName = md5(time() . $logo->getClientOriginalName()) . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/associations'), $hashName);
            $data['logo'] = 'images/associations/' . $hashName;
        }

        $association->update($data);

        return redirect()->route('associations.index')->with('success', 'Association updated successfully.');
    }

    public function destroy(Association $association)
    {
        $this->deleteImageIfExists($association->logo);

        $association->delete();

        return redirect()->route('associations.index')->with('success', 'Association deleted successfully.');
    }

    private function deleteImageIfExists($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
