<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use SoftDeletes;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'nif',
        'license_plate',
        'phone_number',
    ];

    public function user() {
        return $this -> belongsTo(User:: class, 'id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivered_by', 'user_id');
    }
}
