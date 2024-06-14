<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class BrandContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Brand::all();
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
            $brandName = $request->validate([
                'BrandName' => ['required', 'string']
            ]);

            // create a new brand record 
            $brand = Brand::create($brandName);

            return response()->json(["message" => "New brand created successfuly", "brand" => $brand], 200);
        } catch (\Throwable $e) {
            //throw $th;
            return response()->json([
                "message" => "Failed to create a new brand",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Brand::find($id);
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
            $requestedBrand = Brand::findOrFail($id);
            $newBrand = $request->validate([
                'BrandName' => ['required', 'string']
            ]);

            $requestedBrand->update($newBrand);

            // Return a success response
            return response()->json(['message' => 'Brand updated successfully']);
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
        return Brand::destroy($id);
    }
}
