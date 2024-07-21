<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Traits\ClickHouseLoggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    use ClickHouseLoggable;

    public function index()
    {
        $this->logToClickHouse('publisher_index');
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
            $this->logToClickHouse('publisher_store_failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $publisher = Publisher::create($request->all());
        $this->logToClickHouse('publisher_store_success', ['publisher_id' => $publisher->id]);
        return response()->json($publisher, 200);
    }

    public function show($id)
    {
        $this->logToClickHouse('publisher_show', ['publisher_id' => $id]);
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
            $this->logToClickHouse('publisher_update_failed', ['publisher_id' => $id, 'errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $publisher->update($request->all());
        $this->logToClickHouse('publisher_update_success', ['publisher_id' => $id]);
        return response()->json($publisher);
    }

    public function destroy($id)
    {
        $publisher = Publisher::findOrFail($id);
        $publisher->delete();
        $this->logToClickHouse('publisher_delete', ['publisher_id' => $id]);
        return response()->json(null, 200);
    }
}
