<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function openShift(Request $request)
    {
        $shiftDate = $request->shift_date ?? date('Y-m-d');
        $existingShift = Shift::whereDate('shift_date', $shiftDate)
            ->whereNull('close_author')
            ->first();

        if ($existingShift) {
            return response()->json([
                'message' => 'Открытая смена на эту дату уже существует.'
            ], 400);
        }

        $shift = Shift::create([
            'shift_date' => $request->shift_date ?? date('Y-m-d'),
            'employee_ids' => null,
            'close_author' => null,
            'notes' => null,
        ]);

        return response()->json($shift, 201);
    }

    public function getShiftById($id)
    {
        $shift = Shift::findOrFail($id);
        return response()->json($shift, 201);
    }

    public function getShiftByDate(Request $request)
    {
        $shift = Shift::whereDate('shift_date', $request->shift_date)->first();
        return response()->json($shift, 201);
    }

    public function addEmployeeToShift(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);

        $shift = Shift::findOrFail($id);
        $employeeIds = (array)$shift->employee_ids;

        if ($shift->close_author) {
            return response()->json([
                'message' => 'Данная смена уже закрыта.'
            ], 400);
        }

        if (in_array($request->employee_id, $employeeIds)) {
            return response()->json([
                'message' => 'Данный сотрудник уже поставлен на эту смену.'
            ], 400);
        }

        $employeeIds[] = $request->employee_id;
        $shift->employee_ids = $employeeIds;
        $shift->save();

        return response()->json($shift);
    }

    public function addNoteToShift(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->notes .= ". " . $request->note;
        $shift->save();

        return response()->json($shift);
    }

    public function closeShift(Request $request, $id)
    {
        $request->validate([
            'close_author' => 'required|exists:employees,id'
        ]);

        $shift = Shift::findOrFail($id);

        if ($shift->close_author) {
            return response()->json([
                'message' => 'Данная смена уже закрыта.'
            ], 400);
        }

        $shift->close_author = $request->close_author;
        $shift->save();

        return response()->json($shift);
    }
}
