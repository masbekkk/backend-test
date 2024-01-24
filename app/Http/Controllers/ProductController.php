<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Products retrieved Successfully!',
                'data' => $products
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error('Error retrieving products: ', $e->getMessage());
            throw $e;
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve products',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer']
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $newProducts = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Products created Successfully!',
                'data' => $newProducts,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error('Error creating products: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create products',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer']
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated Successfully!',
                'data' => $product,
            ], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {

            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted Successfully!',
                'data' => null,
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete product',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
