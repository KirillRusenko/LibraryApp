<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_date',
        'employee_ids',
        'close_author',
        'notes'
    ];

    protected $casts = [
        'employee_ids' => 'array',
    ];

    public function closeAuthor()
    {
        return $this->belongsTo(Employee::class, 'close_author');
    }
}
