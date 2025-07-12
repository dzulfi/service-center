<?php

namespace App\Http\Controllers;

use App\Enums\StockTypeEnum;
use App\Http\Controllers\Controller;
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

        return view('stock_spareparts.stock_in', compact('sparepart'));
    }

    public function storeStockIn(Request $request, Sparepart $sparepart)
    {
        if (!Auth::user()->isRma()) {
            abort(403, 'Akses Ditolak');
        }

        $request->validate([
            'quantity'=> 'required|integer',
        ]);

        StockSparepart::create([
            'sparepart_id' => $sparepart->id,
            'stock_type' => StockTypeEnum::In,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('spareparts.index')->with('success','Item berhasil ditambahkan');
    }
}
