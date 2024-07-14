<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends User
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
