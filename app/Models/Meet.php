<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'start_time',
        'duration',
        'invited',
    ];

   

    /**
     * The employees invited to the meeting.
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
