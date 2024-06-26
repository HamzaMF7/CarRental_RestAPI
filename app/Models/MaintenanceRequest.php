<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'Description',
        'RequestDate',
        'Status',
        'CompletionDate',
        'CarID',
    ];

    protected $dates = [
        'RequestDate',
        'CompletionDate',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
