<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Traits\ClickHouseLoggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    use ClickHouseLoggable;

    public function index()
    {
        $this->logToClickHouse('book_index');
        $books = Book::with(['author', 'publisher'])->get();
        return response()->json($books);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publication_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'isbn' => 'required|string|unique:books|max:13',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('book_store_failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $book = Book::create($request->all());
        $this->logToClickHouse('book_store_success', ['book_id' => $book->id]);
        return response()->json($book, 200);
    }

    public function show($id)
    {
        $this->logToClickHouse('book_show', ['book_id' => $id]);
        $book = Book::with(['author', 'publisher'])->findOrFail($id);
        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'publication_year' => 'integer|min:1000|max:' . (date('Y') + 1),
            'isbn' => 'string|unique:books,isbn,' . $id . '|max:13',
            'author_id' => 'exists:authors,id',
            'publisher_id' => 'exists:publishers,id',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('book_update_failed', ['book_id' => $id, 'errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $book->update($request->all());
        $this->logToClickHouse('book_update_success', ['book_id' => $id]);
        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        $this->logToClickHouse('book_delete', ['book_id' => $id]);
        return response()->json(null, 200);
    }
}
