<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::all();

        return response()->json([
            'success' => true,
            'data' => $classrooms
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|in:Class 1,Class 2,Class 3,Class 4,Class 5,Class 6',




        ]);

        // return $request;

        $classroom = Classroom::create([
            'name' => $request->name,
            // 'teacher_id' => $request->teacher_id,

        ]);

        $response = [
            "status" => 200,
            "message" => "classroom posted",
        ];
        return response()->json($response);

        // Save the subject to the database
    }


    public function store(Request $request)
    {
        $classroom = Classroom::create([
            'name' => $request->name,
            // 'teacher_id' => $request->teacher_id
        ]);

        return response()->json([
            'success' => true,
            'data' => $classroom
        ]);
    }



    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $classroom->name = $request->name ?? $classroom->name;
        // $classroom->teacher_id = $request->teacher_id ?? $classroom->teacher_id;

        $classroom->save();

        return response()->json([
            'success' => true,
            'data' => $classroom
        ]);
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);

        $classroom->delete();

        return response()->json([
            'success' => true,
            'message' => 'Classroom deleted successfully'
        ]);
    }
}
