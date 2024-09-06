<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookItem extends Model
{
    use HasFactory;

    const CONDITION_GOOD = 1;
    const CONDITION_FAIR = 2;
    const CONDITION_POOR = 3;
    const CONDITION_ON_REPAIR = 4;

    protected $fillable = [
        'book_id',
        'isbn',
        'publication_year',
        'publisher_id',
        'last_returned_at',
        'last_borrowed_at',
        'condition',
        'is_borrowed'
    ];

    protected $casts = [
        'last_returned_at' => 'datetime',
        'last_borrowed_at' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
