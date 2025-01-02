<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    // Tambahkan middleware untuk API
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    // Menampilkan semua services
    public function index()
    {
        $services = Service::all();
        return response()->json($services);
    }

    // Menampilkan form untuk menambahkan service (untuk admin)
    public function create()
    {
        return response()->json(['message' => 'Create service form']);
    }

    // Menyimpan data service baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/services'), $imageName);
            $data['image'] = 'images/services/' . $imageName;
        }

        $service = Service::create($data);

        return response()->json(['message' => 'Service created successfully.', 'service' => $service], 201);
    }

    // Menampilkan data service berdasarkan ID
    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json($service);
    }

    // Mengupdate data service
    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            $this->deleteImageIfExists($service->image);

            // Simpan gambar baru
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/services'), $imageName);
            $data['image'] = 'images/services/' . $imageName;
        }

        $service->update($data);

        return response()->json(['message' => 'Service updated successfully.', 'service' => $service]);
    }

    // Menghapus service
    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        // Hapus gambar jika ada
        $this->deleteImageIfExists($service->image);

        $service->delete();

        return response()->json(['message' => 'Service deleted successfully.']);
    }

    private function deleteImageIfExists($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
