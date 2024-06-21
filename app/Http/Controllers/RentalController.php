<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalAndPaymentRequest;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Rental::all();
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
    public function store(RentalAndPaymentRequest $request)
    {

        try {

            $data = $request->validated();

            // Extract rentalInfo and paymentInfo  
            $rentalData = $data['rentalInfo'];
            $paymentData = $data['paymentInfo'];

            // Initialize varibales 
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Rental::find($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Rental::destroy($id);
    }
}
