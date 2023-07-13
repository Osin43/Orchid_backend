<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Auth;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();

        return response()->json($user, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function Teacher(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required|in:male,female,others',
            'mobile_number' => 'required|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|unique:users',
            'password' => 'required|min:8|max:20|confirmed',
            'email' => 'required|email|unique:users',
            'classroom_id' => 'required|exists:classrooms,id',
            'role' => 'required|in:teacher',
        ]);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'classroom_id' => $request->classroom_id,
            'mobile_number' => "977" . $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,

            'is_admin' => false,
        ]);

        return response()->json(['message' => 'User created successfully', 'User' => $user]);
    }

    public function Parent(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'address' => 'required',
            'mobile_number' => 'required|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|unique:users',
            'password' => 'required|min:8|max:20|confirmed',
            'email' => 'required|email|unique:users',
            'student_id' => 'required|exists:users,id,role,student',
            'role' => 'required|in:parent',


        ]);

        $user = User::create([
            'name' => $request->name,
            'address' => $request->address,

            'student_id' => $request->student_id,
            'mobile_number' => "977" . $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,

            'is_admin' => false,
        ]);



        return response()->json(['message' => 'User created successfully', 'User' => $user]);
    }
    public function Student(Request $request1)
    {
        $request1->validate([
            'name' => 'required',
            'gender' => 'required|in:male,female,others',
            'address' => 'required',
            'classroom_id' => 'required|numeric',
            'mobile_number' => 'required|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|unique:users',
            'password' => 'required|min:8|max:20|confirmed',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:student',
            'dob' => 'required|date',



            'classroom_id' => 'nullable|exists:classrooms,id',

        ]);

        // return $request;

        $user = User::create([
            'name' => $request1->name,
            'gender' => $request1->gender,
            'address' => $request1->address,
            'dob' => $request1->dob,
            'classroom_id' => $request1->classroom_id,
            'mobile_number' => "977" . $request1->mobile_number,
            'email' => $request1->email,
            'password' => Hash::make($request1->password),
            'role' => $request1->role,
            // 'satus' => 'pending',
            'is_admin' => false,
        ]);
        $response = [
            "status" => 200,
            "data" => "$user",
            "message" => "User Account Created Successfully",
            // "token"=>$token,

        ];
        try {

            // Response if user created successfully
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(["error" => "An error occurred while processing your request"], 500);
        }
    }

    // public function getUsersByClassroom($classroom_id)
    // {
    //     $users = User::where('classroom_id', $classroom_id)->get();
    //     return response()->json(['classroom' => $classroom_id]);
    // }
    //get user count by classroom
    public function getUserCountclass($classroom_id)
    {
        $userCount = User::where('classroom_id', $classroom_id)
            ->where('role', 'student')
            ->count();
        return response()->json(['user_count' => $userCount]);
    }





    // public function userindex($classroom_id)
    // {
    //     $users = User::where('classroom_id', $classroom_id)->get(['name', 'dob', 'address', 'role', 'gender']);
    //     return response()->json(['users' => $users]);
    // }

    public function userindex($classroom_id)
    {
        $users = User::where('classroom_id', $classroom_id)
            ->where('role', 'student')
            ->get(['id', 'name', 'dob', 'address', 'role', 'gender', 'mobile_number']);
        return response()->json(['users' => $users]);
    }

    public function getUsername($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['name' => $user->name]);
    }
    public function classroom($studentId)
    {
        $student = User::find($studentId);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $classroomId = $student->classroom_id;

        $classroom = Classroom::find($classroomId);

        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], 404);
        }

        return response()->json(['classroom_id' => $classroomId]);
    }

    public function getstudentid($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['student_id' => $user->student_id]);
    }

    public function getUserCountByRole()
    {
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $parentCount = User::where('role', 'parent')->count();

        return response()->json([
            'student_count' => $studentCount,
            'teacher_count' => $teacherCount,
            'parent_count' => $parentCount,
        ]);
    }

    public function showUser($id)
    {
        $users = User::where('id', $id)->get();

        if (!$users) {
            return response()->json(["message" => " not found"], 404);
        }

        return response()->json($users, 200);
    }




    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        $user = User::where('email', $request->email)->first();
        // $user = User::where('role', $request->role)->first();

        if (!$user || !Hash::check($request->password, $user->password,)) {
            return response()->json(["message" => "Invalid value provided"], 404);
        }

        if ($user->banned) {
            return response()->json(["message" => 'You have been banned from the system. Please contact support for assistance.'], 401);
        }


        $token = $user->createToken($request->email)->plainTextToken;
        $response = [
            "status" => true,
            "user" => $user,
            "token" => $token,


        ];
        return response()->json($response, 200);
    }

    // public function logout(Request $req)
    // {
    //     /**@var User $user */
    //     $user = $req->user();
    //     $user->ACCESSTOKEN()->delete;
    //     return response('', 204);
    // }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "User logged out successfully."]);
    }


    public function show($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $classroom_id = $user->classroom_id;
        return response()->json(["classroom_id" => $classroom_id], 200);
    }

    public function Profileshow($id)
    {
        $user = User::find($id);


        if (!$user) {
            return response()->json(["message" => " not found"], 404);
        }

        return response()->json($user, 200);
    }

    public function getClassroom()
    {
        $user = Auth::user();

        // Assuming the user model has a classroom_id field
        $classroomId = $user->classroom_id;

        return response()->json(['classroom_id' => $classroomId]);
    }

    public function getStudent($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $student_id = $user->student_id;
        return response()->json(["student_id" => $student_id], 200);
    }

    public function approveUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->status = 'approved';
        $user->approved_at = now();
        $user->save();

        return response()->json([
            'message' => 'User approved successfully'
        ], 200);
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $user->delete();
        $successResponse = ["message" => "User deleted successfully"];
        return response()->json($successResponse, 200);
    }






    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'role'=>'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();
    //     $user = User::where('role', $request->role)->first();

    //     if (!$user || !Hash::check($request->password, $user->password, )) {
    //         return response()->json(["message" => "Invalid value provided"], 404);
    //     }


    //     $token = $user->createToken($request->email)->plainTextToken;
    //     $response = [
    //         "status" => true,
    //         "user" => $user,
    //         "token"=>$token,


    //     ];
    //     return response()->json($response, 200);
    // }
    public function counts()
    {
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $parentCount = User::where('role', 'parent')->count();

        return response()->json([
            'teacherCount' => $teacherCount,
            'studentCount' => $studentCount,
            'teacherCount' => $parentCount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json(["message" => "User not found"], 404);
    //     }

    //     return response()->json($user, 200);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json(User::whereId($id)->first());
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

        $request->validate([
            'name' => 'nullable',
            'gender' => 'nullable|in:male,female,others',
            'address' => 'nullable',
            'mobile_number' => 'nullable|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10',
            // 'role' => 'nullable|in:teacher,student,parent,accountant',
        ]);
        $user = User::find($id);
        $user->name = $request->name ? $request->name : $user->name;
        $user->gender = $request->gender ? $request->gender : $user->gender;
        $user->address = $request->address ? $request->address : $user->address;
        $user->mobile_number = $request->mobile_number ? $request->mobile_number : $user->mobile_number;
        // $user->role = $request->role ? $request->role : $user->role;
        $user->update();

        $errResponse = [
            "status" => false,
            "message" => "Update error"
        ];

        if (!$user) {
            return response()->json($errResponse, 404);
        }

        $successResponse = [
            "status" => true,
            "message" => "Profile Updated Successfully"
        ];

        return response()->json($successResponse, 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         return response()->json(["message" => "User not found"], 404);
    //     }
    //     $user->delete();
    //     $successResponse = ["message" => "User deleted successfully"];
    //     return response()->json($successResponse, 200);
    // }

    public function banUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->banned = true;
        $user->save();
        return response()->json(['message' => 'User banned successfully'], 200);
    }
}
