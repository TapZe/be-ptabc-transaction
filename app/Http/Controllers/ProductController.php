<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Stock;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['type', 'stock'])->get();
        return response()->json($products);
    }

    public function productType()
    {
        $productType = ProductType::all();
        return response()->json($productType);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:product,name',
            'stock' => 'nullable|numeric',
            'product_type_id' => 'nullable|numeric',
            'product_type_name' => 'nullable|string',
        ]);

        if ($request->product_type_id) {
            $productType = ProductType::find($request->product_type_id);

            if (!$productType) {
                if ($request->product_type_name) {
                    $productType = ProductType::create([
                        'name' => $request->product_type_name,
                    ]);
                } else {
                    throw new Exception('Product type ID is not found and product type name is empty.');
                }
            }
        } elseif ($request->product_type_name) {
            $productType = ProductType::create([
                'name' => $request->product_type_name,
            ]);
        } else {
            throw new Exception('Product type ID is empty and product type name is also empty.');
        }

        $productParams = [
            'name' => $request->name,
            'product_type_id' => $productType->id
        ];
        $newProduct = Product::create($productParams);

        $stockParams = [
            'quantity' => $request->stock,
            'product_id' => $newProduct->id
        ];
        $newStock = Stock::create($stockParams);

        return response()->json([
            'message' => 'Product added successfully',
            'product' => $newProduct->load('type', 'stock'),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'nullable|string|unique:products,name,' . $id,
            'stock' => 'nullable|numeric',
            'product_type_id' => 'nullable|numeric',
            'product_type_name' => 'nullable|string',
        ]);

        try {
            $product = Product::findOrFail($id);

            if ($request->has('name')) {
                $product->name = $request->name;
            }

            $productType = ProductType::find($request->product_type_id);
            if (!$productType) {
                if ($request->has('product_type_name')) {
                    $productType = ProductType::where('name', $request->product_type_name)->first();
                    if (!$productType) {
                        $productType = ProductType::create([
                            'name' => $request->product_type_name,
                        ]);
                    }
                } else {
                    throw new Exception('Invalid product type ID and no product type name provided.');
                }
            }
            $product->product_type_id = $productType->id;
            $product->save();

            if ($request->has('stock')) {
                $stock = Stock::where('product_id', $product->id)->first();
                if ($stock) {
                    $stock->quantity = $request->stock;
                    $stock->save();
                } else {
                    Stock::create([
                        'quantity' => $request->stock,
                        'product_id' => $product->id
                    ]);
                }
            }

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product->load('type', 'stock'),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
