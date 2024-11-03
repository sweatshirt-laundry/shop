<?php

use App\Http\Api\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return response()->json(["message" => "API is up and running"]);
});
Route::apiResource('orders', OrderController::class);
