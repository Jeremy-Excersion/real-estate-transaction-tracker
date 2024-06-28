<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleSource extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // each source belongs to one sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}