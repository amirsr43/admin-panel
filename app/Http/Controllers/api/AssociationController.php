<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AssociationController extends Controller
{
    public function __construct()
    {
        // Menambahkan middleware untuk API
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    // Menampilkan semua data association
    public function index()
    {
        $associations = Association::all();
        return response()->json($associations);
    }

    // Menyimpan data association baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Ambil data input yang diizinkan
        $data = $request->only(['name', 'logo']);

        // Proses penyimpanan gambar jika ada
        if ($request->hasFile('logo')) {
            $imageName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/associations'), $imageName);
            $data['logo'] = 'images/associations/' . $imageName;
        }

        // Simpan association ke database
        $association = Association::create($data);

        // Respons JSON setelah data disimpan
        return response()->json(['message' => 'Association created successfully.', 'association' => $association], 201);
    }

    // Menampilkan data association berdasarkan ID
    public function show($id)
    {
        $association = Association::find($id);

        if (!$association) {
            return response()->json(['message' => 'Association not found.'], 404);
        }

        return response()->json($association);
    }

    // Mengupdate data association
    public function update(Request $request, $id)
    {
        $association = Association::find($id);

        if (!$association) {
            return response()->json(['message' => 'Association not found.'], 404);
        }

        // Validasi input
        $request->validate([
            'name' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Ambil data input yang diizinkan
        $data = $request->only(['name', 'logo']);

        // Proses penyimpanan gambar jika ada
        if ($request->hasFile('logo')) {
            // Hapus gambar lama jika ada
            $this->deleteImageIfExists($association->logo);

            // Simpan gambar baru
            $imageName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/associations'), $imageName);
            $data['logo'] = 'images/associations/' . $imageName;
        }

        // Update data association
        $association->update($data);

        return response()->json(['message' => 'Association updated successfully.', 'association' => $association]);
    }

    // Menghapus data association
    public function destroy($id)
    {
        $association = Association::find($id);

        if (!$association) {
            return response()->json(['message' => 'Association not found.'], 404);
        }

        // Hapus gambar jika ada
        $this->deleteImageIfExists($association->logo);

        // Hapus association
        $association->delete();

        return response()->json(['message' => 'Association deleted successfully.']);
    }

    // Helper untuk menghapus gambar jika ada
    private function deleteImageIfExists($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
