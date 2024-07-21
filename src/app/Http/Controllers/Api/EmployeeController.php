<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Traits\ClickHouseLoggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    use ClickHouseLoggable;

    public function index()
    {
        $this->logToClickHouse('employee_index');
        $employees = Employee::all();
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('employee_store_failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $employee = Employee::create($request->all());
        $this->logToClickHouse('employee_store_success', ['employee_id' => $employee->id]);
        return response()->json($employee, 201);
    }

    public function show($id)
    {
        $this->logToClickHouse('employee_show', ['employee_id' => $id]);
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'position' => 'string|max:255',
            'phone' => 'string|max:20',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('employee_update_failed', ['employee_id' => $id, 'errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $employee->update($request->all());
        $this->logToClickHouse('employee_update_success', ['employee_id' => $id]);
        return response()->json($employee);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        $this->logToClickHouse('employee_delete', ['employee_id' => $id]);
        return response()->json(null, 204);
    }
}
