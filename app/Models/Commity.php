<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commity extends Model
{
    use HasFactory;
    
    protected $fillable=[ 'id'  ,'email', 'phone','active','full_name', 'salary','avatar' ];  

}
