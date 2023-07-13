<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserUploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
// use App\Http\Controllers\HeadTeacherController;
use App\Http\Controllers\HeadTeacherController;
use App\Http\Controllers\UserClassroomController;
use App\Models\Announcement;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/api/upload', 'UploadController@upload');
Route::post('/addroutine', [App\Http\Controllers\RoutineController::class, 'create']);
Route::post('/anounce', [App\Http\Controllers\AnnouncementController::class, 'create']);
Route::get('/showanounce', [App\Http\Controllers\AnnouncementController::class, 'index']);
Route::get('/result', [App\Http\Controllers\ResultController::class, 'create']);
Route::post('/subject', [SubjectController::class, 'store']);
Route::post('/postclassroom', [ClassroomController::class, 'store']);



Route::controller(UserController::class)->group(function () {
    Route::post('/signup', 'Student');
    Route::post('/parent', 'Parent');
    Route::post('/teacher', 'Teacher');
    Route::post('/login', 'login');
    // Route::get('/getclassroom/{classroom_id}', 'getUsersByClassroom');
    Route::get('/getclassroom/{classroom_id}', 'userindex');
    Route::get('/class/{id}', 'show');
    Route::get('/profile/{id}', 'Profileshow');
    Route::get('/approve/{id}', 'approveUser');
    Route::get('/username/{id}', 'getUsername');
    Route::get('/studentid/{id}', 'getstudentid');
    Route::get('/usercount/class/{id}', 'getUserCountclass');
    Route::get('/userrole', 'getUserCountByRole');
    Route::get('/user/all', 'index');
    Route::get('/oneuser/{id}', 'showUser');
    Route::delete('/ban/{id}', 'destroy');
    Route::get('/student/{id}', 'getStudent');
    Route::get('/students/{studentId}/classroom', 'classroom');
    Route::get('/user/classroom', 'getClassroom');
});
// routes/api.php
// Route::get('/user/classroom', '\UserController@getClassroom')->name('user.classroom');





Route::controller(AnnouncementController::class)->group(function () {

    Route::post('/announce', 'create');
    Route::get('/showannounce ', 'index');
});
Route::controller(ClassroomController::class)->group(function () {

    Route::get('/classes', 'index');
    // Route::get('/showannounce ', 'index');
});

Route::controller(SubjectController::class)->group(function () {

    Route::get('/subjects', 'index');
    // Route::get('/showannounce ', 'index');
});


Route::controller(StudentController::class)->group(function () {

    Route::post('/student', 'store');
    Route::get('/showannounce', 'index');
    // Route::get('/class/{user_id}','show');


});

Route::controller(ResultController::class)->group(function () {

    Route::post('/result', 'store');
    Route::get('/showresult', 'index');
    Route::post('/import/result', 'import');
    Route::get('/view/{student_id}', 'viewResults');
});
Route::post('/results', [ResultController::class, 'store']);
Route::get('/students/{studentId}/result', 'ResultController@show');



Route::controller(AttendanceController::class)->group(function () {

    Route::post('/attend', 'import');
    Route::get('/show', 'index');
    Route::get('/show/{student_id}', 'show');
    Route::get('/count/{student_id}', 'summary');
    Route::get('/attendances/filter', 'filter');
    Route::get('/at/{studnet_id}', 'getStudentAttendance');
    Route::get('/Attendanceshow/{student_id}', 'Attendanceshow');
});
Route::controller(SubjectController::class)->group(function () {

    Route::post('/sub', 'create');
    Route::get('/subject', 'index');
});
Route::controller(ClassroomController::class)->group(function () {

    Route::post('/addclass', 'store');
});

Route::controller(UserClassroomController::class)->group(function () {

    Route::post('/userclass', 'store');
    // Route::get('/class/{id}','show');
    Route::get('/class/{classroom_id}/users', 'index');
});

Route::get('/users/count', function () {
    return response()->json([
        'count' => \App\Models\User::countUsers()
    ]);
});

Route::get('/users/count/{role}', function ($role) {
    return response()->json([
        'count' => \App\Models\User::countUsersByRole($role)
    ]);
});
Route::post('/attendance/filter', [AttendanceController::class, 'filter']);
Route::get('/students/{studentId}/attendance', [AttendanceController::class, 'getAttendance']);


Route::controller(AssignmentController::class)->group(function () {

    Route::post('/ass', 'store');
    Route::get('/assignmentview', 'index');
    Route::get('/assignment/{id}', 'show');
    Route::post('assignments',  'assign');
    Route::post('assignments/{id}/upload', [AssignmentController::class, 'upload']);
    Route::get('assignments/{id}/file', [AssignmentController::class, 'getFile']);
});

Route::controller(RoutineController::class)->group(function () {

    Route::post('/routine', 'store');
    Route::get('/routineview', 'index');
    Route::get('/show/{classroom_id}/ass', 'show');
    Route::get('/filter', 'filterByDay');
});
