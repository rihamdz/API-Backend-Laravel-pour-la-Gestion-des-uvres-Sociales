<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minha extends Model
{
    use HasFactory;
    protected $fillable = [
        'offer_title',
        'first_name',
        'last_name',
        'phone',
        'second_phone',
        'corp',
        'rank',
        'justification',
        'date_employment',
        'state'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
