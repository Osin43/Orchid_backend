<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
// use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        // $attendances = Attendance::with('users')->get();

        // return response()->json([
        //     'data' => $attendances,
        // ]);

        $attendances = Attendance::all();

        return response()->json($attendances, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:users,id,role,student',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        $attendance = Attendance::create($validatedData);

        return response()->json([
            'data' => $attendance,
        ]);
    }

    public function summary($student_id)
    {
        $attendance = DB::table('attendances')
            ->selectRaw('COUNT(CASE WHEN status = "present" THEN 1 END) AS present_count')
            ->selectRaw('COUNT(CASE WHEN status = "absent" THEN 1 END) AS absent_count')
            // ->where('date', '=', date('Y-m-d'))
            ->where('student_id', '=', $student_id)
            ->first();

        return response()->json($attendance);
    }

    public function show($id)
    {
        $attendance = Attendance::where('student_id', $id)->get();

        if (!$attendance) {
            return response()->json(["message" => " not found"], 404);
        }

        return response()->json($attendance, 200);
    }

    public function Attendanceshow($student_id)
    {
        $attendance = User::where('student_id', $student_id)->get();
        $attendance = Attendance::where('student_id', $student_id)
            ->get(['date', 'status']);

        if (!$attendance) {
            return response()->json(["message" => " not found"], 404);
        }

        return response()->json($attendance, 200);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        $attendance->update($validatedData);

        return response()->json([
            'data' => $attendance,
        ]);
    }
    public function getAttendance($studentId)
    {
        $attendance = Attendance::where('student_id', $studentId)->get();
        return response()->json($attendance);
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'message' => 'Attendance deleted successfully',
        ]);
    }

    public function import(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        $file = $validatedData['file'];
        $csvData = array_map('str_getcsv', file($file));
        $header = array_shift($csvData);
        $attendanceData = [];
        foreach ($csvData as $row) {
            $attendanceData[] = array_combine($header, $row);
        }
        $importedCount = 0;
        foreach ($attendanceData as $data) {
            if (isset($data['student_id'])) {
                $attendance = Attendance::where('student_id', $data['student_id'])
                    ->where('date', $data['date'])
                    ->first();
                if (!$attendance) {
                    $attendance = new Attendance();
                }
                $attendance->student_id = $data['student_id'];
                $attendance->date = $data['date'];
                $attendance->status = $data['status'];
                $attendance->save();
                $importedCount++;
            }
        }

        return response()->json([
            'message' => "$importedCount records imported successfully",
        ]);
    }

    public function filter(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'nullable',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1970|max:9999',
        ]);

        $query = Attendance::query();

        if ($validatedData['student_id']) {
            $query->where('student_id', $validatedData['student_id']);
        }

        $query->whereMonth('date', $validatedData['month'])
            ->whereYear('date', $validatedData['year']);

        $attendances = $query->get();

        return response()->json($attendances, 200);
    }


    public function export()
    {
        $attendances = Attendance::with('student')->get();

        $csvData = [];

        $csvData[] = ['ID', 'Student Name', 'Date', 'Status'];

        foreach ($attendances as $attendance) {
            $csvData[] = [
                $attendance->id,
                $attendance->student->name,
                $attendance->date,
                $attendance->status,
            ];
        }




        $filename = 'attendances-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
