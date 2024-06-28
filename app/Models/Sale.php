<?php

namespace App\Models;

use App\Models\SaleFee;
use App\Models\SaleCheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'client_name',
        'client_email',
        'address',
        'city',
        'state',
        'zip',
        'asking_price',
        'sold_price',
        'percentage',
        'agent_split_percentage',
        'pending_date',
        'closing_date',
        'status',
        'buyer',
        'notes',
    ];

    // Define the relationship with SaleFee
    public function fees()
    {
        return $this->hasMany(SaleFee::class);
    }

    // Define the relationship with SaleCheck
    public function checks()
    {
        return $this->hasMany(SaleCheck::class);
    }

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with SaleSource - each sale can have many sources
    public function sources()
    {
        return $this->hasMany(SaleSource::class);
    }
}
