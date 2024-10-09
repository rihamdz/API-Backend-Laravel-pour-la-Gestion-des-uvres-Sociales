<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use Notifiable;
    use HasFactory,HasApiTokens;
  
    protected $fillable=[ 'id'  ,'email', 'phone','active','full_name', 'salary','avatar' ];  
    
    public function meetings()
    {
        return $this->belongsToMany(Meeting::class);
    }

    /*
    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    // Method to check if the user's role is 'predent'
    public function isPredent()
    {
        // Check if the user has any roles with the type 'predent'
        return $this->roles()->where('type_role', 'predent')->exists();
    } */
}
