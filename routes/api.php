<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('')->name('admin.')->middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::resource('product', ProductController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('product-category', ProductCategoriesController::class);
});

Route::namespace('')->name('user.')->middleware('auth')->prefix('customer')->group(function () {
    Route::post('process-order', [OrderController::class, 'store'])->name('process-order');
});

Route::get('/generate-random', function () {
    $randomString = generateRandomAlphanumeric(10);

    return response()->json(['random_string' => $randomString]);
});
