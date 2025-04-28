<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderItemController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SupplierController;
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

//Route::resource('categories', CategoryController::class);
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
Route::post('/inventories', [InventoryController::class, 'store'])->name('inventories.store');
Route::get('/inventories/{id}', [InventoryController::class, 'show'])->name('inventories.show');
Route::put('/inventories/{id}', [InventoryController::class, 'update'])->name('inventories.update');
Route::delete('/inventories/{id}', [InventoryController::class, 'destroy'])->name('inventories.destroy');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

Route::get('/order-items', [OrderItemController::class, 'index'])->name('order_items.index');
Route::post('/order-items', [OrderItemController::class, 'store'])->name('order_items.store');
Route::get('/order-items/{id}', [OrderItemController::class, 'show'])->name('order_items.show');
Route::put('/order-items/{id}', [OrderItemController::class, 'update'])->name('order_items.update');
Route::delete('/order-items/{id}', [OrderItemController::class, 'destroy'])->name('order_items.destroy');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase_orders.store');
Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.show');
Route::put('/purchase-orders/{id}', [PurchaseOrderController::class, 'update'])->name('purchase_orders.update');
Route::delete('/purchase-orders/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase_orders.destroy');

Route::get('/purchase-order-items', [PurchaseOrderItemController::class, 'index'])->name('purchase_order_items.index');
Route::post('/purchase-order-items', [PurchaseOrderItemController::class, 'store'])->name('purchase_order_items.store');
Route::get('/purchase-order-items/{id}', [PurchaseOrderItemController::class, 'show'])->name('purchase_order_items.show');
Route::put('/purchase-order-items/{id}', [PurchaseOrderItemController::class, 'update'])->name('purchase_order_items.update');
Route::delete('/purchase-order-items/{id}', [PurchaseOrderItemController::class, 'destroy'])->name('purchase_order_items.destroy');

Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
Route::get('/shipments/{id}', [ShipmentController::class, 'show'])->name('shipments.show');
Route::put('/shipments/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
Route::delete('/shipments/{id}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');

Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
