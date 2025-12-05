<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Http\Resources\productsResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return response()->json(['products' => $products], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        
        return new productsResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Set status based on stock: if stock = 0 → out_of_stock
            $validated['status'] = $validated['stock'] > 0 ? 'in_stock' : 'out_of_stock';
            
            $product = Product::create($validated);

            return response()->json([
                'message' => 'Product created successfully.',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $validated = $request->validated();
            
            // Update status based on stock: if stock = 0 → out_of_stock
            if (isset($validated['stock'])) {
                $validated['status'] = $validated['stock'] > 0 ? 'in_stock' : 'out_of_stock';
            }
            
            $product->update($validated);

            return response()->json([
                'message' => 'Product updated successfully.',
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
