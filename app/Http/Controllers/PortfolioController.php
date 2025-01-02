<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\Group;
use Illuminate\Support\Facades\File;

class PortfolioController extends Controller
{
    public function index()
    {
        // Ambil semua portfolio dengan grup terkait
        $portfolios = Portfolio::with('group')->get();
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        // Ambil semua grup untuk ditampilkan di form
        $groups = Group::all();
        return view('admin.portfolios.create', compact('groups'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'published' => 'required|date',
            'group_id' => 'required|exists:groups,id',
        ]);

        // Ambil data input yang diizinkan
        $data = $request->all();

        // Proses penyimpanan gambar jika ada
        if ($request->hasFile('image')) {
            // Buat nama unik untuk file gambar
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // Pindahkan gambar ke folder 'public/images/portfolios'
            $request->file('image')->move(public_path('images/portfolios'), $imageName);
            // Simpan path relatif ke gambar
            $data['image'] = 'images/portfolios/' . $imageName;
        }

        // Simpan portfolio ke database
        Portfolio::create($data);

        // Redirect setelah menyimpan
        return redirect()->route('portfolios.index')->with('success', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        // Ambil semua grup untuk ditampilkan di form edit
        $groups = Group::all();
        return view('admin.portfolios.edit', compact('portfolio', 'groups'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'published' => 'required|date',
            'group_id' => 'required|exists:groups,id',
        ]);

        // Ambil data input yang diizinkan
        $data = $request->only(['name', 'published', 'group_id']);

        // Proses penyimpanan gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($portfolio->image && File::exists(public_path($portfolio->image))) {
                File::delete(public_path($portfolio->image));
            }

            // Buat nama unik untuk gambar baru
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // Pindahkan gambar ke folder 'public/images/portfolios'
            $request->file('image')->move(public_path('images/portfolios'), $imageName);
            // Simpan path relatif ke gambar baru
            $data['image'] = 'images/portfolios/' . $imageName;
        }

        // Update portfolio di database
        $portfolio->update($data);

        // Redirect setelah update
        return redirect()->route('portfolios.index')->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        // Hapus gambar terkait jika ada
        if ($portfolio->image && File::exists(public_path($portfolio->image))) {
            File::delete(public_path($portfolio->image));
        }

        // Hapus portfolio
        $portfolio->delete();

        // Redirect setelah penghapusan
        return redirect()->route('portfolios.index')->with('success', 'Portfolio deleted successfully.');
    }
}
