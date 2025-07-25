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
        if (!Auth::user()->isRma()) {
            abort(403, "Akses Ditolak");
        }

        return view('stock_spareparts.stock_in.create', compact('sparepart'));
    }

    public function storeStockIn(Request $request, Sparepart $sparepart)
    {
        if (!Auth::user()->isRma()) {
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
        if (!Auth::user()->isRma()) {
            abort(403, 'Akses Ditolak');
        }

        return view('stock_spareparts.stock_out.minus', compact('sparepart'));
    }

    public function storeStockOutMinus(Request $request, Sparepart $sparepart)
    {
        if (!Auth::user()->isRma()) {
            abort(403   , 'Akses Ditolak');
        }

        $request->validate([
            'quantity'=> 'required|integer|min:1',
        ]);

        StockSparepart::create([
            'sparepart_id' => $sparepart->id,
            'stock_type' => StockTypeEnum::Out,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('spareparts.index')->with('success','Sparepart berhasil dikurangi');
    }



    public function stockOut(ServiceItem $serviceItem)
    {
        if (!Auth::user()->isRma()) {
            abort(403, 'Akses Ditolah');
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

        StockSparepart::create([
            'sparepart_id' => $request->sparepart_id,
            'service_item_id' => $serviceItem->id,
            'stock_type' => StockTypeEnum::Out,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('service_processes.index')->with('success','Sparepart berhasil di gunakan');
    }
}
