<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use App\Models\Shipment;
use App\Models\User;
use App\Enums\LocationStatusEnum;
use App\Enums\ShipmentStatusEnum;
use App\Enums\ShipmentTypeEnum;
use App\Http\Controllers\Controller;
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
            ->get();
        
        return view('shipments.admin.outbound_to_rma_index', compact('serviceItems'));
    }

    /**
     * Menampilkan form untuk membuat pengiriman (resi) dari admin ke RMA
     */
    public function createOutboundToRma(ServiceItem $serviceItem)
    {
        // hanya admin yang bisa melihat
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak');
        }

        if ($serviceItem->location_status !== LocationStatusEnum::AtBranch) {
            return redirect()->back()->with('error','Barang tidak berada di cabang atau sudah dalam proses pengiriman');;
        }

        return view('shipments.admin.outbound_to_rma_create', compact('serviceItem'));
    }

    /**
     * Menyimpan pengiriman (resi) dari Admin ke RMA
     * Membuat record Shipment dan update ServiceItem.location_status
     */
    public function storeOutboundToRma(Request $request, ServiceItem $serviceItem)
    {
        // hanya admin yang bisa melakukan ini
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak');
        }

        $request->validate([
            'resi_number' => 'required|string|max:255|unique:shipments,resi_number', // Resi harus unik
            'resi_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            'notes' => 'nullable|string',
        ]);

        if ($serviceItem->location_status !== LocationStatusEnum::AtBranch) {
            return redirect()->back()->with('error', 'Barang ini tidak berada di cabang atau sudah dalam proses pengiriman');
        }

        $imagePath = null;
        if ($request->hasFile('resi_image')) {
            $imagePath = $request->file('resi_image')->store('resi_images/to_rma', 'public'); // simpan di storage/app/public/resi_image/to_rma
        }

        // 1. buat record shipments
        Shipment::create([
            'service_item_id' => $serviceItem->id,
            'shipment_type' => ShipmentTypeEnum::ToRMA,
            'resi_number' => $request->resi_number,
            'responsible_user_id' => Auth::id(), // Admin yang mengirim
            'resi_image_path' => $imagePath,
            'status' => ShipmentStatusEnum::Kirim, // Otomatis status Kirim
            'notes' => $request->notes,
        ]);

        // 2. Update ServiceItem.location_status
        $serviceItem->update(['location_status' => LocationStatusEnum::InTransitToRMA]);

        return redirect()->route('shipments.admin.outbound_to_rma.index')->with('success', 'Barang berhasil dikirim ke RMA dengan resi: ' .$request->resi_number);
    }

    // RMA Admin side: menerima Barang dari admin
    /**
     * Menampilkan daftar pengiriman yang sedang menuju RMA Admin ('status: kirim')
     */
    public function indexInboundFromAdmin()
    {
        // Hanya RMA Admin yang bisa melihat ini
        if (!Auth::user()->isRmaAdmin()) {
            abort(403, 'Akses Ditolak');
        }

        // Ambil pengiriman yang tipenya To_RMA dan status Kirim
        $shipments = Shipment::with(['serviceItem.customer', 'responsibleUser'])
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->where('status', ShipmentStatusEnum::Kirim)
            ->get();

        return view('shipments.rma.inbound_from_admin_index', compact('shipments'));
    }

    /**
     * mengubah status pengiriman menjadi 'Diterima' oleh RMA Admin
     * @param \App\Models\Shipment $shipment
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function receiveInboundFromAdmin(Shipment $shipment) 
    // {
    //     // Hanya RMA Admin yang bisa melakukan ini
    //     if (!Auth::user()->isRmaAdmin()) {
    //         abort(403, 'Akses Ditolak');
    //     }

    //     // Pastikan ini pengiriman ke RMA dan statusnya masih kirim
    //     if ($shipment->shipment_type !== ShipmentTypeEnum::ToRMA || $shipment->status !== ShipmentStatusEnum::Kirim) {
    //         return redirect()->back()->with('error','Pengiriman ini tidak valid untuk diterima RMA');
    //     }

    //     // 1. Update status Shipment
    //     $shipment->update([
    //         'status' => ShipmentStatusEnum::Diterima,
    //         'responsible_user_id' => Auth::id(), // RMA yang menerima
    //     ]);

    //     // 2. Update ServiceItem.location_status
    //     $shipment->serviceItem->update(['location_status' => LocationStatusEnum::AtRMA]);

    //     return redirect()->route('shipments.rma.inbound_from_admin.index')->with('success', 'Barang "' . $shipment->serviceItem->name . '" berhasil diterima.');
    // }

    // RMA Admin side: Mengirim barang kembali ke admin
    /**
     * Menampilkan daftar barang service di RMA yang sudah selesai dan siap dikirim
     */
    public function indexOutboundFromRma()
    {
        // hanya RMA Admin yang bisa melihat ini
        if (!Auth::user()->isRmaAdmin()) {
            abort(403, 'Akses Ditolak');
        }

        // Ambil service Items yang sudah Selesai (proses terakhirnya) Dan lokasinya masih di RMA
        $serviceItems = ServiceItem::with(['customer', 'serviceProcesses', 'creator'])
            ->where('location_status', LocationStatusEnum::AtRMA)
            ->get()
            ->filter(function ($item) {
                return $item->latestServiceProcess && $item->latestServiceProcess->process_status  === 'Selesai';
            });
        
        return view('shipments.rma.outbound_from_rma_index', compact('serviceItems'));
    }

    /**
     * Menampilkan form untuk membuat (resi) dari RMA Admin ke Admin
     */
    public function createOutboundFromRma(ServiceItem $serviceItem)
    {
        // Hanya RMA yang bisa melihat ini
        if (!Auth::user()->isRmaAdmin()) {
            abort(403, 'Akses Ditolak');
        }

        // pastikan service item memang di At_RMA dan sudah selesai
        if ($serviceItem->location_status !== LocationStatusEnum::AtRMA || !$serviceItem->latestServiceProcess || $serviceItem->latestServiceProcess->process_status !== 'Selesai') {
            return redirect()->back()->with('error', 'Barang ini tidak siap untuk dikirim kembali');
        }

        return view('shipments.rma.outbound_from_rma_create', compact('serviceItem'));
    }

    /**
     * Menyimpan pengiriman (resi) dari RMA ke Admin
     * membuat record Shipment dan update ServiceItem.location_status.
     */
    public function storeOutboundFromRma(Request $request, ServiceItem $serviceItem)
    {
        // Hanya RMA yang bisa melakukan ini
        if (!Auth::user()->isRmaAdmin()) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'resi_number' => 'required|string|max:255|unique:shipments,resi_number',
            'resi_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($serviceItem->location_status !== LocationStatusEnum::AtRMA || !$serviceItem->latestServiceProcess || $serviceItem->latestServiceProcess->process_status !== 'Selesai'
            ) {
            return redirect()->back()->with('error', 'Barang ini tidak siap untuk dikirim kembali kantor cabang');
        }

        $imagePath = null;
        if ($request->hasFile('resi_image')) {
            $imagePath = $request->file('resi_image')->store('resi_images/from_rma', 'public');
        }

        Shipment::create([
            'service_item_id' => $serviceItem->id,
            'shipment_type' => ShipmentTypeEnum::FromRMA,
            'resi_number' => $request->resi_number,
            'responsible_user_id' => Auth::id(), // RMA yang mengirim
            'resi_image_path' => $imagePath,
            'status' => ShipmentStatusEnum::KirimKembali, // Otomatis status Kirim Kembali
            'notes' => $request->notes,
        ]);

        $serviceItem->update(['location_status' => LocationStatusEnum::InTransitFromRMA]);

        return redirect()->route('shipments.rma.outbound_from_rma.index')->with('success', 'Barang berhasil dikirim kembali ke cabang dengan resi: ' . $request->resi_number);
    }

    // Admin Side: Menerima Barang dari RMA
    /**
     * Menampilkan daftar pengiriman yang sedang menuju Admin (status 'Kirim Kembali').
     */
    public function indexInboundFromRma()
    {
        // hanya admin yang bisa melihat ini
        if (!Auth::user()->isAdmin()) {
            abort(403,'Akses Ditolak');
        }

        // mengambil ID user yang sedang login beserta branch_id nya
        $loggedInUserId = Auth::user();
        // dd($loggedInUserId);
        $loggedInUserBranchOfficeId = $loggedInUserId->branch_office_id; // ID cabang user yang login

        // Ambil pengiriman yang tipenya from_rma dan statusnya 'Kirim Kembali'
        // dan service item terkaitnya dibuat oleh user dari cabang yang sama dengan admin yang login
        $shipments = Shipment::with([
                'serviceItem.customer', 
                'responsibleUser', 
                'serviceItem.serviceProcesses',
                'serviceItem.creator.branchOffice' // Eager load branch office dari creator untuk filter
            ])
            ->where('shipment_type', ShipmentTypeEnum::FromRMA)
            ->where('status', ShipmentStatusEnum::KirimKembali)
            ->whereHas('serviceItem.creator', function ($query) use ($loggedInUserBranchOfficeId) {
                // filter shipments dimana creator (pembuat service item), memiliki branch_office_id yang sama dengan cabang user yang login.
                $query->where('branch_office_id', $loggedInUserBranchOfficeId);
            })
            ->get();

        return view('shipments.admin.inbound_from_rma_index', compact('shipments'));
    }

    /**
     * mengubah status pengiriman menjadi 'Diterima Cabang' oleh admin
     */
    public function receiveInboundFromRma(Shipment $shipment)
    {
        // hanya admin yang bisa melakukan ini
        if (!Auth::user()->isAdmin()) {
            abort(403,'Akses Ditolak');
        }

        // pastikan ini dari RMA dan statusnya masih Kirim Kembali
        if ($shipment->shipment_type !== ShipmentTypeEnum::FromRMA || $shipment->status !== ShipmentStatusEnum::KirimKembali) {
            return redirect()->back()->with('error','Pengiriman ini tidak valid untuk diterima Cabang.');
        }

        // 1. Admin mengupdate status 
        $shipment->update([
            'status' => ShipmentStatusEnum::DiterimaCabang,
            'responsible_user' => Auth::id(), // Admin yang menerima
        ]);

        // 2. update serviceItem.location_status
        $shipment->serviceItem->update(['location_status' => LocationStatusEnum::AtBranch]); // Atau ReadyForPickup jika ada langkah pengambilan Mitra Bisnis

        return redirect()->route('shipments.admin.inbound_from_rma.index')->with('success', 'Barang "' . $shipment->serviceItem->name . '" berhasil diterima cabang');
    }

    // Metode show (untuk melihat detail shipment)
    public function show(Shipment $shipment)
    {
        $shipment->load(['serviceItem.customer', 'responsibleUser']);
        return view('shipments.show', compact('shipment'));
    }

    /**
     * MULTI CHOSE SERVICE ITEM
     */
    public function createBulkOutboundToRma(Request $request)
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $serviceItemIds = $request->input('service_item_ids', []);
        $serviceItems = ServiceItem::whereIn('id', $serviceItemIds)
            ->where('location_status', LocationStatusEnum::AtBranch)
            ->get();
        
        return view('shipments.admin.bulk_outbound_to_rma_create', compact('serviceItems'));
    }

    public function storeBulkOutboundToRma(Request $request)
    {
        if (!Auth::user()->isAdmin()) abort(403, 'Akses Ditolak Hanya Admin Cabang');

        $request->validate([
            'service_item_ids' => 'required|array|min:1',
            'resi_number' => 'required|string|max:255|unique:shipments,resi_number',
            'resi_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('resi_image')) {
            $imagePath = $request->file('resi_image')->store('resi_image/to_rma', 'public');
        }

        $shipment = Shipment::create([
            'shipment_type' => ShipmentTypeEnum::ToRMA,
            'resi_number' => $request->resi_number,
            'responsible_user_id' => Auth::id(),
            'resi_image_path' => $imagePath,
            'status' => ShipmentStatusEnum::Kirim,
            'notes' => $request->notes,
        ]);

        foreach ($request->service_item_ids as $itemId) {
            $item = ServiceItem::findOrFail($itemId);
            $shipment->serviceItems()->attach($itemId);
            $item->update(['location_status' => LocationStatusEnum::InTransitFromRMA]);
        }

        return redirect()->route('shipments.admin.outbound_to_rma.index')->with('success','Pengiriman berhasil dibuat');
    }

    // penerimaan seluruh isi shipment oleh Admin RMA
    public function receiveInboundFromAdmin(Shipment $shipment)
    {
        if (!Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya Admin Cabang');

        if ($shipment->shipment_type !== ShipmentTypeEnum::ToRMA || $shipment->status !== ShipmentStatusEnum::Kirim) {
            return redirect()->back()->with('error','Pengiriman tidak valid');
        }

        foreach ($shipment->serviceItems as $item) {
            $item->update(['location_status' => LocationStatusEnum::AtRMA]);
        }

        $shipment->update([
            'status' => ShipmentStatusEnum::Diterima,
            'responsible_user_id' => Auth::id(),
        ]);

        return back()->with('success','Semua Barang telah diterima oleh RMA');
    }

    // Cetak SPJ
    public function printSPJ(Shipment $shipment)
    {
        $shipment->load([
            'serviceItems.customer', 
            'serviceItems.creator', 
            'serviceItems.latestServiceProcess',
        ]);

        $pdf = \PDF::loadView('shipments.admin.spj_pdf', compact('shipment'));
        return $pdf->download('SPJ-' . $shipment->resi_number . '.pdf');
    }

    // Edit Pengiriman (Jika belum diterima)
    public function editShipment(Shipment $shipment)
    {
        if (!Auth::user()->isAdmin()) abort(403, 'Akses Ditolah Hanya Admin Cabang');

        if ($shipment->status !== ShipmentStatusEnum::Kirim) {
            return redirect()->back()->with('error','Pengiriman sudah diterima tidak bisa dirubah');
        }

        $shipment->load('serviceItems');
        $serviceItems = ServiceItem::where('location_status', LocationStatusEnum::AtBranch)->get();

        return view('shipments.admin.edit_shipment', compact('shipment','serviceItems'));
    }

    public function updateShipment(Request $request, Shipment $shipment)
    {
        if (!Auth::user()->isAdmin()) abort(403, 'Akses Ditolak Hanya Admin Cabang');

        if ($shipment->status !== ShipmentStatusEnum::Kirim) {
            return redirect()->back()->with('error','Pengiriman Sudah diterima dan tidak bisa dirubah');
        }

        $request->validate([
            'service_item_ids'=> 'required|array|min:1',
            'notes' => 'nullable|string'
        ]);

        $shipment->update([
            'notes' => $request->notes,
        ]);

        // Reset sebelumnya
        foreach ($request->service_item_ids as $itemId) {
            $item = ServiceItem::findOrFail($itemId);
            $shipment->serviceItems()->attach($itemId);
            $item->update(['location_status' => LocationStatusEnum::InTransitToRMA]);
        }

        return redirect()->route('shipments.admin.outbound_to_rma.index')
            ->with('success', 'Pengiriman berhasil diperbarui.');
    }
}
