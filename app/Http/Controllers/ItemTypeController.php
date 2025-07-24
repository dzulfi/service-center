<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use App\Models\Merk;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    public function index()
    {
        $itemTypes = ItemType::paginate(10);

        return view('item_types.index', compact('itemTypes'));
    }

    public function create()
    {
        return view('item_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string',
        ]);

        ItemType::create([
            'type_name' => $request->type_name,
        ]);

        return redirect()->route('item_types.index')->with('success','Tipe berhasil dibuat');
    }

    public function show(ItemType $itemTypeId)
    {
        return view('item_types.show', compact('itemType'));
    }

    public function edit(ItemType $itemType)
    {
        return view('item_types.edit', compact('itemType'));
    }

    public function update(Request $request, ItemType $itemType)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $itemType->update($request->all());

        return redirect()->route('item_types.index')->with('success', 'Data berhasil di update');
    }

    public function destroy(ItemType $itemType)
    {
        $itemType->delete();
        return redirect()->route('item_types.index')->with('success','Tipe barang berhasil dihapus');
    }
}
