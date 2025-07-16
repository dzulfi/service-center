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
        $itemTypes = ItemType::all();
        $no = 1;

        return view('item_types.index', compact('itemTypes', 'no'));
    }

    public function create()
    {
        $merks = Merk::all();

        return view('item_types.create', compact('merks', ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk_id' => 'required|exists:merks,id',
            'type_name' => 'required|string',
        ]);

        ItemType::create([
            'merk_id' => $request->merk_id,
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
        $merks = Merk::all();
        return view('item_types.edit', compact('itemType', 'merks'));
    }

    public function update(Request $request, ItemType $itemType)
    {
        $request->validate([
            'merk_id' => 'required|exists:merks,id',
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
