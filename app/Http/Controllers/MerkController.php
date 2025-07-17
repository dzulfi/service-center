<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Merk;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    public function index()
    {
        $merks = Merk::all();

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
        return view('merks.show', compact('merk'));
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
}
