<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarStoreRequest;
use App\Http\Requests\CarUpdateRequest;
use App\Models\Car;
use App\Models\Location;
use App\Models\Rental;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

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
     * insertion of data across both cars and car_details tables in a single request
     */
    public function store(CarStoreRequest $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validated();

            $carData = $validatedData['car'];
            $carDetailsData = $validatedData['carDetails'];

            // Upload and store image
            if ($request->hasFile('car.Image')) {
                $image = $request->file('car.Image');
                $image_name = time() . '_' . $image->getClientOriginalName(); // Add timestamp to ensure unique name
                $path = $image->storeAs('images', $image_name, 'public');
                $carData["Image"] = $path;
            }

            // Initialize variables to store the created car and car details
            $car = null;
            $carDetails = null;

            // Use transaction to ensure atomicity
            DB::transaction(function () use ($carData, $carDetailsData, &$car, &$carDetails) {
                $car = Car::create($carData);
                $carDetails = $car->carDetails()->create(array_merge($carDetailsData, ['CarID' => $car->id]));
            });

            // Return success response
            return response()->json([
                'message' => 'New car created successfully',
                'car' => $car,
                'carDetails' => $carDetails
            ], 201);
        } catch (\Throwable $e) {
            // Return error response
            return response()->json([
                'message' => 'Failed to create car',
                'error' => $e->getMessage() // Only return the error message for security reasons
            ], 500);
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
        try {
            // Find the car by its ID
            $requestedCar = Car::findOrFail($id);

            // Separate car and carDetails data
            $carData = $request->input('car');
            $carDetailsData = $request->input('carDetails');

            // Handle image update and upload
            if ($request->hasFile('car.Image')) {
                //Delete the old image from storage if it exists
                if ($requestedCar->Image) {
                    Storage::disk('public')->delete($requestedCar->Image);
                }

                // Upload and store the new image
                $image = $request->file('car.Image');
                $image_name = time() . '_' . $image->getClientOriginalName(); // Add timestamp to ensure unique name
                $path = $image->storeAs('images', $image_name, 'public');
                $carData['Image'] = $path;
            }
            // Use transaction to ensure atomicity

            DB::transaction(function () use ($requestedCar, $carData, $carDetailsData) {
                // Update the car record 
                $requestedCar->update($carData);

                // Update the carDetails record 
                $requestedCar->carDetails->update($carDetailsData);
            });

            // Return a success response
            return response()->json(['message' => 'Car updated successfully']);
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
        return Car::destroy($id);
    }


    public function findCar(Request $request)
    {
        try {
            $availableCarsAtLocation = Location::with(['cars' => function ($query) {
                $query->where('CurrentStatus', 'Available');
            }])->find($request->locationID);

            $availableCarCount = $availableCarsAtLocation->cars->count();

            return response()->json([
                "availableCarsAtLocation" => $availableCarsAtLocation,
                "availableCarCount" => $availableCarCount
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkOut(Request $request)
    {

        try {
            // dd($request);
            $jsonData = $request->getContent();
            // $paymentData = $request->input('paymentInfo');
            // dd($jsonData);

            $data = json_decode($jsonData);

            // dd($data);

            //  Check if JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            // Extract rentalInfo and paymentInfo
            $rentalData = (array) $data->rentalInfo ?? null;
            $paymentData = (array) $data->paymentInfo ?? null;

            // dd(gettype($rentalData), gettype($paymentData));
            // dd($rentalData, $paymentData);
            $rental = null;
            $payment = null;

            // Use transaction to ensure atomicity
            DB::transaction(function () use ($rentalData, $paymentData, &$rental, &$payment) {
                $rental = Rental::create($rentalData);
                $payment = $rental->payement()->create(array_merge($paymentData, ['RentalID' => $rental->id]));
            });

            return response()->json([
                "msg" => "success"
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
