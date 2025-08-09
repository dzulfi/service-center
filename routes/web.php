<?php

use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\ServiceProcessController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentRmaController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\StockSparePartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchOfficeController;
use App\Models\Customer;
use App\Models\ItemType;
use App\Models\Merk;
use App\Models\StockSparePart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
// use DataTables;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // route umum untuk sidebar (semua bisa akses halaman ini, tetapi konten didalamnya dibatasi)
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');

    // Hanya Admin
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Server Side Customer DataTable
        Route::get('customers/data', [CustomerController::class, 'getDataCustomer'])->name('customers.data');

        // CRUD Customer
        Route::resource('customers', CustomerController::class);
        
        // CRUD Service Item
        Route::resource('service_items', ServiceItemController::class);
        
        // API Dynamic Option Item Type
        Route::get('/api/item-types', function (Request $request) {
            $search = $request->get('term');
            $itemTypes = ItemType::query()
                ->select('id', 'type_name')
                ->when($search, function ($query, $search) {
                    return $query->where('type_name', 'like', '%'. $search .'%');
                })
                ->limit(10)
                ->get();
            return response()->json($itemTypes);
        });

        // API Dynamic Option Merks
        Route::get('/api/merks', function (Request $request) {
            $search = $request->get('term');
            $merks = Merk::query()
                ->select('id', 'merk_name')
                ->when($search, function ($query, $search) {
                    return $query->where('merk_name', 'like', '%'. $search .'%');
                })
                ->limit(10)
                ->get();
            return response()->json($merks);
        });
    });

    /**
     * Side: Admin cabang
     */
    // ROUTE UNTUK SISTEM KIRIM BARANG (RESI)
    // Admin Side (Mengirim Barang Ke RMA)
    Route::middleware(['role:admin'])->prefix('shipments/admin')->name('shipments.admin.')->group( function () {
        
        // Route::get('outbound_to_rma/{serviceItem}/create', [ShipmentController::class, 'createOutboundToRma'])->name('outbound_to_rma.create'); // Form kirim
        // Route::post('outbound-to-rma/{serviceItem}/store', [ShipmentController::class, 'storeOutboundToRma'])->name('outbound_to_rma.store'); // Simpan Kirim
        
        // Menampilkan seluruh data service siap kirim ke rma
        Route::get('outbound-to-rma', [ShipmentController::class, 'indexOutboundToRma'])->name('outbound_to_rma.index'); // Daftar barang siap kirim
        // multiple choice service item
        Route::get('outbound-to-rma/create-multiple', [ShipmentController::class, 'createOutboundMultiple'])->name('outbound_to_rma.bulk_create');
        Route::post('outbound-to-rma/store-multiple', [ShipmentController::class, 'storeOutboundMultiple'])->name('outbound_to_rma.bulk_store');

        // Menampilkan Resi pengiriman barang service
        Route::get('resi-outbound-to-rma', [ShipmentController::class,'indexResiOutboundToRma'])->name('resi_outbound_to_rma.index');
        // Edit Resi pengiriman dan rubah service item
        Route::get('resi-outbound-to-rma/edit/{shipment}', [ShipmentController::class, 'editResiOutboundToRma'])->name('resi_outbound_to_rma.edit');
        Route::put('resi-outbound-to-rma/update/{shipment}', [ShipmentController::class, 'updateResiOutboundToRma'])->name('resi_outbound_to_rma.update');
        
        // Admin Menerima Barang dari RMA
        Route::get('inbound-from-rma', [ShipmentController::class, 'indexInboundFromRma'])->name('inbound_from_rma.index'); // Daftar barang masuk dari RMA
        Route::post('inbound-from-rma/{shipment}/receive', [ShipmentController::class, 'receiveInboundFromRma'])->name('inbound_from_rma.receive'); // Aksi Terima
        // Detail service masuk dari RMA
        Route::get('inbound-from-rma/{shipment}', [ShipmentController::class,'showInboundFromRma'])->name('inbound_from_rma.show');

        // Downlooad Shipment PDF
        Route::get('resi-outbound-to-rma/{shipment}/pdf', [ShipmentController::class, 'pdfResiOutboundToRma'])->name('resi_outbound_to_rma.pdf');

        // Delete Resi
        Route::delete('resi-outbound-to-rma/{id}', [ShipmentController::class,'destroyResiOutstandingToRma'])->name('resi_outbound_to_rma.destroy');

        // History Pengiriman
        Route::get('resi-outbound-to-rma/history', [ShipmentController::class,'historyResiOutboundToRma'])->name('history_resi_outbound_to_rma.index');
        Route::get('resi-outbound-to-rma/history-show/{shipment}', [ShipmentController::class,'historyShowResiOutboundToRma'])->name('history_resi_outbound_to_rma.show');
    });

    /**
     * Side: RMA Admin
     */
    // RMA Side (Menerima Barang dari Admin & Mengirim Kembali ke Admin)
    Route::middleware(['role:rma_admin'])->prefix('shipments/rma')->name('shipments.rma.')->group(function () {
        // Route::get('inbound-from-admin', [ShipmentController::class, 'indexInboundFromAdmin'])->name('inbound_from_admin.index'); // Daftar barang masuk dari admin
        // Route::post('inbound-from-admin/{shipment}/receive', [ShipmentController::class,'receiveInboundFromAdmin'])->name('inbound_from_admin.receive'); // Aksi terima
        // Route::get('outbound-from-rma/{serviceItem}/create', [ShipmentRmaController::class,'createOutboundFromRma'])->name('outbound_from_rma.create'); // form kirim kembali
        // Route::post('outbound-from-rma/{serviceItem}/store', [ShipmentRmaController::class,'storeOutboundFromRma'])->name('outbound_from_rma.store'); // Simpan Kirim Kembali
        
        // Daftar barang service masuk dari admin
        Route::get('inbound-from-admin', [ShipmentRmaController::class, 'indexInboundFromAdmin'])->name('inbound_from_admin.index'); // Daftar barang masuk dari admin
        // Detail service masuk dari admin
        Route::get('inbound-from-admin/{shipment}', [ShipmentRmaController::class, 'showInboundFromAdmin'])->name('inbound_from_admin.show');
        // Aksi terima resi
        Route::post('inbound-from-admin/{shipment}/receive', [ShipmentRmaController::class,'receiveInboundFromAdmin'])->name('inbound_from_admin.receive'); 
        // Aksi terima resi detail
        Route::post('inbound-from-admin-detail/{shipment}/receive', [ShipmentRmaController::class,'receiveInboundFromAdminDetail'])->name('inbound_from_admin_detail.receive');

        // Daftar Kirim barang ke RMA
        Route::get('outbound-from-rma', [ShipmentRmaController::class,'indexOutboundFromRma'])->name('outbound_from_rma.index'); // Daftar barang siap kirim kembali
        // Multiple choice service item
        Route::get('outbound-from-rma/create-multiple', [ShipmentRmaController::class, 'createOutboundMultipleFromRma'])->name('outbound_from_rma.bulk_create');
        Route::post('outbound-from-rma/store-multiple', [ShipmentRmaController::class, 'storeOutboundMultipleFromRma'])->name('outbound_from_rma.bulk_store');

        // Menampilkan resi pengiriman barang service
        Route::get('outbound-from-rma/resi-outbound-from-rma', [ShipmentRmaController::class,'indexResiOutboundFromRma'])->name('resi_outbound_from_rma.index');
        // Edit Resi pengiriman dan rubah service item
        Route::get('resi-outbound-from-rma/edit/{shipment}', [ShipmentRmaController::class, 'editResiOutboundFromRma'])->name('resi_outbound_from_rma.edit');
        Route::put('resi-outbound-from-rma/update/{shipment}', [ShipmentRmaController::class, 'updateResiOutboundFromRma'])->name('resi_outbound_from_rma.update');

        // Downlooad Shipment PDF
        Route::get('resi-outbound-from-rma/{shipment}/pdf', [ShipmentRmaController::class, 'pdfResiOutboundFromRma'])->name('resi_outbound_from_rma.pdf');

        // Delete Resi
        Route::delete('resi-outbound-to-rma/{id}', [ShipmentRmaController::class,'destroyResiOutboundFromRma'])->name('resi_outbound_from_rma.destroy');
    });

    // proses servis (RMA/Teknisi saja) akan menyimpan handle_by_user_id
    Route::middleware(['role:rma'])->group( function () {
        Route::get('service_processes', [ServiceProcessController::class, 'index'])->name('service_processes.index');
        Route::get('service_processes/{serviceItem}/work', [ServiceProcessController::class, 'workOn'])->name('service_processes.work_on');
        Route::post('service_processes/{serviceItem}/work', [ServiceProcessController::class, 'storeWork'])->name('service_processes.store_work');
        Route::get('service_processes/{serviceProcess}', [ServiceProcessController::class, 'show'])->name('service_processes.show'); 
    });

    // halaman detail Mitra Bisnis, semua role dapat melihat halaman ini
    Route::middleware(['role:developer,superadmin,admin,rma,rma_admin'])->group(function () {
        // aksese detail customer (show)
        Route::get('customers/{customers}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('customers/{customer}/on-process-service', [CustomerController::class, 'showServiceOnProcess'])->name('customers.on_process_services');

        Route::get('service_items/{service_item}', [ServiceItemController::class, 'show'])->name('service_items.show');
        Route::get('service_processes/{serviceProcess}', [ServiceProcessController::class, 'show'])->name('service_processes.show');
        Route::get('shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show'); // Detail shipments
    });

    // hanya Developer
    Route::middleware(['role:developer'])->group(function () {
        // CRUD User
        Route::resource('users', UserController::class);
        
        // CRUD Merk
        Route::resource('merks', MerkController::class);

        // CRUD Tipe Barang
        Route::resource('item_types', ItemTypeController::class);
    });

    // CRUD kantor cabang (developer, superadmin)
    Route::middleware(['role:developer,superadmin'])->group(function () {
        Route::resource('branch_offices', BranchOfficeController::class);
    });

    // melihat aktivitas service (developer, superadmin)
    Route::middleware(['role:developer,superadmin'])->group(function () {
        // Melihat List Customer All
        Route::get('activity/customers', [CustomerController::class, 'indexAll'])->name('activity.customers.index');
        // Datatables Customer Activity
        Route::get('activity/data', [CustomerController::class, 'getDataCustomerActivity'])->name('activity-customers');
        // Show Detail Customer
        Route::get('activity/customers/{customer}', [CustomerController::class, 'showDetailAktivityCustomer'])->name('activity.customers.detail_activity_customer');
        
        // melihat daftar & barang service
        Route::get('activity/service-items', [ServiceItemController::class, 'indexAllServiceItems'])->name('activity.service_items.index');
        Route::get('activity/service-items/{serviceItem}', [ServiceItemController::class, 'showDetailActivityServiceItem'])->name('activity.service_items.detail_activity_service_item');

        // Activity Service Processes
        Route::get('activity/service-processes', [ServiceProcessController::class, 'indexActivityServiceProcesses'])->name('activity.service_processes.index');
    });

    // RMA (Stock Sparepart)
    Route::middleware(['role:rma,rma_admin'])->group(function () {
        Route::resource('spareparts', SparepartController::class);
        
        // Stock In
        Route::get('stock_sparepart/{sparepart}/stock_in', [StockSparepartController::class, 'stockIn'])->name('stock_in.create');
        Route::post('stock_sparepart/{sparepart}/stock_in', [StockSparepartController::class, 'StoreStockIn'])->name('stock_in.store');

        // stock Out
        Route::get('stock_sparepart/{serviceItem}/stock_out', [StockSparepartController::class, 'stockOut'])->name('stock_out.index');
        Route::post('stock_sparepart/{serviceItem}/stock_out', [StockSparepartController::class,'storeStockOut'])->name('stock_out.store');
        
        // minus stock
        Route::get('stock_sparepart/{sparepart}/stock_out_minus', [StockSparepartController::class,'stockOutMinus'])->name('stock_out_minus.create');
        Route::post('stock_sparepart/{sparepart}/stock_out_minus', [StockSparepartController::class,'storeStockOutMinus'])->name('stock_out_minus.store');

        // Pengembalian
        Route::get('stock-sparepart/stock-return/{serviceItem}', [StockSparepartController::class,'stockReturn'])->name('stock_return.create');
        Route::post('stock-sparepart/stock_return/{serviceItem}', [StockSparepartController::class,'storeStockReturn'])->name('stock_return.store');
    });

    // logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
