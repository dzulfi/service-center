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

class ShipmentRmaController extends Controller
{
    /**
     * Side: RMA Admin
     */
    // Menampilkan daftar resi pengiriman dari admin cabang
    public function indexInboundFromAdmin()
    {
        if (!Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya RMA Admin');

        $shipments = Shipment::with('responsibleUser', 'serviceItems')
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->where('status', ShipmentStatusEnum::Kirim)
            ->get();

        return view('shipments.rma.inbound_from_admin_index', compact('shipments'));
    }
    
    public function showInboundFromAdmin(Shipment $shipment)
    {
        // $availableItems = ServiceItem::whereDoesntHave('shipments', function ($q) {
        //     $q->where('shipment_type', ShipmentTypeEnum::ToRMA);
        // })->orWhereHas('shipments', function ($q) use ($shipment) {
        //     $q->where('shipments.id', $shipment->id);
        // })->get();

        $availableItems = $shipment->serviceItems()->get();
        // dd($availableItems);

        return view('shipments.rma.inbound_from_admin_show', compact('shipment', 'availableItems'));
    }

    public function receiveInboundFromAdmin(Shipment $shipment)
    {
        if (!Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya RMA Admin');

        if ($shipment->status === ShipmentStatusEnum::Diterima) {
            return redirect()->back()->with('error', 'Resi sudah diterima sebelumnya');
        }

        // update status shipment menjadi diterima
        $shipment->status = ShipmentStatusEnum::Diterima;
        $shipment->save();

        // Ubah status lokasi setiap barang service
        foreach ($shipment->serviceItems as $item) {
            $item->location_status = LocationStatusEnum::AtRMA;
            $item->save();
        }

        return redirect()->back()->with('success', 'Pengiriman berhasil diterima dan semua barang berada di RMA');
    }

    public function receiveInboundFromAdminDetail(Shipment $shipment)
    {
        if (!Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya RMA Admin');

        if ($shipment->status === ShipmentStatusEnum::Diterima) {
            return redirect()->back()->with('error', 'Resi sudah diterima sebelumnya');
        }

        // update status shipment menjadi diterima
        $shipment->status = ShipmentStatusEnum::Diterima;
        $shipment->save();

        // Ubah status lokasi setiap barang service
        foreach ($shipment->serviceItems as $item) {
            $item->location_status = LocationStatusEnum::AtRMA;
            $item->save();
        }

        return redirect()->route('shipments.rma.inbound_from_admin.index')->with('success', 'Pengiriman berhasil diterima dan semua barang berada di RMA');
    }

    public function indexOutboundFromRma()
    {
        if (!Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya RMA Admin');

        $serviceItems = ServiceItem::with(['customer', 'creator', 'serviceProcesses'])
            ->where('location_status', LocationStatusEnum::AtRMA)
            ->get()
            ->filter(function ($item) {
                $process = $item->latestServiceProcess;
                return $process && in_array($process->process_status, ['Selesai', 'Tidak bisa diperbaiki']);
                // return $item->latestServiceProcess && $item->latestServiceProcess->process_status  === 'Selesai';
            });

            return view('shipments.rma.outbound_from_rma_index', compact('serviceItems')); 
    }

    public function createOutboundMultipleFromRma(Request $request)
    {
        if (!Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya RMA Admin');

        $ids = $request->service_item_ids;
        if (!$ids) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu barang terlebih dahulu');
        }

        $serviceItems = ServiceItem::with('customer')
            ->whereIn('id', $ids)
            ->get();
        
        return view('shipments.rma.outbound_from_rma_buld_create', compact('serviceItems'));
    }

    public function storeOutboundMultipleFromRma(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'resi_number' => 'required|string',
            'resi_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
            'service_item_ids' => 'required|array',
            'service_item_ids.*' => 'exists:service_items,id',
        ]);

        // Simpan data shipment
        $shipment = Shipment::create([
            'shipment_type' => ShipmentTypeEnum::FromRMA,
            'resi_number' => $validated['resi_number'],
            'responsible_user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
            'status' => ShipmentStatusEnum::KirimKembali,
        ]);

        // Upload file jika ada 
        if ($request->hasFile('resi_image')) {
            $path = $request->file('resi_image')->store('resi_images/from_rma', 'public');
            $shipment->resi_image_path = $path;
            $shipment->save();
        }

        foreach ($validated['service_item_ids'] as $itemId) {
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'service_item_id' => $itemId,
            ]);

            // update status lokasi pada service item menjadi 
            ServiceItem::where('id', $itemId)->update([
                'location_status' => LocationStatusEnum::InTransitFromRMA,
            ]);
        }

        return redirect()->route('shipments.rma.outbound_from_rma.index')
            ->with('success','Pengiriman berhasil disimpan.');
    }

    public function indexResiOutboundFromRma(Request $request)
    {
        if (!Auth::user()->isRmaAdmin()) abort(403, 'Akses Ditolak Hanya RMA Admin');

        $shipments = Shipment::where('shipment_type', ShipmentTypeEnum::FromRMA)
            ->where('status', ShipmentStatusEnum::KirimKembali)
            ->get();

        return view('shipments.rma.resi_outbound_from_rma_index', compact('shipments'));
    }

    public function editResiOutboundFromRma(Shipment $shipment)
    {
        $availableItems = ServiceItem::whereHas('serviceProcesses', function ($q) {
            $q->whereIn('process_status', ['Selesai', 'Tidak bisa diperbaiki']);
        })->where(function ($q) use ($shipment) {
            $q->whereDoesntHave('shipments', function ($q2) use ($shipment) {
                $q2->where('shipment_type', ShipmentTypeEnum::FromRMA)
                    ->where('shipments.id', '!=', $shipment->id);
            });
        })->get();

        return view('shipments.rma.resi_outbound_from_rma_edit', compact('shipment', 'availableItems'));
    }

    public function updateResiOutboundFromRma(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'resi_number' => 'required|string',
            'notes'=> 'nullable|string',
            'resi_image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_item_ids'=> 'required|array',
            'service_item_ids.*'=> 'exists:service_items,id',
        ]);

        $branchIds = ServiceItem::whereIn('id', $validated['service_item_ids'])
            ->with('creator.branchOffice')
            ->get()
            ->pluck('creator.branch_office_id')
            ->unique();

        if ($branchIds->count() > 1) {
            return redirect()->back()->with('error', 'Semua service item harus berasal dari cabang yang sama.');
        }

        $shipment->update([
            'resi_number' => $validated['resi_number'],
            'notes'=> $validated['notes'],
        ]);

        // Update ulang gambar resi jika ada
        $imagePath = $shipment->resi_image_path;
        if ($request->hasFile('resi_image')) {
            if ($shipment->resi_image_path) {
                Storage::disk('public')->delete($shipment->resi_image_path);
            }
            $path = $request->file('resi_image')->store('resi_images/from_rma', 'public');
            $shipment->resi_image_path = $path;
            $shipment->save();
        }

        // Ambil semua service item id lama
        $oldItemIds = $shipment->serviceItems->pluck('id')->toArray();

        // yang dibatalkan
        $remove = array_diff($oldItemIds, $validated['service_item_ids']);
        foreach ($remove as $itemId) {
            ShipmentItem::where('shipment_id', $shipment->id)
                ->where('service_item_id', $itemId)
                ->delete();

            // kembalikan lokasi pada service item
            ServiceItem::find($itemId)->update(['location_status' => LocationStatusEnum::AtRMA]);
        }

        // service item yang ditambahkan
        $added = array_diff($validated['service_item_ids'], $oldItemIds);
        foreach ($added as $itemId) {
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'service_item_id'=> $itemId,
            ]);

            // ubah location status pada service item yang baru ditambahkan menjadi InTransitFromRMA
            ServiceItem::find($itemId)->update(['location_status' => LocationStatusEnum::InTransitFromRMA]);
        }

        return redirect()->route('shipments.rma.resi_outbound_from_rma.index')->with('success','Data resi berhasil diperbarui');
    }

    public function pdfResiOutboundFromRma(Shipment $shipment)
    {
        $shipment->load('serviceItems', 'responsibleUser');

        $pdf = PDF::loadView('shipments.rma.resi_outbound_from_rma_pdf', [
            'shipment'=> $shipment
        ])->setPaper('a4','landscape');

        return $pdf->stream('resi_shipment_from_rma_' . $shipment->id .'.pdf');
    }

    public function destroyResiOutboundFromRma($id)
    {
        $shipment = Shipment::with('serviceItems')->findOrFail($id);

        // cek apakah resi sudah diterima
        if ($shipment->status === ShipmentStatusEnum::DiterimaCabang) {
            return redirect()->route('shipments.rma.resi_outbound_from_rma.index')->with('error','Resi tidak dapat dihapus karena sudah diterima Admin Cabang');
        }

        // Kembalikan status lokasi semua service item ke AtRMA
        foreach ($shipment->serviceItems as $item) {
            $item->location_status = LocationStatusEnum::AtRMA;
            $item->save();
        }

        // Hapus relasi di pivot
        $shipment->serviceItems()->detach();

        // Hapus resi
        if ($shipment->resi_image_path) {
            Storage::disk('public')->delete($shipment->resi_image_path);
        }
        $shipment->delete();

        return redirect()->route('shipments.rma.resi_outbound_from_rma.index')->with('success','Resi berhasil dihapus dan semua service item dikembalikan ke RMA');
    }

    public function historyInboundFromAdmin()
    {
        return view('shipments.rma.inbound_from_admin_history');
    }

    public function getDataHistoryInboundFromRma(Request $request)
    {
        $query = Shipment::with(['responsibleUser', 'serviceItems'])
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->where('status', ShipmentStatusEnum::Diterima)
            ->latest()
            ->get();

        return DataTables::of($query)
            ->addColumn('image', function ($row) {
                if ($row->resi_image_path) {
                        return '<img src="' . Storage::url($row->resi_image_path) . '" alt="' . $row->resi_number . '" style="width: 50px; height: 50px; object-fit: cover;">';
                    }

                    return "Tidak ada foto";
            })
            ->addColumn('sender', function ($row) {
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
                        <a href="' . route('shipments.rma.history_inbound_from_admin.show', $row->id) . '" class="view-button">Lihat Detail</a>
                    </div>
                ';
            })
            ->rawColumns(['action', 'image'])
            ->addIndexColumn()
            ->make(true);
    }

    public function showHistoryInboundFromAdmin(Shipment $shipment)
    {
        $availableItems = $shipment->serviceItems()->get();

        return view('shipments.rma.inbound_from_admin_history_show', compact('shipment', 'availableItems'));
    }

    public function historyResiOutboundFromRma()
    {
        return view('shipments.rma.resi_outbound_from_rma_history');
    }

    public function getDataHistoryResiOutboundFromRma(Request $request) 
    {
        $query = Shipment::where('shipment_type', ShipmentTypeEnum::FromRMA)
            ->where('status', ShipmentStatusEnum::DiterimaCabang)
            ->latest()
            ->get();

        return DataTables::of($query)
            ->addColumn('image', function ($row) {
                if ($row->resi_image_path) {
                    return '<img src="' . Storage::url($row->resi_image_path) . '" alt="' . $row->resi_number . '" style="width: 50px; height: 50px; object-fit: cover;">';
                }

                return 'Tidak ada gambar';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('shipments.rma.history_resi_outbound_from_rma.show', $row->id) . '" class="view-button">Lihat</a>
                        <a href="' . route('shipments.rma.resi_outbound_from_rma.pdf', $row->id) . '" target="_blank" class="download-button">Cetak</a>
                    </div>
                ';
            })
            ->rawColumns(['image','action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function showHistoryResiOutboundFromRma(Shipment $shipment)
    {
        $availableItems = ServiceItem::whereHas('serviceProcesses', function ($q) {
            $q->whereIn('process_status', ['Selesai', 'Tidak bisa diperbaiki']);
        })
        ->whereHas('shipments', function ($q) use ($shipment) {
            $q->where('shipments.id', $shipment->id);
        })
        ->get();

        return view('shipments.rma.resi_outbound_from_rma_history_show', compact('shipment', 'availableItems'));
    }
}
