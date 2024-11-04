<?php

use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::resource('orders', OrderController::class);
    Route::patch('items/{id}', [ItemController::class, 'update'])->name('items.update');
});
