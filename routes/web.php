<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/', [ProductoController::class, 'index']);

Route::resource('products', ProductoController::class)
    ->parameters(['products' => 'producto']);
