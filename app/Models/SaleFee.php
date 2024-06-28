<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleFee extends Model
{
    use HasFactory;

    protected $appends = ['split_type_name'];

    // define the split types enum
    const SPLIT_TYPES = [
        1 => 'Seller Concessions',
        2 => 'Seller Additions',
        3 => 'Pre-Team Fees',
        4 => 'Pre-Team Additions',
        5 => 'Post-Team Fees',
        6 => 'Post-Team Additions',
        7 => 'Referral Fee Percentage',
        8 => 'Flat Fee Commission',
    ];

    protected $fillable = [
        'sale_id',
        'fee_name',
        'fee_amount',
        'split_type',
    ];

    protected $casts = [
        'split_type' => 'integer',
    ];

    public function getSplitTypeNameAttribute()
    {
        return self::SPLIT_TYPES[$this->split_type] ?? 'Unknown';
    }

    // Define the relationship with Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}