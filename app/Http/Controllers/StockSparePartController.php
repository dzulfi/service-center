<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StockSparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockSparePartController extends Controller
{
    public function index() 
    {
        $stockSpareParts = StockSparePart::orderBy('name')->get();

        return view('stock_spare_parts.index', compact('stockSpareParts'));
    }

    public function create()
    {
        return view('stock_spare_parts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'initial_stock' => 'required|integer|min:0',
        ]);

        $imagePath =  null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stock_spare_parts_images', 'public');
        }

        StockSparePart::create([
            'name' => $request->name,
            'image' => $imagePath,
            'description' => $request->description,
            'stock' => $request->initial_stock,
        ]);

        return redirect()->route('stock_spare_parts.index')->with('success', 'Sparepart berhasil ditambahkan');
    }

    public function show(StockSparePart $stockSparePart) 
    {
        return view('stock_spare_parts.show', compact('stockSparePart'));
    }

    public function edit(StockSparePart $stockSparePart)
    {
        return view('stock_spare_parts.edit', compact('stockSparePart'));
    }

    public function update(Request $request, StockSparePart $stockSparePart)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'=> 'nullable|string',
            // stock tidak diupdate langsung dari form edit umum, tapi dari form terpisah
        ]);

        $imagePath = $stockSparePart->image_path; // Pertahankan gambar lama jika tidak ada gambar baru
        if ($request->hasFile('image')) {
            // hapus gambar lama jika ada
            if ($stockSparePart->image_path) {
                Storage::disk('public')->delete($stockSparePart->image_path);
            }
            $imagePath = $request->file('image')->store('stock_spare_parts_images', 'public');
        }

        return redirect()->route('stock_spare_parts.index')->with('success', 'Sparepart berhasil diperbaharui.');
    }

    public function destroy(StockSparePart $stockSparePart)
    {
        // Hapus gambar jika ada gambar
        if ($stockSparePart->image_path) {
            Storage::disk('public')->delete($stockSparePart->image_path);
        }
        $stockSparePart->delete();
        return redirect()->route('stock_spare_parts.index')->with('success','Sparepart berhasil di hapus');
    }

    public function updateStock(Request $request, StockSparePart $stockSparePart)
    {
        $request->validate([
            'added_stock' => 'required|integer|min:1', 
        ]);

        $stockSparePart->increment('stock', $request->added_stock); // menambah stock yang ada 

        return redirect()->route('stock_spare_parts.show', $stockSparePart->id)->with('success','Stock ' . $stockSparePart->name . ' berhasil ditambahkan sebanyak ' . $request->added_stock . '. Stock saat ini: ' . $stockSparePart->stock);
    }
}
