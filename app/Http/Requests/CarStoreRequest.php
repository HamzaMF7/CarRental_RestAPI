<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarStoreRequest extends FormRequest
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
            'car.CarName' => ['required', 'string', 'max:255'],
            'car.Price' => ['required', 'numeric'],
            'car.Capacity' => ['required', 'integer'],
            'car.Image' => ['required'],
            'car.FuelType' => ['required', 'string', 'in:diesel,essence,electric,hybrid/essence,hybrid/diesel'],
            'car.TransmissionType' => ['required', 'string', 'max:255'],
            'car.CurrentStatus' => ['required', 'string', 'in:Available,Booked,Out for Rent,Under Maintenance,Returned,Unavailable,Damaged'],
            'car.CategoryID' => ['required', 'integer'],
            'car.BrandID' => ['required', 'integer'],
            'carDetails.Model' => ['required', 'string'],
            'carDetails.Color' => ['required', 'string'],
            'carDetails.Hybrid' => ['boolean'],
            'carDetails.Electric' => ['boolean'],
            'carDetails.AirConditioner' => ['required',],
            'carDetails.RegistrationNumber' => ['required', 'string'],
            'carDetails.Mileage' => ['required',],
            'carDetails.GPSInstalled' => ['required',],
            'carDetails.BluetoothEnabled' => [],
            'carDetails.InsuranceDetails' => ['text'],
            'carDetails.MaintenanceHistory' => ['text'],
        ];
    }
}
