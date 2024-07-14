<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::all();
        return response()->json($publishers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:publishers',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $publisher = Publisher::create($request->all());
        return response()->json($publisher, 200);
    }

    public function show($id)
    {
        $publisher = Publisher::findOrFail($id);
        return response()->json($publisher);
    }

    public function update(Request $request, $id)
    {
        $publisher = Publisher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'address' => 'string|max:255',
            'phone' => 'string|max:20',
            'email' => 'email|max:255|unique:publishers,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $publisher->update($request->all());
        return response()->json($publisher);
    }

    public function destroy($id)
    {
        $publisher = Publisher::findOrFail($id);
        $publisher->delete();
        return response()->json(null, 200);
    }
}
