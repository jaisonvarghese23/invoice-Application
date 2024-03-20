<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get_all_invoice',[InvoiceController::class,'get_all_invoices']);
Route::get('/search_invoices',[InvoiceController::class,'search_invoices']);
Route::get('/create_invoice',[InvoiceController::class,'create_invoice']);
Route::get('/customers',[CustomerController::class,'customers']);
Route::get('/products',[ProductController::class,'products']);
Route::post('/add_invoice',[InvoiceController::class,'add_invoice']);
Route::get('/show_invoice/{id}',[InvoiceController::class,'show_invoice']);
Route::get('/edit_invoice/{id}',[InvoiceController::class,'edit_invoice']);
Route::post('/add_invoice',[InvoiceController::class,'add_invoice']);
Route::post('/update_invoice/{id}',[InvoiceController::class,'update_invoice_items']);
Route::get('/delete_invoice/{id}',[InvoiceController::class,'delete_invoice']);

