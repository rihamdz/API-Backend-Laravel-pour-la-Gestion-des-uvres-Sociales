<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Offer extends Model
{
    use  HasFactory;
    protected $fillable=[
        'id'  ,'description', 'title','active'  ,   'categorie_title'];

        protected $casts = [
            'required_documents' => 'array',
        ];
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
