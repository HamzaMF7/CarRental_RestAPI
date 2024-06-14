<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;



    protected $fillable = [
        'CarID',
        'UserID',
        'StartDate',
        'EndDate',
        'TotalCost',
        'Status',
        'AdditionalRequirements',
        'PhoneNumber',
        'City',
        'PickupLocationID',
        'ReturnLocationID',
    ];

    protected $dates = [
        'StartDate',
        'EndDate',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'CarID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'PickupLocationID');
    }

    public function returnLocation()
    {
        return $this->belongsTo(Location::class, 'ReturnLocationID');
    }

}
