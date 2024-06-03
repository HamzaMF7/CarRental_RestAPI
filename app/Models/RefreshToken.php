<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id', 'token', 'expires_at', 'ip_address', 'user_agent',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
