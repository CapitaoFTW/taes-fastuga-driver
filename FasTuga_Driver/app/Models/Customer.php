<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use SoftDeletes;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'points',
        'nif',
        'default_payment_type',
        'default_payment_reference',
    ];

    public function user() {
        return $this -> belongsTo(User:: class, 'id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivered_by');
    }
}
