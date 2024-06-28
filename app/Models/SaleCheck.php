<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'number',
        'amount',
        'date',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}