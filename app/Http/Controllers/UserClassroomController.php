<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserClassroom;
use App\Models\User;

class UserClassroomController extends Controller
{
    // Get all userclassroom records
    // public function index($student_id)
    // {
    //     // Retrieve all users that belong to the specified classroom
    //     $userclassroom = UserClassroom::where('student_id', $student_id)->get();

    //     if (!$userclassroom) {
    //         return response()->json(["message" => " not found"], 404);
    //     }

    //     return response()->json( 200);
    // }

//     public function index($student_id)
// {
//     // Retrieve the student's information based on their ID
//     $student = User::find($student_id);

//     if (!$student) {
//         return response()->json(["message" => "Student not found"], 404);
//     }

//     // Retrieve all users that belong to the specified classroom
//     $userclassroom = UserClassroom::where('student_id', $student_id)->get();

//     // Return the student's information and the users that belong to their classroom
//     return response()->json(["student" => $student, "users" => $userclassroom], 200);
// }

public function index($classroom_id)
{
    // Retrieve all users that belong to the specified classroom
    $userclassroom = UserClassroom::where('classroom_id', $classroom_id)->get();

    if (!$userclassroom->count()) {
        return response()->json(["message" => "No students found in the classroom"], 404);
    }

    // Retrieve information about each student in the classroom
    $students = [];
    foreach ($userclassroom as $uc) {
        $student = User::find($uc->student_id);
        if ($student) {
            $students[] = $student;
        }
    }

    // Return the students' information
    return response()->json(["students" => $students], 200);
}



    // Get a specific userclassroom record by ID
    public function show($id)
    {
       

        // $attendance = UserClassroom::where('student_id', $id)->get();

        // if (!$attendance) {
        //     return response()->json(["message" => " not found"], 404);
        // }

        // return response()->json($attendance, 200);
    }

    // Create a new userclassroom record
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'student_id' => 'required|exists:users,id,role,student',
        ]);

        $userclassroom = new UserClassroom();
        $userclassroom->classroom_id = $validatedData['classroom_id'];
        $userclassroom->student_id = $validatedData['student_id'];
        $userclassroom->save();

        return response()->json(['message' => 'UserClassroom record created successfully.']);
    }

    // Update an existing userclassroom record
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'classroom_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);

        $userclassroom = UserClassroom::findOrFail($id);
        $userclassroom->classroom_id = $validatedData['classroom_id'];
        $userclassroom->student_id = $validatedData['student_id'];
        $userclassroom->save();

        return response()->json(['message' => 'UserClassroom record updated successfully.']);
    }

    // Delete a userclassroom record by ID
    public function destroy($id)
    {
        $userclassroom = UserClassroom::findOrFail($id);
        $userclassroom->delete();

        return response()->json(['message' => 'UserClassroom record deleted successfully.']);
    }
}
