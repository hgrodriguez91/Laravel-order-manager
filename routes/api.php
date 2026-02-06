<?php

use App\Http\Controllers\CreateOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
    Route::post('/', CreateOrderController::class);
});