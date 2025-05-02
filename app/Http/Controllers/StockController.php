<?php

namespace App\Http\Controllers;

use App\Models\Fruit;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $fruits = Fruit::all();
        return view('stock.index', compact('fruits'));
    }

    public function create()
    {
        return view('stock.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        Fruit::create($request->all());
        return redirect()->route('stock.index')->with('success', 'Fruit added successfully!');
    }

    public function edit(Fruit $stock)
    {
        return view('stock.edit', compact('stock'));
    }

    public function update(Request $request, Fruit $stock)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        $stock->update($request->all());
        return redirect()->route('stock.index')->with('success', 'Fruit updated successfully!');
    }

    public function destroy(Fruit $stock)
    {
        $stock->delete();
        return redirect()->route('stock.index')->with('success', 'Fruit deleted successfully!');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $fruits = Fruit::where('name', 'like', "%{$search}%")->get();
        return view('stock.index', compact('fruits'));
    }
}