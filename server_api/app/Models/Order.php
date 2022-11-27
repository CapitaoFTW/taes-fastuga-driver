<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
            'ticket_number',
            'status' ,
            'total_price',
    ];

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'P':
                return 'Preparing';
            case 'C':
                return 'Canceled';
            case 'R':
                return 'Ready';
            case 'D':
                return 'Delivered';
        }
    }

    

    public function delivered_by()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
}
