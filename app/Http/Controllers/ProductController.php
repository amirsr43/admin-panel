<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua produk dengan kategori terkait
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // Ambil semua kategori untuk ditampilkan di form
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Ambil data input yang diizinkan
        $data = $request->all();

        // Proses penyimpanan gambar jika ada
        if ($request->hasFile('image')) {
            // Buat nama unik untuk file gambar
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // Pindahkan gambar ke folder 'public/images/products'
            $request->file('image')->move(public_path('images/products'), $imageName);
            // Simpan path relatif ke gambar
            $data['image'] = 'images/products/' . $imageName;
        }

        // Simpan produk ke database
        Product::create($data);

        // Redirect setelah menyimpan
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        // Ambil semua kategori untuk ditampilkan di form edit
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Ambil data input yang diizinkan
        $data = $request->only(['name', 'category_id']);

        // Proses penyimpanan gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            // Buat nama unik untuk gambar baru
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // Pindahkan gambar ke folder 'public/images/products'
            $request->file('image')->move(public_path('images/products'), $imageName);
            // Simpan path relatif ke gambar baru
            $data['image'] = 'images/products/' . $imageName;
        }

        // Update produk di database
        $product->update($data);

        // Redirect setelah update
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Hapus gambar terkait jika ada
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        // Hapus produk
        $product->delete();

        // Redirect setelah penghapusan
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
