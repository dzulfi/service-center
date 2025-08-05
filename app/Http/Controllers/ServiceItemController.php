<?php

namespace App\Http\Controllers;

use App\Enums\LocationStatusEnum;
use App\Enums\ShipmentStatusEnum;
use App\Enums\ShipmentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ItemType;
use App\Models\Merk;
use App\Models\ServiceItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * menampilkan daftar barang service yang hanya dibuat oleh admin cabang tersebut
     */
    public function index()
    {
        // mengambil ID user yang sedang login
        $loggedInUserId = Auth::id();
        
        $serviceItems = ServiceItem::with('customer', 'creator', 'serviceProcesses') // load relasi yang dibutuhkan untuk tampilan dan status
            ->where('created_by_user_id', $loggedInUserId)
            ->paginate(10); // Load relasi customer
            
        return view('service_items.index', compact('serviceItems'));
    }

    /**
     * Metode untuk menampilkan daftar semua barang service untuk dilihat Role Developer dan Superadmin
     */
    public function indexAllServiceItems()
    {
        $serviceItems = ServiceItem::with(['customer', 'creator'])->paginate(10);
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

        $customers = Customer::all(); // mengambil semua Mitra Bisnis untuk di dropdown
        $itemTypes = ItemType::all();
        $merks = Merk::all();
        return view('service_items.create', compact('customers', 'itemTypes', 'merks'));
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
            'serial_number' => 'required|string|max:255',
            'analisa_kerusakan' => 'required|string',
            // 'item_type_id' => 'required|exists:item_types,id',
            'item_type_id'=> 'required|string|max:255',
            // 'merk_id'=> 'required|exists:merks,id',
            'merk_id' => 'required|string|max:255',
        ]);

        // Logika pembuatan generate kode service
        $user = Auth::user();
        // mengabil kode branch office
        $branchOfficeCode = $user->branchOffice->code;

        // konversi kode untuk date
        $datePart = Carbon::now()->format('ymd');

        // Cari ServiceItem dengan kode TERTINGGI secara keseluruhan (global)
        // Ini akan digunakan untuk mengekstrak nomor urut tertinggi
        // Asumsi: Kode selalu dalam format fixed-length [BranchCode][YYMMDD][NomorUrut]
        $latestServiceItem = ServiceItem::orderByDesc('code')->first();

        $sequentialNumber = 1; // Nomor urut default jika belum ada item hari ini dari cabang ini
        if ($latestServiceItem) {
            // Ambil 6 digit terakhir dari kode urut, konversi ke integer, lalu tambahkan 1
            $lastCode = $latestServiceItem->code;
            $latestSequentialNumber = (int) substr($lastCode, -5);
            $sequentialNumber = $latestSequentialNumber + 1;
        }

        // Format nomor urut dengan 6 digit (000001, 000010)
        $formattedSequentialNumber = str_pad($sequentialNumber, 5, '0', STR_PAD_LEFT);

        // Gabungkan semua bagian untuk mendapatkan kode final
        $generatedCode = $branchOfficeCode . $datePart . $formattedSequentialNumber;

        while (ServiceItem::where('code', $generatedCode)->exists()) {
            $sequentialNumber++;
            $formattedSequentialNumber = str_pad($sequentialNumber, 5, '0', STR_PAD_LEFT);
            $generatedCode = $branchOfficeCode . $datePart . $formattedSequentialNumber;
        }

        // DYNAMIC OPTIONS ITEM TYPES
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka dibuatkan
        $itemTypeInput = $request->input('item_type_id'); // request input dari user 
        // Cek apakah input berupa ID numerik (dari dropdown)
        if (is_numeric($itemTypeInput)) {
            $itemTypeId = $itemTypeInput; // cek jika ada dan berupa ID maka tidka perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $itemType = ItemType::firstOrCreate([
                'type_name' => $itemTypeInput
            ]);
            $itemTypeId = $itemType->id;
        }

        // DYNAMIC OPTIONS MERKS
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka dibuatkan
        $merkInput = $request->input('merk_id'); // request input dari user 
        // Cek apakah input berupa ID numerik (dari dropdown)
        if (is_numeric($merkInput)) {
            $merkId = $merkInput; // cek jika ada dan berupa ID maka tidka perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $merk = Merk::firstOrCreate([
                'merk_name' => $merkInput
            ]);
            $merkId = $merk->id;
        }

        $serviceItem = ServiceItem::create([
            'customer_id' => $request->customer_id,
            'name' => $request->name,
            'serial_number' => $request->serial_number,
            'code' => $generatedCode, // Hasil penggabungan kode.
            'analisa_kerusakan' => $request->analisa_kerusakan,
            'created_by_user_id' => Auth::id(), // Simpan ID user yang sedang login
            'location_status' => LocationStatusEnum::AtBranch,
            // 'item_type_id' => $request->item_type_id,
            'item_type_id' => $itemTypeId,
            'merk_id' => $merkId,
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
        // $itemTypes = ItemType::all();
        $merks = Merk::all();

        $serviceItem->load('itemType');

        return view('service_items.edit', compact(
            'serviceItem', 
            'customers', 
            // 'itemTypes', 
            // 'merks'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'analisa_kerusakan' => 'nullable|string',
            'item_type_id' => 'required|string|max:255',
            'merk_id'=> 'required|string|max:255',
        ]);

        // $serviceItem->update($request->all());

        // DYNAMIC OPTION ITEM TYPES
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka akan dibuatkan data item type baru
        $itemTypeInput = $request->input('item_type_id');
        // Cek apakah input berupa ID numerik (dropdown)
        if (is_numeric($itemTypeInput)) {
            $itemTypeId = $itemTypeInput; // cek jika ada dan berupa ID maka tidak perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $itemType = ItemType::firstOrCreate([
                'type_name' => $itemTypeInput,
            ]);
            $itemTypeId = $itemType->id;
        }

        // DYNAMIC OPTION MERKS
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka akan dibuatkan data merk baru
        $merkInput = $request->input('merk_id');
        // Cek apakah input berupa ID numerik (dropdown)
        if (is_numeric($merkInput)) {
            $merkId = $merkInput; // cek jika ada dan berupa ID maka tidak perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $merk = Merk::firstOrCreate([
                'merk_name' => $merkInput,
            ]);
            $merkId = $merk->id;
        }

        $serviceItem->update([
            'customer_id' => $request->customer_id,
            'name'=> $request->name,
            'serial_number' => $request->serial_number,
            'analisa_kerusakan' => $request->analisa_kerusakan,
            'item_type_id' => $itemTypeId,
            'merk_id' => $merkId,
        ]);

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
