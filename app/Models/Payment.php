<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'Amount',
        'PaymentMethod',
        'PaymentStatus',
        'PaymentDate',
        'TransactionID',
        'RentalID',
    ];

    protected $casts = [
        'PaymentDate' => 'date',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'RentalID');
    }
}
