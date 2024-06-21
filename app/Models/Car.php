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
        return $this->belongsTo(Category::class, 'CategoryID');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'BrandID');
    }

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'CarID');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'CarID');
    }
}
