<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'Rating',
        'Comment',
        'DatePosted',
        'UserID',
        'CarID',
    ];

    protected $dates = [
        'DatePosted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'CarID');
    }
}
