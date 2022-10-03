<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\invoicecontroller;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin',[\App\Http\Controllers\admin\AuthController::class,'index'])->name('admin.login');
Route::post('adminpostlogin', [\App\Http\Controllers\admin\AuthController::class, 'postLogin'])->name('admin.postlogin');
Route::get('logout', [\App\Http\Controllers\admin\AuthController::class, 'logout'])->name('admin.logout');

Route::group(['prefix'=>'admin','middleware'=>['auth'],'as'=>'admin.'],function () {
    Route::get('dashboard',[\App\Http\Controllers\admin\DashboardController::class,'index'])->name('dashboard');

   
    Route::get('settings',[\App\Http\Controllers\admin\SettingsController::class,'index'])->name('settings.list');
    Route::post('updateInvoiceSetting',[\App\Http\Controllers\admin\SettingsController::class,'updateInvoiceSetting'])->name('settings.updateInvoiceSetting');
    Route::get('settings/edit',[\App\Http\Controllers\admin\SettingsController::class,'editSettings'])->name('settings.edit');

    
    
  
    Route::get('invoice/list',[\App\Http\Controllers\admin\invoicecontroller::class,'list'])->name('invoice.list');

   Route::post('allInvoicelist',[\App\Http\Controllers\admin\invoicecontroller::class,'allInvoicelist'])->name('allInvoicelist');

   Route::get('invoice/edit/{id}',[\App\Http\Controllers\admin\invoicecontroller::class,'edit'])->name('invoice.edit');

   Route::post('invoice/save',[\App\Http\Controllers\admin\invoicecontroller::class,'save'])->name('invoice.save');
   Route::get('invoice/pdf/{id}',[\App\Http\Controllers\admin\invoicecontroller::class,'generate_pdf'])->name('invoice.pdf');
});

Route::get('invoice/create/{billno}',[\App\Http\Controllers\admin\invoicecontroller::class,'index'])->name('invoice.create');
Route::post('invoice/create',[\App\Http\Controllers\admin\invoicecontroller::class,'store'])->name('invoice.add');
Route::post('invoice/add_row_item',[\App\Http\Controllers\admin\invoicecontroller::class,'add_row_item'])->name('invoice.add_row_item');
