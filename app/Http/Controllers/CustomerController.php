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
        $customers = Customer::with('kategori')->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.customers.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = $request->only(['name', 'kategori_id']);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $hashName = md5(time() . $logo->getClientOriginalName()) . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/logos'), $hashName);
            $data['logo'] = 'images/logos/' . $hashName;
        }

        Customer::create($data);

        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        $kategoris = Kategori::all();
        return view('admin.customers.edit', compact('customer', 'kategoris'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = $request->only(['name', 'kategori_id']);

        if ($request->hasFile('logo')) {
            $this->deleteImageIfExists($customer->logo);

            $logo = $request->file('logo');
            $hashName = md5(time() . $logo->getClientOriginalName()) . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/logos'), $hashName);
            $data['logo'] = 'images/logos/' . $hashName;
        }

        $customer->update($data);

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $this->deleteImageIfExists($customer->logo);
        $customer->delete();

        return redirect()->route('customers.index');
    }

    private function deleteImageIfExists($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
