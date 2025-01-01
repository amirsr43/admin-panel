<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    public function index()
    {
        // Ambil semua data pelanggan dengan kategori terkait
        $customers = Customer::with('kategori')->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        // Ambil semua kategori untuk ditampilkan pada form
        $kategoris = Kategori::all();
        return view('admin.customers.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        // Ambil data input yang diizinkan
        $data = $request->only(['name', 'kategori_id']);

        // Proses penyimpanan logo jika ada
        if ($request->hasFile('logo')) {
            // Buat nama unik untuk file logo
            $logoName = time() . '_' . $request->file('logo')->getClientOriginalName();
            // Pindahkan logo ke folder 'public/logos'
            $request->file('logo')->move(public_path('images/logos'), $logoName);
            // Simpan path relatif ke logo
            $data['logo'] = 'images/logos/' . $logoName;
        }

        // Simpan customer ke database
        Customer::create($data);

        // Redirect setelah menyimpan
        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        // Ambil semua kategori untuk ditampilkan pada form edit
        $kategoris = Kategori::all();
        return view('admin.customers.edit', compact('customer', 'kategoris'));
    }

    public function update(Request $request, Customer $customer)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        // Ambil data input yang diizinkan
        $data = $request->only(['name', 'kategori_id']);
        
        // Proses penyimpanan logo jika ada
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($customer->logo && File::exists(public_path($customer->logo))) {
                File::delete(public_path($customer->logo));
            }

            // Buat nama unik untuk logo baru
            $logoName = time() . '_' . $request->file('logo')->getClientOriginalName();
            // Pindahkan logo baru ke folder 'public/logos'
            $request->file('logo')->move(public_path('images/logos'), $logoName);
            // Simpan path relatif ke logo baru
            $data['logo'] = 'images/logos/' . $logoName;
        }

        // Update data customer di database
        $customer->update($data);

        // Redirect setelah update
        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        // Hapus logo terkait jika ada
        if ($customer->logo && File::exists(public_path($customer->logo))) {
            File::delete(public_path($customer->logo));
        }

        // Hapus customer dari database
        $customer->delete();

        // Redirect setelah penghapusan
        return redirect()->route('customers.index');
    }
}
