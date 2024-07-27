<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    public function index()
    {
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
            return response()->json($validator->errors(), 400);
        }

        $book = Book::create($request->all());
        return response()->json($book, 200);
    }

    public function show($id)
    {
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
            return response()->json($validator->errors(), 400);
        }

        $book->update($request->all());
        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(null, 200);
    }

    public function available()
    {
        $books = Book::where('is_borrowed', false)->get();
        return response()->json($books);
    }

    public function borrow($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Книга не найдена'], 404);
        }

        if ($book->is_borrowed) {
            return response()->json(['message' => 'Книга уже взята'], 400);
        }

        $book->is_borrowed = true;
        $book->save();

        return response()->json(['message' => 'Книга успешно взята'], 200);
    }
}
