<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalAndPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rentalInfo.CarID' => 'required|exists:cars,id',
            'rentalInfo.UserID' => 'required|exists:users,id',
            'rentalInfo.StartDate' => 'required|date',
            'rentalInfo.EndDate' => 'required|date|after:rentalInfo.StartDate',
            'rentalInfo.TotalCost' => 'required|numeric',
            'rentalInfo.Status' => 'required|string|max:255',
            'rentalInfo.AdditionalRequirements' => 'nullable|string|max:255',
            'rentalInfo.PhoneNumber' => 'required|string|max:20',
            'rentalInfo.City' => 'required|string|max:255',
            'rentalInfo.PickupLocationID' => 'required|exists:locations,id',
            'rentalInfo.ReturnLocationID' => 'required|exists:locations,id',
            'paymentInfo.Amount' => 'required|numeric',
            'paymentInfo.PaymentMethod' => 'required|string|max:255',
            'paymentInfo.PaymentStatus' => 'required|string|max:255',
            'paymentInfo.PaymentDate' => 'required|date',
            'paymentInfo.TransactionID' => 'required|string|max:255',
        ];
    }
}
