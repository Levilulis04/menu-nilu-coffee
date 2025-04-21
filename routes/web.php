<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\menuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/menu', [menuController::class, 'showMenu'])->name('user.menu');
Route::get('/status', [menuController::class, 'showMenu'])->name('user.status');
Route::get('/statuss', [menuController::class, 'showMenus'])->name('user.cart');


Route::get('/admin/login', [AdminAuthController::class, 'getLoginAdmin'])->name('admin.login.form');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');

Route::get('/admin/menus', [adminController::class, 'getMenuAdmin'])->name('menus.index');
Route::get('/admin/menus/create', [adminController::class, 'getCreateMenuAdmin'])->name('menus.create');
Route::post('/admin/menus/create', [adminController::class, 'postCreateMenuAdmin'])->name('menus.store');

Route::post('/admin/menus/update/{id}', [adminController::class, 'postUpdateMenuAdmin'])->name('menus.update');
Route::get('/admin/menus/edit/{id}', [adminController::class, 'getEditMenuAdmin'])->name('menus.edit');

Route::delete('/admin/menus/{menu}', [adminController::class, 'destroy'])->name('menus.destroy');

Route::get('/admin/qr', fn() => view('admin.qr'))->name('admin.qr');
Route::get('/admin/qr-preview', [adminController::class, 'showQrPreview'])->name('admin.qr.preview');

Route::get('/admin/tables', [adminController::class, 'showTableStatus'])->name('admin.tables');
Route::put('/admin/tables/status/{table_number}', [adminController::class, 'updateTableStatus'])->name('tables.updateStatus');



