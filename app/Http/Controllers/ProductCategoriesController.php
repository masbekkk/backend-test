<?php

namespace App\Http\Controllers;

use App\Models\ProductCategories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productCategories = ProductCategories::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Product Categories retrieved Successfully!',
                'data' => $productCategories
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error('Error retrieving Product categories: ' . $e->getMessage());
            throw $e;
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve Product Categories',
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
            'product_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $newProductCategory = ProductCategories::create([
                'product_id' => $request->product_id,
                'category_id' => $request->category_id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Product Category created Successfully!',
                'data' => $newProductCategory,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error('Error creating Product Category: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Product Category',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategories $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategories $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategories $productCategory)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $productCategory->update([
                'product_id' => $request->product_id,
                'category_id' => $request->category_id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Product Category updated Successfully!',
                'data' => $productCategory,
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error('Error updating Product Category: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Product Category',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategories $productCategory)
    {
        try {
            $productCategory->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product Category deleted Successfully!',
                'data' => null,
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            Log::error('Error deleting Product Category: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Product Category',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
