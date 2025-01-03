<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PortfolioController extends Controller
{
    public function __construct()
    {
        // Menambahkan middleware untuk API
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    // Menampilkan semua portfolio dengan grup terkait
    public function index()
    {
        $portfolios = Portfolio::with('group')->get();
        return response()->json($portfolios);
    }

    // Menampilkan form untuk menambahkan portfolio (untuk admin)
    public function create()
    {
        $groups = Group::all();
        return response()->json(['groups' => $groups]);
    }

    // Menyimpan data portfolio baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'published' => 'required|date',
            'group_id' => 'required|exists:groups,id',
        ]);

        $data = $request->all();

        // Proses penyimpanan gambar jika ada
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/portfolios'), $imageName);
            $data['image'] = 'images/portfolios/' . $imageName;
        }

        $portfolio = Portfolio::create($data);

        return response()->json(['message' => 'Portfolio created successfully.', 'portfolio' => $portfolio], 201);
    }

    // Menampilkan data portfolio berdasarkan ID
    public function show($id)
    {
        $portfolio = Portfolio::with('group')->find($id);

        if (!$portfolio) {
            return response()->json(['message' => 'Portfolio not found.'], 404);
        }

        return response()->json($portfolio);
    }

    // Mengupdate data portfolio
    public function update(Request $request, $id)
    {
        $portfolio = Portfolio::find($id);

        if (!$portfolio) {
            return response()->json(['message' => 'Portfolio not found.'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'published' => 'required|date',
            'group_id' => 'required|exists:groups,id',
        ]);

        $data = $request->only(['name', 'published', 'group_id']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($portfolio->image && File::exists(public_path($portfolio->image))) {
                File::delete(public_path($portfolio->image));
            }

            // Proses penyimpanan gambar baru
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/portfolios'), $imageName);
            $data['image'] = 'images/portfolios/' . $imageName;
        }

        $portfolio->update($data);

        return response()->json(['message' => 'Portfolio updated successfully.', 'portfolio' => $portfolio]);
    }

    // Menghapus data portfolio
    public function destroy($id)
    {
        $portfolio = Portfolio::find($id);

        if (!$portfolio) {
            return response()->json(['message' => 'Portfolio not found.'], 404);
        }

        // Hapus gambar terkait jika ada
        if ($portfolio->image && File::exists(public_path($portfolio->image))) {
            File::delete(public_path($portfolio->image));
        }

        $portfolio->delete();

        return response()->json(['message' => 'Portfolio deleted successfully.']);
    }

    // Menampilkan portfolio berdasarkan group
    public function portfoliosByGroup($groupId)
    {
        $group = Group::find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found.'], 404);
        }

        $portfolios = Portfolio::where('group_id', $groupId)->with('group')->get();

        if ($portfolios->isEmpty()) {
            return response()->json(['message' => 'No portfolios found in this group.'], 404);
        }

        return response()->json($portfolios);
    }
}
