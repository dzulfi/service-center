<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SparepartController extends Controller
{
    public function index()
    {
        $spareparts = Sparepart::all();

        return view('spareparts.index', compact('spareparts'));
    }

    public function create()
    {
        return view('spareparts.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:spareparts,code',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'nullable|string'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('spareparts_images', 'public');
        }

        Sparepart::create([
            'code' => $request->code,
            'name' => $request->name,
            'image_path' => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()->route('spareparts.index')->with('success','Barang sparepart berhasil di tambahkan');
    }

    public function show(Sparepart $sparepart)
    {
        return view('spareparts.show', compact('sparepart'));
    }

    public function edit(Sparepart $sparepart) 
    {
        return view('spareparts.edit', compact('sparepart'));
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('spareparts', 'code')->ignore($sparepart->id)],
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'description' => 'nullable|string',
        ]);

        $imagePath = $sparepart->image_path; // pertahankan gambar lama jika tidak ada gambar baru
        if ($request->hasFile('image')) {
            // hapus gambar lama jika ada
            if($sparepart->image_path) {
                Storage::disk('public')->delete($sparepart->image_path);
            }
            $imagePath = $request->file('image')->store('spareparts_images', 'public');
        }

        $sparepart->update([
            'code' => $request->code,
            'name' => $request->name,
            'image_path' => $imagePath,
            'description' => $request->description
        ]);

        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil diperbaharui');
    }

    public function destroy(Sparepart $sparepart)
    {
        // Hapus gambar jika ada
        if ($sparepart->image_path) {
            Storage::disk('public')->delete($sparepart->image_path);
        }
        $sparepart->delete();
        return redirect()->route('spareparts.index')->with('success','Sparepart berhasil di hapus');
    }
}
