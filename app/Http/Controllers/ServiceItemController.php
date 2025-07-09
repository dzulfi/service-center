<?php

namespace App\Http\Controllers;

use App\Enums\LocationStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceItems = ServiceItem::with('customer')->get(); // Load relasi customer
        return view('service_items.index', compact('serviceItems'));
    }

    /**
     * Metode untuk menampilkan daftar semua barang service untuk dilihat Role Developer dan Superadmin
     */
    public function indexAllServiceItems()
    {
        $serviceItems = ServiceItem::with(['customer', 'creator'])->get();
        return view('service_items.index_all', compact('serviceItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // hanya admin yang bisa membuat service ini
        if (!Auth::user()->isAdmin()) {
            abort(403,'Akses Ditolak');
        }

        $customers = Customer::all(); // mengambil semua pelanggan untuk di dropdown
        $merks = ['Techma', 'Hilook', 'Hikvision', 'Dahua', 'Lainnya']; // dropdown pemilihan merk lain
        return view('service_items.create', compact('customers', 'merks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // hanya admin yang bisa membuat service item
        if (!Auth::user()->isAdmin()){
            abort(403,'Akses Ditolak');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'merk' => 'nullable|string|max:255',
            'analisa_kerusakan' => 'nullable|string',
            'jumlah_item' => 'nullable|string',
        ]);

        $serviceItem = ServiceItem::create([
            'customer_id' => $request->customer_id,
            'name' => $request->name,
            'type' => $request->type,
            'serial_number' => $request->serial_number,
            'merk' => $request->merk,
            'analisa_kerusakan' => $request->analisa_kerusakan,
            'jumlah_item' => $request->jumlah_item,
            'created_by_user_id' => Auth::id(), // Simpan ID user yang sedang login
            'location_status' => LocationStatusEnum::AtBranch,
        ]);

        return redirect()->route('service_items.index')->with('success', 'Barang servis berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceItem $serviceItem)
    {
        return view('service_items.show', compact('serviceItem'));
    }

    /**
     * Detail aktifitas barang service per id (role: developer, superadmin)
     */
    public function showDetailActivityServiceItem(ServiceItem $serviceItem)
    {
        return view('service_items.detail_activity_service_item', compact('serviceItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceItem $serviceItem)
    {
        $customers = Customer::all();
        $merks = ['Techma', 'Hilook', 'Hikvision', 'Dahua', 'Lainnya'];
        return view('service_items.edit', compact('serviceItem', 'customers', 'merks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'analisa_kerusakan' => 'nullable|string',
            'merk' => 'required|string|',
            'jumlah_item' => 'required|integer',
        ]);

        $serviceItem->update($request->all());
        return redirect()->route('service_items.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceItem $serviceItem)
    {
        $serviceItem->delete();
        return redirect()->route('service_items.index')->with('success', 'Barang service berhasil dihapus');
    }
}
