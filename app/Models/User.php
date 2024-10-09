<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'email', 'password' , 'otp'];

   
    protected $hidden = [
        
        'remember_token', 'confirmation_token' , 
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
      
    ];
    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    // Method to check if the user's role is 'predent'
  /*  public function isPredent()
    {
        // Check if the user has any roles with the type 'predent'
        return $this->roles()->where('type_role', 'predent')->exists();
    } */ 
}
