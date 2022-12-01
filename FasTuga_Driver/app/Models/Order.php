<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'status',
        'total_price',
        'street_address',
        'quantity',
        'delivered_by',
    ];

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'P':
                return 'Preparing';
            case 'C':
                return 'Cancelled';
            case 'R':
                return 'Ready';
            case 'D':
                return 'Delivered';
            case 'O':
                return 'Ongoing';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class,  'delivered_by', 'id');
    }
}