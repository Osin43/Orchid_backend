<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateGrade($attribute, $value, $parameters)
    {
        if (!is_numeric($value)) {
            return false;
        }

        $minGrade = isset($parameters[0]) ? (int) $parameters[0] : 0;
        $maxGrade = isset($parameters[1]) ? (int) $parameters[1] : 100;

        if ($value < $minGrade || $value > $maxGrade) {
            return false;
        }

        $letterGrade = '';

        if ($value >= 90 && $value <= 100) {
            $letterGrade = 'A+';
        } elseif ($value >= 80 && $value < 90) {
            $letterGrade = 'A';
        } elseif ($value >= 70 && $value < 80) {
            $letterGrade = 'B';
        } elseif ($value >= 60 && $value < 70) {
            $letterGrade = 'C';
        } elseif ($value >= 50 && $value < 60) {
            $letterGrade = 'D';
        } else {
            $letterGrade = 'F';
        }

        $this->setCustomMessages([
            'grade' => 'The :attribute must be between ' . $minGrade . ' and ' . $maxGrade . ' and have a letter grade of ' . $letterGrade
        ]);

        return true;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
