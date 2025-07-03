<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\ServiceProcessController;
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
        Route::get('customers/{customers}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('customers/{customer}/on-process-service', [CustomerController::class, 'showServiceOnProcess'])->name('customers.on_process_services');
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
        Route::get('all-customers', [CustomerController::class, 'indexAll'])->name('all_customers.index');
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
