<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use App\Models\ServiceProcess;
use Illuminate\Http\Request;

class ServiceProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceProcesses = ServiceProcess::with('serviceItem.customer')->get();
        return view('service_processes.index', compact('serviceProcesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua service untuk dropdown
        $serviceItems = ServiceItem::with('customer')->get();
        $statuses = ['Pending', 'Diangnosis', 'Proses pengerjaan', 'Menuggu Sparepart', 'Selesai', 'Tidak bisa diperbaiki'];
        return view('service_processes.create', compact('serviceItems', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_item_id' => 'required|exists:service_items,id',
            'damage_analysis_detail' => 'nullable|string',
            'solution' => 'nullable|string',
            'process_statu' => 'required|string|in:Pending,Diangnosis,Proses pengerjaan,Menuggu Sparepart,Selesai,Tidak bisa diperbaiki',
            'keterangan' => 'nullable|string',
        ]);

        ServiceProcess::create($request->all());

        return redirect()->route('service_processes.index')->with('success', 'Proses servis berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceProcess $serviceProcess)
    {
        // Load relasi serviceItem dan customer
        $serviceProcess->load('serviceItem.customer');
        return view('service_processes.show', compact('serviceProcess'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceProcess $serviceProcess)
    {
        $serviceItems = ServiceItem::with('customer')->get();
        $statuses = ['Pending', 'Diangnosis', 'Proses pengerjaan', 'Menuggu Sparepart', 'Selesai', 'Tidak bisa diperbaiki'];
        return view('service_processes.edit', compact('serviceProcess', 'serviceItems', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceProcess $serviceProcess)
    {
        $request->validate([
            'service_item_id' => 'required|exists:service_items,id',
            'damage_analisys_detail' => 'nullable|string',
            'solution' => 'nullable|string',
            'process_status' => 'required|string|in:Pending,Diangnosis,Proses pengerjaan,Menuggu Sparepart,Selesai,Tidak bisa diperbaiki',
            'keterangan' => 'nullable|string',
        ]);

        $serviceProcess->update($request->all());
        return redirect()->route('service_processes.index')->with('success', 'Proses service berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceProcess $serviceProcess)
    {
        $serviceProcess->delete();
        return redirect()->route('service_processes.index')->with('success', 'Proses servis berhasil dihapus!');
    }
}
