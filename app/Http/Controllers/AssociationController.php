<?php

namespace App\Http\Controllers;

use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AssociationController extends Controller
{
    public function index()
    {
        $associations = Association::all();
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

        // Proses penyimpanan data
        $data = $request->only(['name', 'logo']);

        // Proses penyimpanan gambar
        if ($request->hasFile('logo')) {
            $imageName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/associations'), $imageName);
            $data['logo'] = 'images/associations/' . $imageName;
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

        // Proses update data
        $data = $request->only(['name', 'logo']);

        if ($request->hasFile('logo')) {
            // Hapus gambar lama jika ada
            $this->deleteImageIfExists($association->logo);

            // Simpan gambar baru
            $imageName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/associations'), $imageName);
            $data['logo'] = 'images/associations/' . $imageName;
        }

        $association->update($data);

        return redirect()->route('associations.index')->with('success', 'Association updated successfully.');
    }

    public function destroy(Association $association)
    {
        // Hapus gambar jika ada
        $this->deleteImageIfExists($association->logo);

        $association->delete();

        return redirect()->route('associations.index')->with('success', 'association deleted successfully.');
    }

    private function deleteImageIfExists($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}

