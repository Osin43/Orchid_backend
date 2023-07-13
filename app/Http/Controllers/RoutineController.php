<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\Request;

use Illuminate\Http\Request;
use App\Models\Routine;

class RoutineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routines = Routine::all();

        return response()->json($routines);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id,role,teacher',
            'subject_id' => 'required|exists:subjects,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
        ]);

        $routine = new Routine([
            'classroom_id' => $request->classroom_id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'day' => $request->day,
        ]);

        $routine->save();

        return response()->json(['message' => 'Routine created successfully', 'routine' => $routine]);
    }

    //     public function show($classroom_id) 
    // {
    //     $routines = Routine::where('classroom_id', $classroom_id)->get();

    //     if (!$routines->count()) {
    //         return response()->json(["message" => "No routines found for the classroom"], 404);
    //     }

    //     return response()->json($routines, 200);
    // }

    // public function show($classroom_id) 
    // {
    //     $routines = Routine::with('subject', 'teacher')
    //                 ->where('classroom_id', $classroom_id)
    //                 ->get();

    //     if (!$routines->count()) {
    //         return response()->json(["message" => "No routines found for the classroom"], 404);
    //     }

    //     return response()->json($routines, 200);
    // }

    public function show($classroom_id)
    {
        $routines = Routine::with('subject', 'teacher')
            ->where('classroom_id', $classroom_id)
            ->get();

        if (!$routines->count()) {
            return response()->json(["message" => "No routines found for the classroom"], 404);
        }

        // Map over the routines to add the subject and teacher name to each routine
        $routines = $routines->map(function ($routine) {
            $routine->subject_name = $routine->subject->name;
            $routine->teacher_name = $routine->teacher->name;
            unset($routine->subject);
            unset($routine->teacher);
            return $routine;
        });

        return response()->json($routines, 200);
    }

    public function filterByDay(Request $request)
    {
        // Validate the request data
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
        ]);

        $classroom_id = $request->classroom_id;
        $day = $request->day;

        $routines = Routine::with('subject', 'teacher')
            ->where('classroom_id', $classroom_id)
            ->where('day', $day)
            ->get();

        if (!$routines->count()) {
            return response()->json(["message" => "No routines found for the classroom and day"], 404);
        }

        // Map over the routines to add the subject and teacher name to each routine
        $routines = $routines->map(function ($routine) {
            $routine->subject_name = $routine->subject->name;
            $routine->teacher_name = $routine->teacher->name;
            unset($routine->subject);
            unset($routine->teacher);
            return $routine;
        });

        return response()->json($routines, 200);
    }
}
//     public function generate(Request $request)
//     {
//         $days = ['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

//         $times = [
//             '9:00 AM - 10:00 AM',
//             '10:15 AM - 11:15 AM',
//             '11:30 AM - 12:30 PM',
//             '1:00 PM - 2:00 PM',
//             '2:15 PM - 3:15 PM',
//             '3:30 PM - 4:30 PM',
//         ];

//         $classes = [
//             'Class 1',
//             'Class 2',
//             'Class 3',
//             'Class 4',
//             'Class 5',
//             'Class 6',
//             'Class 7',
//             'Class 8',
//             'Class 9',
//             'Class 10',
//         ];

//         $teachers = [
//             'Teacher 1',
//             'Teacher 2',
//             'Teacher 3',
//             'Teacher 4',
//             'Teacher 5',
//             'Teacher 6',
//             'Teacher 7',
//             'Teacher 8',
//             'Teacher 9',
//             'Teacher 10',
//         ];
// for ($i = 1; $i <= 7; $i++){
//         foreach ($days as $day) {
//             foreach ($times as $time) {
//                 $routine = new Routine;
//                 $routine->day = $day;
//                 $routine->time = $time;
//                 $routine->class = $classes[array_rand($classes)];
//                 $routine->teacher = $teachers[array_rand($teachers)];
//                 $routine->save();
//             }
//         }
//     }

//         return response()->json([
//             'message' => 'Routine generated successfully',
//         ]);
//     }

//     public function index()
//     {
//         $routines = Routine::all();
//         return response()->json($routines);
//     }
// }
