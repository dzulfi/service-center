<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use App\Models\Merk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemTypeController extends Controller
{
    public function index()
    {
        return view('item_types.index');
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

    public function getDataItemTypes(Request $request)
    {
        $query = ItemType::query();

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('item_types.edit', $row->id) . '" class="edit-button">Edit</a>
                        <form action="' . route('item_types.destroy', $row->id) . '" method="POST" style="display: inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="delete-button" onclick="return confirm(\'Anda yakin menghapus tipe barang ini?\')">Hapus</button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action']) // agar kolom action tidak di-escape
            ->addIndexColumn()
            ->make(true);
    }
}
