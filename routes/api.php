<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
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

Route::controller(CategoryController::class)->prefix('category')->group(function () {
    Route::post('/add','add_category');
    Route::delete('/delete/{id}','delete_category');
    Route::post('/edit/{id}','edit_category');
    Route::get('/get_all','get_categories');
    Route::get('/get/{id}','get_category');
});

Route::controller(CartItemController::class)->prefix('cart')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::post('add','add_item');
        Route::delete('delete/{id}','delete_item');
        Route::post('edit/{id}','edit_item');
        Route::get('get/{id}','get_cart_items');
        Route::get('purchase/{id}','purchase');
    });
});

