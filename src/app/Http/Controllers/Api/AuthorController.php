<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Traits\ClickHouseLoggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    use ClickHouseLoggable;

    public function index()
    {
        $this->logToClickHouse('author_index');
        $authors = Author::all();
        return response()->json($authors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'biography' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('author_store', ['errors' => $validator->errors()]);
            return response()->json($validator->errors(), 400);
        }

        $author = Author::create($request->all());
        $this->logToClickHouse('author_store', ['author_id' => $author->id]);
        return response()->json($author, 200);
    }

    public function show($id)
    {
        $this->logToClickHouse('author_show', ['author_id' => $id]);
        $author = Author::findOrFail($id);
        return response()->json($author);
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'surname' => 'string|max:255',
            'birth_date' => 'date',
            'biography' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('author_update', ['author_id' => $id, 'errors' => $validator->errors()]);
            return response()->json($validator->errors(), 400);
        }

        $author->update($request->all());
        $this->logToClickHouse('author_update', ['author_id' => $id]);
        return response()->json($author);
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
        $this->logToClickHouse('author_destroy', ['author_id' => $id]);
        return response()->json(null, 200);
    }
}
