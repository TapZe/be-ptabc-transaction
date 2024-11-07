<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['authGroup'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});

Route::get('/transaction/search', [TransactionController::class, 'searchWithSort']);
Route::get('/transaction/typeBought', [TransactionController::class, 'productTypeBoughtList']);
Route::resource('transaction', TransactionController::class)->except(['create', 'edit']);
Route::resource('product', ProductController::class);
