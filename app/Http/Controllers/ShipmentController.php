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
use Illuminate\Support\Facades\Storage;
use PDF;
use Yajra\DataTables\Facades\DataTables;

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
            // ->where('location_status', LocationStatusEnum::AtBranch)
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
            'resi_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $path = $request->file('resi_image')->store('resi_images/to_rma', 'public');
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
            ->where('status', ShipmentStatusEnum::Kirim)
            ->get();

        return view('shipments.admin.resi_outbound_to_rma_index', compact('shipments'));
    }
    
    public function editResiOutboundToRma(Shipment $shipment)
    {
        $loggedInUserId = Auth::id();
        
        // Ambil semua service item yang belum dipilih oleh shipment lain
        $availableItems = ServiceItem::where('created_by_user_id', $loggedInUserId)
        ->whereDoesntHave('shipments', function ($q) {
            $q->where('shipment_type', ShipmentTypeEnum::ToRMA);
        })->orWhereHas('shipments', function ($q) use ($shipment) {
            $q->where('shipments.id', $shipment->id);
        })->get();

        return view('shipments.admin.resi_outbound_to_rma_edit', compact('shipment', 'availableItems'));
    }

    // Update Resi Admin Cabang ke RMA
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
        $imagePath = $shipment->resi_image_path; // Pertahankan gambar lama jika tidak ada perubahan
        if ($request->hasFile('resi_image')) {
            if($shipment->resi_image_path) {
                Storage::disk('public')->delete($shipment->resi_image_path);
            }
            $path = $request->file('resi_image')->store('resi_images/to_rma', 'public');
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

    // Cetak Resi Admin Cabang ke RMA
    public function pdfResiOutboundToRma(Shipment $shipment)
    {
        $shipment->load('serviceItems', 'responsibleUser'); // eager load user & items

        $pdf = PDF::loadView('shipments.admin.resi_outstanding_to_rma_pdf', [
            'shipment' => $shipment
        ])->setPaper('a4','landscape');

        return $pdf->stream('resi_shipment_to_rma_' . $shipment->id . '.pdf');
    }

    // Hapus Resi Admin Cabang ke RMA
    public function destroyResiOutstandingToRma($id)
    {
        $shipment = Shipment::with('serviceItems')->findOrFail($id);

        // cek apakah resi sudah diterima
        if ($shipment->status === ShipmentStatusEnum::Diterima) {
            return redirect()->route('shipments.admin.resi_outbound_to_rma.index')
                ->with('error', 'Resi Tidak dapat dihapus karena sudah diterima Admin RMA');
        }

        // Kembalikan status lokasi semua service item ke AtBranch
        foreach ($shipment->serviceItems as $item) {
            $item->location_status = LocationStatusEnum::AtBranch;
            $item->save();
        }

        // Hapus relasi di pivot 
        $shipment->serviceItems()->detach();

        // Hapus resi 
        if ($shipment->resi_image_path) {
            Storage::disk('public')->delete($shipment->resi_image_path);
        }
        $shipment->delete();

        return redirect()->route('shipments.admin.resi_outbound_to_rma.index')->with('success','Resi berhasil dihapus dan semua service item dikembalikan ke kantor cabang');
    }

    public function indexInboundFromRma()
    {
        if (Auth::user()->isRmaAdmin()) abort(403, 'Aksi Ditolak Hanya RMA Admin');

        $userBranchId = Auth::user()->branch_office_id;

        $shipments = Shipment::with('responsibleUser', 'serviceItems')
            ->where('shipment_type', ShipmentTypeEnum::FromRMA)
            ->where('status', ShipmentStatusEnum::KirimKembali)
            ->get()
            // Filter hanya shipment yang punya service item untuk cabang yang sama dengan user
            ->filter (function ($shipment) use ($userBranchId) {
                return $shipment->serviceItems->contains(function ($item) use ($userBranchId) {
                    return $item->creator && $item->creator->branch_office_id == $userBranchId;
                });
            });

        return view('shipments.admin.inbound_from_rma_index', compact('shipments'));
    }

    public function showInboundFromRma(Shipment $shipment)
    {
        // $availableItems = ServiceItem::whereHas('serviceProcesses', function ($q) {
        //     $q->whereIn('process_status', ['Selesai', 'Tidak bisa diperbaiki']);
        // })->where(function ($q) use ($shipment) {
        //     $q->whereDoesntHave('shipments', function ($q2) use ($shipment) {
        //         $q2->where('shipment_type', ShipmentTypeEnum::FromRMA)
        //             ->where('stattus', ShipmentStatusEnum::KirimKembali)
        //             ->where('shipments.id', '!=', $shipment->id);
        //     });
        // })->get();

        $availableItems = ServiceItem::whereHas('serviceProcesses', function ($q) {
            $q->whereIn('process_status', ['Selesai', 'Tidak bisa diperbaiki']);
        })
        ->whereHas('shipments', function ($q) use ($shipment) {
            $q->where('shipments.id', $shipment->id);
        })
        ->get();

        return view('shipments.admin.inbound_from_rma_show', compact('shipment', 'availableItems'));
    }

    public function receiveInboundFromRma(Shipment $shipment)
    {
        if (Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya RMA Admin');

        if ($shipment->status === ShipmentStatusEnum::DiterimaCabang) {
            return redirect()->back()->with('error', 'Resi sudah diterima sebelumnya');
        }

        // Update status shipment menjadi diterima
        $shipment->status = ShipmentStatusEnum::DiterimaCabang;
        $shipment->save();

        // ubah status lokasi setiap service item
        foreach ($shipment->serviceItems as $item) {
            $item->location_status = LocationStatusEnum::ReadyForPickup;
            $item->save();
        }

        return redirect()->back()->with('success','Pengiriman berhasu diterima dan semua barang kembali ke admin cabang');
    }

    public function historyResiOutboundToRma()
    {
        if (!Auth::user()->isAdmin()) (abort(403, 'Akses Ditolah Hanya Admin Cabang'));

        // Ambil ID user yang login dimana hanya user tersebut yang dapat melihat datanya saja tidak dapat melihat data user lain
        $loggedInUserId = Auth::id();
        
        // Ambil Semua Service Item yang terkait dengan shipment ini
        $shipments = Shipment::where('responsible_user_id', $loggedInUserId)
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->where('status', ShipmentStatusEnum::Diterima)
            ->latest()
            ->get();

        return view('shipments.admin.resi_outbound_to_rma_history', compact('shipments'));
    }

    public function getDataHistoryResiOutboundToRma(Request $request)
    {
        $loggedInUserId =  Auth::id();

        $query = Shipment::where('responsible_user_id', $loggedInUserId)
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->where('status', ShipmentStatusEnum::Diterima)
            ->latest()
            ->get();

        return DataTables::of($query)
            ->addColumn('image', function ($row) {
                if ($row->resi_image_path) {
                    return '
                        <img src="' . Storage::url($row->resi_image_path) .'" alt="' . $row->resi_number . '" style="width: 50px; height: 50px; object-fit: cover;">
                    ';
                }
                return "Tidak ada Foto";
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('shipments.admin.history_resi_outbound_to_rma.show', $row->id) . '" class="view-button">Lihat</a>
                        <a href="' . route('shipments.admin.resi_outbound_to_rma.pdf', $row->id) . '" target="_blank" class="download-button">Cetak</a>
                    </div>
                ';
            })
            ->rawColumns(['action', 'image'])
            ->addIndexColumn()
            ->make(true);
    }

    public function historyShowResiOutboundToRma(Shipment $shipment)
    {
        // $availableItems = ServiceItem::whereDoesntHave('shipments', function ($q) {
        //     $q->where('shipment_type', ShipmentTypeEnum::ToRMA);
        // })->orWhereHas('shipments', function ($q) use ($shipment) {
        //     $q->where('shipments.id', $shipment->id);
        // })->get();

        $availableItems = $shipment->serviceItems()->get();

        return view('shipments.admin.resi_outbound_to_rma_history_show', compact('shipment', 'availableItems'));
    }

    public function historyInboundFromRma()
    {
        return view('shipments.admin.inbound_from_rma_history');
    }

    public function getDataHistoryInboundFromRma(Request $request)
    {
        $userBranchId = Auth::user()->branch_office_id;

        $query = Shipment::with(['responsibleUser', 'serviceItems'])
            ->where('shipment_type', ShipmentTypeEnum::FromRMA)
            ->where('status', ShipmentStatusEnum::DiterimaCabang)
            ->latest()
            ->get()
            // Filter hanya shipment yang punya service item untuk cabang yang sama dengan user
            ->filter(function ($shipment) use ($userBranchId) {
                return $shipment->serviceItems->contains(function ($item) use ($userBranchId) {
                    return $item->creator && $item->creator->branch_office_id == $userBranchId;
                });
            });

            return DataTables::of($query)
                ->addColumn('image', function ($row) {
                    if ($row->resi_image_path) {
                        return '<img src="' . Storage::url($row->resi_image_path) . '" alt="' . $row->resi_number . '" style="width: 50px; height: 50px; object-fit: cover;">';
                    }

                    return "Tidak ada foto";
                })
                ->addColumn('admin', function ($row) {
                    return $row->responsibleUser ? $row->responsibleUser->name : '-';
                })
                ->addColumn('branch_office', function ($row) {
                    return $row->responsibleUser->branchOffice ? $row->responsibleUser->branchOffice->name : '-';
                })
                ->addColumn('date_delivery', function ($row) {
                    return $row->created_at->format('d M Y H:i');
                })
                ->addColumn('date_accepted', function ($row) {
                    return $row->updated_at->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="actions">
                            <a href="' . route('shipments.admin.history_inbound_from_rma.show', $row->id) . '" class="view-button">Lihat Detail</a>
                        </div>
                    ';
                })
                ->rawColumns(['image', 'action'])
                ->addIndexColumn()
                ->make(true);
    }

    public function showHistoryInboundFromRma(Shipment $shipment) 
    {
        $availableItems = ServiceItem::whereHas('serviceProcesses', function ($q) {
            $q->whereIn('process_status', ['Selesai', 'Tidak bisa diperbaiki']);
        })
        ->whereHas('shipments', function ($q) use ($shipment) {
            $q->where('shipments.id', $shipment->id);
        })
        ->get();

        return view('shipments.admin.inbound_from_rma_history_show', compact('shipment', 'availableItems'));
    }
}
