<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class CategoryContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
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
        try {

            $categoryNameData = $request->validate([
                "CategoryName" => ['required , string']
            ]);

            $category = Category::create($categoryNameData);

            return response()->json([
                'message' => 'New category created successfully',
                'category' => $category
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to create a new category',
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Category::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $requestedCategory = Category::findOrFail($id);
            $newCategory = $request->validate([
                'CategoryName' => ['required', 'string']
            ]);

            $requestedCategory->update($newCategory);

            // Return a success response
            return response()->json(['message' => 'Category updated successfully']);
        } catch (\Throwable $e) {
            // Return error response
            return response()->json([
                'message' => 'Failed to update car',
                'error' => $e->getMessage() // Only return the error message for security reasons
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Category::destroy($id);
    }
}
