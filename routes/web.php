<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\ServiceProcessController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchOfficeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

    // Crud Customer (hanya admin) 
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('customers', CustomerController::class);
    });

    // CRUD service item (hanya admin) akan menyimpan created_by_user_id
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('service_items', ServiceItemController::class);
    });

    // proses servis (RMA/Teknisi saja) akan menyimpan handle_by_user_id
    Route::middleware(['role:rma'])->group( function () {
        Route::get('service_processes', [ServiceProcessController::class, 'index'])->name('service_processes.index');
        Route::get('service_processes/{serviceItem}/work', [ServiceProcessController::class, 'workOn'])->name('service_processes.work_on');
        Route::post('service_processes/{serviceItem}/work', [ServiceProcessController::class, 'storeWork'])->name('service_processes.store_work');
        Route::get('service_processes/{serviceProcess}', [ServiceProcessController::class, 'show'])->name('service_processes.show'); 
    });

    // halaman detail pelanggan, semua role dapat melihat halaman ini
    Route::middleware(['role:developer,superadmin,admin,rma'])->group(function () {
        // aksese detail customer (show)
        Route::get('customers/{customers}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('customers/{customer}/on-process-service', [CustomerController::class, 'showServiceOnProcess'])->name('customers.on_process_services');

        Route::get('service_items/{service_item}', [ServiceItemController::class, 'show'])->name('service_items.show');
        Route::get('service_processes/{serviceProcess}', [ServiceProcessController::class, 'show'])->name('service_processes.show');
        Route::get('shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show'); // Detail shipments
    });

    // CRUD user (hanya Developer)
    Route::middleware(['role:developer'])->group(function () {
        Route::resource('users', UserController::class);
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

    // ROUTE UNTUK SISTEM KIRIM BARANG (RESI)
    // Admin Side (Mengirim Barang Ke RMA)
    Route::middleware(['role:admin'])->prefix('shipments/admin')->name('shipments.admin.')->group( function () {
        Route::get('outbound-to-rma', [ShipmentController::class, 'indexOutboundToRma'])->name('outbound_to_rma.index'); // Daftar barang siap kirim
        Route::get('outbound_to_rma/{serviceItem}/create', [ShipmentController::class, 'createOutboundToRma'])->name('outbound_to_rma.create'); // Form kirim
        Route::post('outbound-to-rma/{serviceItem}/store', [ShipmentController::class, 'storeOutboundToRma'])->name('outbound_to_rma.store'); // Simpan Kirim

        // Admin: Menerima Barang dari RMA
        Route::get('inbound-from-rma', [ShipmentController::class, 'indexInboundFromRma'])->name('inbound_from_rma.index'); // Daftar barang masuk dari RMA
        Route::post('inbound-from-rma/{shipment}/receive', [ShipmentController::class, 'receiveInboundFromRma'])->name('inbound_from_rma.receive'); // Aksi Terima
    });

    // RMA Side (Menerima Barang dari Admin & Mengirim Kembali ke Admin)
    Route::middleware(['role:rma'])->prefix('shipments/rma')->name('shipments.rma.')->group(function () {
        Route::get('inbound-from-admin', [ShipmentController::class, 'indexInboundFromAdmin'])->name('inbound_from_admin.index'); // Daftar barang masuk dari admin
        Route::post('inbound-from-admin/{shipment}/receive', [ShipmentController::class,'receiveInboundFromAdmin'])->name('inbound_from_admin.receive'); // Aksi terima

        Route::get('outbound-from-rma', [ShipmentController::class,'indexOutboundFromRma'])->name('outbound_from_rma.index'); // Daftar barang siap kirim kembali
        Route::get('outbound-from-rma/{serviceItem}/create', [ShipmentController::class,'createOutboundFromRma'])->name('outbound_from_rma.create'); // form kirim kembali
        Route::post('outbound-from-rma/{serviceItem}/store', [ShipmentController::class,'storeOutboundFromRma'])->name('outbound_from_rma.store'); // Simpan Kirim Kembali
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
