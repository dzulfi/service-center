<?php

namespace App\Http\Controllers;

use App\Enums\StockTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use App\Models\ServiceProcess;
use App\Models\Sparepart;
use App\Models\StockSparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockSparepartController extends Controller
{
    public function stockIn(Sparepart $sparepart)
    {
        if (!Auth::user()->isRma() && !Auth::user()->isRmaAdmin()) {
            abort(403, "Akses Ditolak");
        }

        return view('stock_spareparts.stock_in.create', compact('sparepart'));
    }

    public function storeStockIn(Request $request, Sparepart $sparepart)
    {
        if (!Auth::user()->isRma() && !Auth::user()->isRmaAdmin()) {
            abort(403, 'Akses Ditolak');
        }

        $request->validate([
            'quantity'=> 'required|integer|min:1',
        ]);

        StockSparepart::create([
            'sparepart_id' => $sparepart->id,
            'stock_type' => StockTypeEnum::In,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('spareparts.index')->with('success','Item berhasil ditambahkan');
    }

    public function stockOutMinus(Sparepart $sparepart)
    {
        if (!Auth::user()->isRma() && !Auth::user()->isRmaAdmin()) {
            abort(403, 'Akses Ditolak');
        }

        return view('stock_spareparts.stock_out.minus', compact('sparepart'));
    }

    public function storeStockOutMinus(Request $request, Sparepart $sparepart)
    {
        if (!Auth::user()->isRma() && !Auth::user()->isRmaAdmin()) {
            abort(403   , 'Akses Ditolak');
        }

        $request->validate([
            'quantity'=> 'required|integer|min:1',
        ]);

        $currentStock = $sparepart->getStock();

        if ($request->quantity > $currentStock) {
            return redirect()->back()->withInput()->with('error', 'Jumlah yang dikurangi melebihi stok saat ini (' . $currentStock . ')');
        }

        StockSparepart::create([
            'sparepart_id' => $sparepart->id,
            'stock_type' => StockTypeEnum::Out,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('spareparts.index')->with('success','Sparepart berhasil dikurangi');
    }

    public function stockOut(ServiceItem $serviceItem)
    {
        if (!(Auth::user()->isRma() || Auth::user()->isDeveloper() || Auth::user()->isSuperAdmin())) {
            abort(403, 'Akses Ditolak');
        }

        $spareparts = Sparepart::get(); // menambil semua data sparepart

        return view('stock_spareparts.stock_out.index', compact('serviceItem', 'spareparts'));
    }

    public function storeStockOut(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer|min:1',
        ]); 

        // ambil model sparepart
        $sparepart = Sparepart::findOrFail($request->sparepart_id);
        $currentStock = $sparepart->getStock();

        if ($request->quantity > $currentStock) {
            return redirect()->back()->withInput()->with('error', 'Jumlah yang digunakan melebihi stok saat ini (' . $currentStock . ')');
        }

        StockSparepart::create([
            'sparepart_id' => $request->sparepart_id,
            'service_item_id' => $serviceItem->id,
            'stock_type' => StockTypeEnum::Out,
            'quantity' => $request->quantity,
        ]);

        if (Auth::user()->isDeveloper() || Auth::user()->isSuperAdmin()) {
            return redirect()->route('activity.service_processes.index')->with('success','Sparepart berhasil di gunakan');
        } else {
            return redirect()->route('service_processes.work_on', $serviceItem->id)->with('success','Sparepart berhasil di gunakan');
        }
    }

    public function stockReturn(ServiceItem $serviceItem)
    {
        if (!(Auth::user()->isRma() || Auth::user()->isDeveloper() || Auth::user()->isSuperAdmin())) abort(403, 'Akses Ditolak Hanya RMA');

        $spareparts = Sparepart::get();

        return view('stock_spareparts.stock_in.return_stock', compact('serviceItem','spareparts'));
    }

    public function storeStockReturn(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $sparepartId = $request->sparepart_id;
        $quantityReturn = $request->quantity;

        // Hitung jumlah out untuk sparepart tertentu pada service item ini
        $totalOut = StockSparepart::where('sparepart_id', $sparepartId)
            ->where('service_item_id', $serviceItem->id)
            ->where('stock_type', StockTypeEnum::Out)
            ->sum('quantity');

        // Hitung Jumlah pengembalian (IN) sebelumnya untuk sparepart tersebut
        $totalReturned = StockSparepart::where('sparepart_id', $sparepartId)
            ->where('service_item_id', $serviceItem->id)
            ->where('stock_type', StockTypeEnum::In)
            ->sum('quantity');

        $maxReturnable = $totalOut - $totalReturned;

        if ($quantityReturn > $maxReturnable) {
            return redirect()->back()->withInput()->with('error', 'Jumlah pengembalian melebihi yang pernah digunakan. Maksimal yang dapat dikembalikan: ' . $maxReturnable);
        }

        StockSparepart::create([
            'sparepart_id' => $sparepartId,
            'service_item_id' => $serviceItem->id,
            'stock_type' => StockTypeEnum::In,
            'quantity' => $quantityReturn,
        ]);

        if (Auth::user()->isDeveloper() || Auth::user()->isSuperAdmin()) {
            return redirect()->route('activity.service_processes.index')->with('success','Sparepart berhasil di gunakan');
        } else {
            return redirect()->route('service_processes.work_on', $serviceItem->id)->with('success', 'Sparepart berhasil dikembalikan ke stok');
        }
    }
}
