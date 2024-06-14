<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'CarName',
        'Price',
        'Capacity',
        'Image',
        'FuelType',
        'TransmissionType',
        'CurrentStatus',
        'CategoryID',
        'BrandID',
    ];


    public function carDetails()
    {
        return $this->hasOne(CarDetails::class, 'CarID');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class);
    }
}
