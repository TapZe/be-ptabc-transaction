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

// Index: GET /transaction - Retrieve a list of transactions.
// Show: GET /transaction/{id} - Retrieve a specific transaction by ID.
// Store: POST /transaction - Create a new transaction.
// Update: PUT /transaction/{id} - Update a specific transaction by ID.
// Destroy: DELETE /transaction/{id} - Delete a specific transaction by ID.
