<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftReport extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'reports';

    protected $fillable = [
        'shift_id',
        'shift_date',
        'employee_ids',
        'shift_author',
        'total_books_count',
        'borrowed_books_count',
        'returned_books_count',
        'books_on_repair_count',
        'available_books_count',
        'closed_at',
    ];


}
