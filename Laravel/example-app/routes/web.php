<?php

use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\TestController;
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

Route::get('/test', [
    TestController::class,
    'test'
]);

Route::get('/api/v1/products', [
    ProductController::class,
    'getProducts'
]);

Route::get('/api/v1/products/{id}', [
    ProductController::class,
    'getProductItem'
]);

Route::post('/api/v1/products', [
    ProductController::class,
    'createProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);

Route::delete('/api/v1/products/{id}', [
    ProductController::class,
    'deleteProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);
