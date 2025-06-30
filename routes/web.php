<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\ServiceProcessController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::resource('customers', CustomerController::class);
Route::resource('service_items', ServiceItemController::class);
Route::resource('service_processes', ServiceProcessController::class);