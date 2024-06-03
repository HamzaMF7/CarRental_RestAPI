<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarStoreRequest;
use App\Http\Requests\CarUpdateRequest;
use App\Models\Car;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Car::all();
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
    public function store(CarStoreRequest $request)
    {
        try {
            // Validate the request
            $carData = $request->validated();

            // Upload and store image
            if ($request->hasFile('Image')) {
                $image = $request->file('Image');
                $image_name = time() . '_' . $image->getClientOriginalName(); // Add timestamp to ensure unique name
                $path = $image->storeAs('images', $image_name, 'public');
                $carData["Image"] = $path;
            }

            // Create car record 
            $createdCar = Car::create($carData);

            return response()->json(['message' => 'New car created successfully', 'car data' => $carData]);
        } catch (\Exception $e) {
            // Return error response
            return response()->json(['message' => 'Failed to create car', 'error' => $e], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Car::find($id);
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
        dd($request->all());
        // try {
        //     // Find the car by its ID
        //     $requestedCar = Car::findOrFail($id);

        //     // Delete the old image from storage if it exists
        //     if ($requestedCar->Image) {
        //         Storage::disk('public')->delete($requestedCar->Image);
        //     }

        //     // Return the validated data immediately
        //     $incomingData = $request->all();

        //     // Upload and store the new image if provided
        //     if ($request->hasFile('Image')) {
        //         $image = $request->file('Image');
        //         $image_name = time() . '_' . $image->getClientOriginalName(); // Add timestamp to ensure unique name
        //         $path = $image->storeAs('images', $image_name, 'public');
        //         $incomingData['Image'] = $path;
        //     }

        //     return response()->json(['incommingData' => $incomingData]);

        //     // Update the car record with the validated data
        //     // $requestedCar->update($incomingData);

        //     // Return a success response
        //     return response()->json(['message' => 'Car updated successfully']);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => $e], 500);
        // }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Car::destroy($id);
    }
}
