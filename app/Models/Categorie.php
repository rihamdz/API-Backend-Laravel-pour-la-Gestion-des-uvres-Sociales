<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable=[ 'id'  ,'title'];  
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
