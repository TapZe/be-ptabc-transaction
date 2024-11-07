<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $transactions = Transaction::with(['user', 'product.type'])->paginate($limit);
        return response()->json($transactions);
    }

    public function searchWithSort(Request $request)
    {
        $query = Transaction::with(['user', 'product.type']);

        if ($request->has('name')) {
            $query->whereHas('product', function ($q) use ($request) {
                // Filter transactions by product name
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->input('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order', 'asc');

            if ($sortBy === 'name') {
                $query->whereHas('product', function ($q) use ($sortOrder) {
                    // Order by the product name
                    $q->orderBy('name', $sortOrder);
                });
            } elseif ($sortBy === 'date') {
                $query->orderBy('transaction_date', $sortOrder);
            }
        }

        $limit = $request->input('limit', 10);
        $transactions = $query->paginate($limit);
        return response()->json($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'selled_stock' => 'required|numeric',
            'product_id' => 'required|numeric',
            'transaction_date' => 'nullable|date',
        ]);

        $product = Product::with(['stock'])->find($request->input('product_id'));
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Check if enough stock is available
        if ($request->input('selled_stock') > $product->stock->quantity) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        $transaction = [
            'selled_stock' => $request->input('selled_stock'),
            'product_id' => $request->input('product_id'),
            'user_id' => auth()->user()->id,
            'starting_stock' => $product->stock->quantity,
            'transaction_date' => $request->input('transaction_date', now()),
        ];

        Transaction::created($transaction);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with(['user', 'product.type'])->find($id);
        return response()->json($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'transaction_date' => 'required|date',
        ]);

        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $transaction->transaction_date = $request->input('transaction_date');
        $transaction->save();

        return response()->json(['message' => 'Transaction updated successfully', 'transaction' => $transaction]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::with('product.stock')->find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Restore the stock
        $product = $transaction->product;
        if ($product && $product->stock) {
            $product->stock->quantity += $transaction->selled_stock;
            $product->stock->save();
        }

        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully, stock restored']);
    }


    public function productTypeBoughtList(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);
        $startDate = $request->input('from', '1970-01-01');
        $endDate = $request->input('to', now());

        if ($endDate && Carbon::parse($endDate)->gt(now())) {
            $endDate = now(); // Don't let end date became more than today/current time
        }

        $productTypes = ProductType::all();
        $result = [];

        foreach ($productTypes as $productType) {
            // Sum the selled_stock from transactions that is related to products of this product type
            $query = Transaction::whereHas('product', function ($query) use ($productType) {
                $query->where('product_type_id', $productType->id);
            });

            // Check the date range first then sum the selled stock
            if ($startDate && $endDate) {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
            }
            $totalSelledStock = (int) $query->sum('selled_stock');

            $result[] = [
                'product_type_id' => $productType->id,
                'product_type_name' => $productType->name,
                'total_selled_stock' => $totalSelledStock,
            ];
        }

        usort($result, function ($a, $b) {
            // Using spaceship operator
            return $b['total_selled_stock'] <=> $a['total_selled_stock']; // Descending order
        });

        return response()->json($result);
    }
}
