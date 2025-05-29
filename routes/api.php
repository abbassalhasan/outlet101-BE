<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/add', 'add_product');
    });
    Route::delete('/delete/{id}', 'delete_product');
    Route::post('/edit/{id}',  'edit_product');
    Route::get('/get-all','get_products');
    Route::get('/get/{id}','get_product');

});



