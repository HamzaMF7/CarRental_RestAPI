<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'Model',
        'Color',
        'Hybrid',
        'Electric',
        'AirConditioner',
        'RegistrationNumber',
        'Mileage',
        'GPSInstalled',
        'BluetoothEnabled',
        'InsuranceDetails',
        'MaintenanceHistory',
        'CarID',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
