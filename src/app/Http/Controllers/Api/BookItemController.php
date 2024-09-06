<?php

namespace App\Http\Controllers\Api;

use App\Models\BookItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BookItemController extends Controller
{
    public function index()
    {
        $bookItems = BookItem::all();
        return response()->json($bookItems);
    }

    public function show($id)
    {
        $bookItem = BookItem::findOrFail($id);
        return response()->json($bookItem);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'exists:books,id',
            'isbn' => 'unique|string',
            'publication_year' => 'integer',
            'publisher_id' => 'exists:publishers,id',
            'last_returned_at' => 'nullable|date',
            'condition' => 'integer',
            'is_borrowed' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookItem = BookItem::create($request->validated());

        return response()->json($bookItem, 200);
    }

    public function update(Request $request, $id)
    {
        $bookItem = BookItem::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'book_id' => 'exists:books,id',
            'isbn' => 'unique|string',
            'publication_year' => 'integer',
            'publisher_id' => 'exists:publishers,id',
            'last_returned_at' => 'nullable|date',
            'condition' => 'integer',
            'is_borrowed' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookItem->update($request->validated());

        return response()->json($bookItem, 200);
    }

    public function destroy(BookItem $bookItem)
    {
        $bookItem->delete();
        return response()->json(null, 200);
    }
    public function borrow($id)
    {
        $bookItem = BookItem::findOrFail($id);

        if ($bookItem->is_borrowed) {
            return response()->json('Книга уже взята', 400);
        }

        if ($bookItem->condition === BookItem::CONDITION_ON_REPAIR) {
            return response()->json('Книга в ремонте и не может быть взята', 400);
        }

        $bookItem->is_borrowed = true;
        $bookItem->last_borrowed_at = now();
        $bookItem->save();

        return response()->json('Книга успешно взята', 200);
    }

    public function return($id)
    {
        $bookItem = BookItem::findOrFail($id);

        if (!$bookItem->is_borrowed) {
            return response()->json('Книга не была взята', 400);
        }

        $bookItem->is_borrowed = false;
        $bookItem->last_returned_at = now();
        $bookItem->save();

        return response()->json('Книга успешно возвращена', 200);
    }

    public function availableBookItemsForBook($id)
    {
        $bookItems = BookItem::where('condition', '!=', BookItem::CONDITION_ON_REPAIR)
            ->where('is_borrowed', false)
            ->where('book_id', $id)
            ->get();

        return response()->json($bookItems, 200);
    }

}
