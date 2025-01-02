<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    // Tambahkan middleware untuk API
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    // Menampilkan semua data pelanggan dengan kategori terkait
    public function index()
    {
        $customers = Customer::with('kategori')->get();
        return response()->json($customers);
    }

    // Menampilkan form untuk menambahkan customer (untuk admin)
    public function create()
    {
        $kategoris = Kategori::all();
        return response()->json(['categories' => $kategoris]);
    }

    // Menyimpan data customer baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        // Ambil data input yang diizinkan
        $data = $request->only(['name', 'kategori_id']);

        // Proses penyimpanan logo jika ada
        if ($request->hasFile('logo')) {
            $logoName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/logos'), $logoName);
            $data['logo'] = 'images/logos/' . $logoName;
        }

        $customer = Customer::create($data);

        return response()->json(['message' => 'Customer created successfully.', 'customer' => $customer], 201);
    }

    // Menampilkan data customer berdasarkan ID
    public function show($id)
    {
        $customer = Customer::with('kategori')->find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        return response()->json($customer);
    }

    // Mengupdate data customer
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = $request->only(['name', 'kategori_id']);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($customer->logo && File::exists(public_path($customer->logo))) {
                File::delete(public_path($customer->logo));
            }

            // Proses penyimpanan logo baru
            $logoName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/logos'), $logoName);
            $data['logo'] = 'images/logos/' . $logoName;
        }

        $customer->update($data);

        return response()->json(['message' => 'Customer updated successfully.', 'customer' => $customer]);
    }

    // Menghapus data customer
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        // Hapus logo terkait jika ada
        if ($customer->logo && File::exists(public_path($customer->logo))) {
            File::delete(public_path($customer->logo));
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully.']);
    }
}

