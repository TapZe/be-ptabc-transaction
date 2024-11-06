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

Route::resource('transaction', TransactionController::class)->except(['create', 'edit']);
Route::get('searchNameDate', [TransactionController::class, 'searchByNameOrDate']);
Route::get('productTypeBought', [TransactionController::class, 'productTypeBoughtList']);
Route::resource('product', ProductController::class);
