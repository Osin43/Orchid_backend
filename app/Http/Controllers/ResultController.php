<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\User;
use App\Models\Subject;


class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Result::all();

        return response()->json($result, 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'subject_id' => 'required',
            'grade' => 'required',
            'exam_id' => 'required',
            'marks' => 'required|numeric|min:0|max:100',
        ]);

        Result::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'marks' => $request->marks,
            'grade' =>  $request->grade,
            'exam_id' =>  $request->exam_id,

        ]);


        $response = [
            "status" => 200,
            "message" => "Result added Successfully",
        ];
        return response()->json($response);
    }
    public function showStudent($studentId)
    {
        $student = User::find($studentId);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $result = Result::where('student_id', $studentId)->get();

        return response()->json($result);
    }


    public function import(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $validatedData['file'];
        $csvData = array_map('str_getcsv', file($file));
        $header = array_shift($csvData);
        $resultData = [];

        foreach ($csvData as $row) {
            $resultData[] = array_combine($header, $row);
        }

        $importedCount = 0;

        foreach ($resultData as $data) {
            if (isset($data['student_id'])) {
                $result = Result::where('student_id', $data['student_id'])
                    // ->where('date', $data['date'])
                    ->first();

                if (!$result) {
                    $result = new Result();
                }

                $result->student_id = $data['student_id'];
                // $result->date = $data['date'];
                // $result->status = $data['status'];
                // 
                // Additional fields for marks, grade, total, exam ID, subject ID, percentage
                $result->marks = $data['marks'];
                $result->grade = $data['grade'];
                $result->total = $data['total'];
                $result->exam_id = $data['exam_id'];
                $result->subject_id = $data['subject_id'];
                $result->percentage = $data['percentage'];

                $result->save();
                $importedCount++;
            }
        }

        return response()->json([
            'message' => "$importedCount records imported successfully",
        ]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request, $student_id)
    // {
    //     $request->validate([
    //         'subject_id' => 'required|exists:subjects,id',
    //         'grade' => 'required|numeric|min:0|max:100',
    //     ]);

    //     $result = Result::create([
    //         'student_id' => $student_id,
    //         'subject_id' => $request->input('subject_id'),
    //         'grade' => $request->input('grade'),
    //     ]);

    //     return response()->json($result, 201);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */

    public function viewResults($userId)
    {
        // $results = Result::where('student_id', $userId)->get();

        // return response()->json([
        //     'results' => $results,
        // ]);


        $results = Result::where('student_id', $userId)->get();

        $resultsWithSubjectName = $results->map(function ($result) {
            $subject = Subject::find($result->subject_id);
            $result->subject_name = $subject ? $subject->name : null;
            return $result;
        });

        return response()->json([
            'results' => $resultsWithSubjectName,
        ]);


        // $results = Result::where('student_id', $userId)->get()->toArray();

        // if (!is_array($results)) {
        //     // Handle the case when $results is not an array
        //     return response()->json([
        //         'error' => 'No results found for the specified user ID.',
        //     ]);
        // }

        // return response()->json([
        //     'results' => $results,
        // ]);


        // $results = Result::where('student_id', $userId)->get()->toArray();

        // return response()->json([
        //     'results' => $results,
        // ]);
    }

    public function show($id)
    {
        $attendance = Result::where('student_id', $id)->get();

        if (!$attendance) {
            return response()->json(["message" => " not found"], 404);
        }

        return response()->json($attendance, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
