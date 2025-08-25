<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Merk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MerkController extends Controller
{
    public function index()
    {
        $merks = Merk::paginate(10);

        return view("merks.index", compact("merks"));
    }

    public function create()
    {
        return view('merks.create');
    }

    public function store(Request $request, Merk $merk)
    {
        $request->validate([
            'merk_name'=> 'required|string',
        ]);

        Merk::create([
            'merk_name' => $request->merk_name
        ]);

        return redirect()->route('merks.index')->with('success','Merk berhasil ditambahkan');
    }

    public function show(Merk $merk)
    {
        $itemTypes = $merk->itemTypes()->paginate(10);
        return view('merks.show', compact('merk', 'itemTypes'));
    }

    public function edit(Merk $merk)
    {
        return view('merks.edit', compact('merk'));
    }

    public function update(Request $request, Merk $merk)
    {
        $request->validate([
            'merk_name' => 'required|string',
        ]);

        $merk->update($request->all());

        return redirect()->route('merks.index')->with('success','Merk berhasil diperbaharui');
    }

    public function destroy(Merk $merk)
    {
        $merk->delete();
        return redirect()->route('merks.index')->with('success','Merk berhasil dihapus');
    }

    public function getDataMerks(Request $request)
    {
        $query = Merk::query();

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('merks.edit', $row->id) . '" class="edit-button">Edit</a>
                        <form action="' . route('merks.destroy', $row->id) . '" method="POST" style="display: inline;">
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
