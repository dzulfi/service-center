<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branchOffices = BranchOffice::all();
        return view('branch_offices.index', compact('branchOffices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branch_offices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:branch_offices',
            'code' => 'required|string|max:10|unique:branch_offcices', // unik di tabel branch_offices
            'address' => 'required|string',
            'sub_district' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        BranchOffice::create($request->all());

        return redirect()->route('branch_offices.index')->with('success', 'Kantor cabang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(BranchOffice $branchOffice)
    {
        return view('branch_offices.show', compact('branchOffice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BranchOffice $branchOffice)
    {
        return view('branch_offices.edit', compact('branchOffice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BranchOffice $branchOffice)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('branch_offices')->ignore($branchOffice->id)],
            'code' => ['required', 'string', 'max:10', Rule::unique('branch_offices')->ignore($branchOffice->id)], // Aturan validasi untuk code saat update: unik, kecuali untuk dirinya sendiri
            'address' => 'required|string',
            'sub_district' => 'nullable|string|max:255',
            'disctrict' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $branchOffice->update($request->all());

        return redirect()->route('branch_offices.index')->with('success', 'Kantor cabang berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchOffice $branchOffice)
    {
        $branchOffice->delete();
        return redirect()->route('branch_offices.index')->with('success', 'Kantor Cabang berhasil di hapus');
    }
}
