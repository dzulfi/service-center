<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RmaTechnician;
use Illuminate\Http\Request;

class RmaTechnicianController extends Controller
{
    public function index()
    {
        $rmaTechnicians = RmaTechnician::all();

        return view('rma_technicians.index', compact('rmaTechnicians'));
    }

    public function create()
    {
        return view('rma_technicians.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'no_telp' => 'required|string|max:15',
        ]);

        RmaTechnician::create([
            'name' => $request->name,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('rma_technicians.index')->with('success', 'Teknisi RMA berhasil dibuat');
    }

    public function edit(RmaTechnician $rmaTechnician)
    {
        return view('rma_technicians.edit', compact('rmaTechnician'));
    }

    public function update(RmaTechnician $rmaTechnician, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
        ]);

        $rmaTechnician->update([
            'name' => $request->name,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('rma_technicians.index')->with('success', 'Teknisi RMA berhasil diperbarui');
    }

    public function destroy(RmaTechnician $rmaTechnician)
    {
        $rmaTechnician->delete();
        return redirect()->route('rma_technicians.index')->with('success', 'Teknisi RMA berhasil dihapus');
    }
}
