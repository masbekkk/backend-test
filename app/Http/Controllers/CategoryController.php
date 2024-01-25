<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Categories retrieved Successfully!',
                'data' => $categories
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error('Error retrieving categories: ' . $e->getMessage());
            throw $e;
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve Categories',
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
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $newCategory = Category::create([
                'name' => $request->name,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Category created Successfully!',
                'data' => $newCategory,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error('Error creating Category: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Category',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $category->update([
                'name' => $request->name,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated Successfully!',
                'data' => $category,
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error('Error updating Category: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Category',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted Successfully!',
                'data' => null,
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            Log::error('Error deleting Category: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Category',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
