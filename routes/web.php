<?php

use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\ServiceProcessController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\StockSparePartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchOfficeController;
use App\Models\Customer;
use App\Models\ItemType;
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
        // server side customer DataTable
        Route::get('customers/data', [CustomerController::class, 'getData'])->name('customers.data');
        
        // CRUD Customer
        Route::resource('customers', CustomerController::class);

        // CRUD Service Item
        Route::resource('service_items', ServiceItemController::class);
        
        // api dynamic option item type
        Route::get('/api/item-types', function (Request $request) {
            $search = $request->get('term');
            $itemTypes = ItemType::query()
                ->select('id', 'type_name')
                ->when($search, function ($query, $search) {
                    return $query->where('type_name', 'like', '%'. $search .'%');
                })
                ->limit(10)
                ->get();
            
            // $itemTypes = ItemType::select('id', 'type_name')->get();
            return response()->json($itemTypes);
        });
    });

    // ROUTE UNTUK SISTEM KIRIM BARANG (RESI)
    // Admin Side (Mengirim Barang Ke RMA)
    Route::middleware(['role:admin'])->prefix('shipments/admin')->name('shipments.admin.')->group( function () {
        Route::get('outbound-to-rma', [ShipmentController::class, 'indexOutboundToRma'])->name('outbound_to_rma.index'); // Daftar barang siap kirim
        Route::get('outbound_to_rma/{serviceItem}/create', [ShipmentController::class, 'createOutboundToRma'])->name('outbound_to_rma.create'); // Form kirim
        Route::post('outbound-to-rma/{serviceItem}/store', [ShipmentController::class, 'storeOutboundToRma'])->name('outbound_to_rma.store'); // Simpan Kirim

        // Admin: Menerima Barang dari RMA
        Route::get('inbound-from-rma', [ShipmentController::class, 'indexInboundFromRma'])->name('inbound_from_rma.index'); // Daftar barang masuk dari RMA
        Route::post('inbound-from-rma/{shipment}/receive', [ShipmentController::class, 'receiveInboundFromRma'])->name('inbound_from_rma.receive'); // Aksi Terima

        // multiple choice service item
        Route::get('shipments/admin/outbound-to-rma/create-multiple', [ShipmentController::class, 'createOutboundMultiple'])->name('outbound_to_rma.bulk_create');
        Route::post('shipments/admin/outbound-to-rma/store-multiple', [ShipmentController::class, 'storeOutboundMultiple'])->name('outbound_to_rma.bulk_store');

        // Menampilkan Resi pengiriman barang service
        Route::get('shipments/admin/resi-outbound-to-rma', [ShipmentController::class,'indexResiOutboundToRma'])->name('resi_outbound_to_rma.index');

        // Edit Resi pengiriman dan rubah service item
        Route::get('shipments/admin/resi-outbound-to-rma/edit/{shipment}', [ShipmentController::class, 'editResiOutboundToRma'])->name('resi_outbound_to_rma.edit');
        Route::put('shipments/admin/resi-outbound-to-rma/update/{shipment}', [ShipmentController::class, 'updateResiOutboundToRma'])->name('resi_outbound_to_rma.update');
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
        // melihat daftar & detail customer
        Route::get('activity/customers', [CustomerController::class, 'indexAll'])->name('activity.customers.index');
        Route::get('activity/customers/{customer}', [CustomerController::class, 'showDetailAktivityCustomer'])->name('activity.customers.detail_activity_customer');
        
        // melihat daftar & barang service
        Route::get('activity/service-items', [ServiceItemController::class, 'indexAllServiceItems'])->name('activity.service_items.index');
        Route::get('activity/service-items/{serviceItem}', [ServiceItemController::class, 'showDetailActivityServiceItem'])->name('activity.service_items.detail_activity_service_item');
    });

    // RMA Side (Menerima Barang dari Admin & Mengirim Kembali ke Admin)
    Route::middleware(['role:rma,rma_admin'])->prefix('shipments/rma')->name('shipments.rma.')->group(function () {
        Route::get('inbound-from-admin', [ShipmentController::class, 'indexInboundFromAdmin'])->name('inbound_from_admin.index'); // Daftar barang masuk dari admin
        Route::post('inbound-from-admin/{shipment}/receive', [ShipmentController::class,'receiveInboundFromAdmin'])->name('inbound_from_admin.receive'); // Aksi terima

        Route::get('outbound-from-rma', [ShipmentController::class,'indexOutboundFromRma'])->name('outbound_from_rma.index'); // Daftar barang siap kirim kembali
        Route::get('outbound-from-rma/{serviceItem}/create', [ShipmentController::class,'createOutboundFromRma'])->name('outbound_from_rma.create'); // form kirim kembali
        Route::post('outbound-from-rma/{serviceItem}/store', [ShipmentController::class,'storeOutboundFromRma'])->name('outbound_from_rma.store'); // Simpan Kirim Kembali
    });

    // RMA (Stock Sparepart)
    Route::middleware(['role:rma'])->group(function () {
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
