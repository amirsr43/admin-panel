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
        $portfolios = Portfolio::with('group')->get();
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('admin.portfolios.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'published' => 'required|date',
            'group_id' => 'required|exists:groups,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashName = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/portfolios'), $hashName);
            $data['image'] = 'images/portfolios/' . $hashName;
        }

        Portfolio::create($data);

        return redirect()->route('portfolios.index')->with('success', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        $groups = Group::all();
        return view('admin.portfolios.edit', compact('portfolio', 'groups'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'published' => 'required|date',
            'group_id' => 'required|exists:groups,id',
        ]);

        $data = $request->only(['name', 'published', 'group_id']);

        if ($request->hasFile('image')) {
            if ($portfolio->image && File::exists(public_path($portfolio->image))) {
                File::delete(public_path($portfolio->image));
            }

            $image = $request->file('image');
            $hashName = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/portfolios'), $hashName);
            $data['image'] = 'images/portfolios/' . $hashName;
        }

        $portfolio->update($data);

        return redirect()->route('portfolios.index')->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->image && File::exists(public_path($portfolio->image))) {
            File::delete(public_path($portfolio->image));
        }

        $portfolio->delete();

        return redirect()->route('portfolios.index')->with('success', 'Portfolio deleted successfully.');
    }
}
