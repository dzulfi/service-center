<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\User;
use App\Enums\LocationStatusEnum;
use App\Enums\ShipmentStatusEnum;
use App\Enums\ShipmentTypeEnum;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    // Admin side: Mengirim Barang ke RMA

    /**
     * Menampilkan daftar barang service di Cabang yang siap dikirim ke RMA,
     * (Barang baru dibuat, location_status = 'At_BranchOffice')
     */
    public function indexOutboundToRma()
    {
        // Hanya admin yang bisa melihat ini
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak.');
        }

        $loggedInUserId = Auth::id();

        // Ambil service items yang status lokasinya masih dicabang
        $serviceItems = ServiceItem::with(['customer', 'creator', 'serviceProcesses'])
            ->where('location_status', LocationStatusEnum::AtBranch)
            ->where('created_by_user_id', $loggedInUserId)
            ->where('location_status', LocationStatusEnum::AtBranch)
            ->get();
        
        return view('shipments.admin.outbound_to_rma_index', compact('serviceItems'));
    }

    public function createOutboundMultiple(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $ids = $request->service_item_ids;
        if (!$ids) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu barang terlebih dahulu.');
        }

        $serviceItems = ServiceItem::with('customer')
            ->whereIn('id', $ids)
            ->get();

        return view('shipments.admin.outbound_to_rma_bulk_create', compact('serviceItems'));
    }

    public function storeOutboundMultiple(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'resi_number' => 'required|string',
            'resi_image' => 'nullable|image',
            'notes' => 'nullable|string',
            'service_item_ids' => 'required|array',
            'service_item_ids.*' => 'exists:service_items,id',
        ]);

        // Simpan data shipment
        $shipment = Shipment::create([
            'shipment_type' => ShipmentTypeEnum::ToRMA,
            'resi_number' => $validated['resi_number'],
            'responsible_user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Upload file jika ada
        if ($request->hasFile('resi_image')) {
            $path = $request->file('resi_image')->store('resi_images');
            $shipment->resi_image_path = $path;
            $shipment->save();
        }

        foreach ($validated['service_item_ids'] as $itemId) { // perulangan untuk ID yang terpilih 
            // Pivot Relasi service item dengan shipment
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'service_item_id' => $itemId,
            ]);

            // update status lokasi pada service item menjadi InTransitToRMA
            ServiceItem::where('id', $itemId)->update([
                'location_status' => LocationStatusEnum::InTransitToRMA
            ]);
        }

        return redirect()->route('shipments.admin.outbound_to_rma.index')
                        ->with('success', 'Pengiriman berhasil disimpan.');
    }

    public function indexResiOutboundToRma()
    {
        if (!Auth::user()->isAdmin()) (abort(403, 'Akses Ditolah Hanya Admin Cabang'));

        // Ambil ID user yang login dimana hanya user tersebut yang dapat melihat datanya saja tidak dapat melihat data user lain
        $loggedInUserId = Auth::id();
        
        // Ambil Semua Service Item yang terkait dengan shipment ini
        $shipments = Shipment::where('responsible_user_id', $loggedInUserId)
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->get();

        return view('shipments.admin.resi_outbound_to_rma_index', compact('shipments'));
    }
    
    public function editResiOutboundToRma(Shipment $shipment)
    {
        // $this->authorize('update', $shipment);
        
        // Ambil semua service item yang belum dipilih oleh shipment lain
        $availableItems = ServiceItem::whereDoesntHave('shipments', function ($q) {
            $q->where('shipment_type', ShipmentTypeEnum::ToRMA);
        })->orWhereHas('shipments', function ($q) use ($shipment) {
            $q->where('shipments.id', $shipment->id);
        })->get();

        return view('shipments.admin.resi_outbound_to_rma_edit', compact('shipment', 'availableItems'));
    }

    public function updateResiOutboundToRma(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'resi_number' => 'required|string',
            'notes' => 'nullable|string',
            'resi_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_item_ids'=> 'required|array',
            'service_item_ids.*'=> 'exists:service_items,id',
        ]);

        $shipment->update([
            'resi_number' => $validated['resi_number'],
            'notes' => $validated['notes'],
        ]);

        // Update ulang gambar resi jika ada
        if ($request->hasFile('resi_image')) {
            $path = $request->file('resi_image')->store('resi_images');
            $shipment->resi_image_path = $path;
            $shipment->save();
        }

        // Ambil semua service item id lama
        $oldItemIds = $shipment->serviceItems->pluck('id')->toArray();

        // yang dibatalkan
        $removed = array_diff($oldItemIds, $validated['service_item_ids']);
        foreach ($removed as $itemId) {
            ShipmentItem::where('shipment_id', $shipment->id)
                ->where('service_item_id', $itemId)
                ->delete();

            // Kembalikan lokasi status pada service item
            ServiceItem::find($itemId)->update(['location_status' => LocationStatusEnum::AtBranch]);
        }

        // Service Item yang ditambahkan 
        $added = array_diff($validated['service_item_ids'], $oldItemIds);
        foreach ($added as $itemId) {
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'service_item_id' => $itemId,
            ]);

            // Ubah lokasi status pada service item yang ditambahkan menjadi InTransitToRMA
            ServiceItem::find($itemId)->update(['location_status' => LocationStatusEnum::InTransitToRMA]);
        }

        return redirect()->route('shipments.admin.resi_outbound_to_rma.index')->with('success', 'Data resi berhasil diperbarui.');
    }
}
