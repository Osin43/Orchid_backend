<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {

        $subjects = Subject::all();

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            // 'user_id' => 'required|exists:teachers,id',
        ]);
        Subject::create([
            'name' => $request->name,
        ]);
        $response = [
            "status" => 200,
            "message" => "Subject posted",
        ];
        return response()->json($response);

        // Save the subject to the database
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $id)
    {
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
     * 
     * 
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $subject = Subject::create($validatedData);

        return response()->json([
            'message' => 'Subject created successfully',
            'data' => $subject,
        ]);
    }
    public function destroy($id)
    {
        //
    }
}
