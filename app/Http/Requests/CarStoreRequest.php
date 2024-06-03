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
            'CarName' => ['required', 'string', 'max:255'],
            'Price' => ['required', 'numeric'],
            'Capacity' => ['required', 'integer'],
            'Image' => ['required'],
            'FuelType' => ['required', 'string', 'in:diesel,essence,electric,hybrid/essence,hybrid/diesel'],
            'TransmissionType' => ['required', 'string', 'max:255'],
            'CurrentStatus' => ['required', 'string', 'in:Available,Booked,Out for Rent,Under Maintenance,Returned,Unavailable,Damaged'],
            'CategoryID' => ['required', 'integer'],
            'BrandID' => ['required', 'integer'],
        ];
    }
}
