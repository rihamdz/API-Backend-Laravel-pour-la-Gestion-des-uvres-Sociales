<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;
   
    protected $fillable=[
        'id'  ,'offer_id', 'user_id','required_docs' , 'date'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
