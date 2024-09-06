<?php

namespace App\Helpers;

use App\Models\BookItem;

class ShiftReportHelper
{
    public static function getBorrowedBooksCount($date)
    {
        return BookItem::where('is_borrowed', true)
            ->whereDate('last_borrowed_at', $date)
            ->count();
    }

    public static function getReturnedBooksCount($date)
    {
        return BookItem::where('is_borrowed', false)
            ->whereDate('last_returned_at', $date)
            ->count();
    }

    public static function getBooksOnRepairCount()
    {
        return BookItem::where('condition', BookItem::CONDITION_ON_REPAIR)->count();
    }

    public static function getTotalBooksCount()
    {
        return BookItem::count();
    }

    public static function getAvailableBooksCount()
    {
        return BookItem::where('is_borrowed', false)
            ->where('condition', '!=', BookItem::CONDITION_ON_REPAIR)
            ->count();
    }
}
