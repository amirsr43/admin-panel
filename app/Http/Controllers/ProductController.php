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
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashName = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $hashName);
            $data['image'] = 'images/products/' . $hashName;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->only(['name', 'category_id']);

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $image = $request->file('image');
            $hashName = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $hashName);
            $data['image'] = 'images/products/' . $hashName;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
