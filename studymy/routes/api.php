<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\SpecialityController;

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

Route::middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/check-user-password', [AuthController::class, 'checkUserPassword']);
    Route::post('/update-user', [AuthController::class, 'updateUser']);

    Route::post('/errors', [ErrorController::class, 'store']);
    Route::get('/errors', [ErrorController::class, 'index']);
});

// Группа маршрутов, требующих аутентификации
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::get('/user/{id}', [AuthController::class, 'getUserFromId']);
    Route::post('/users', [AuthController::class, 'store']);
    Route::get('/teachers', [AuthController::class, 'getTeachers']); 
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);   

    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/subjects/{id}', [SubjectController::class, 'show']);
    Route::get('/subjects/{id}/marks', [SubjectController::class,'getMarks']);
    Route::get('/subjects/{id}/lectures', [SubjectController::class, 'getSubjectWithLectures']);
    Route::get('/groups/{id}/subjects', [SubjectController::class, 'getSubjectsByGroupId']);
    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::put('/subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

    Route::post('/groups', [GroupController::class, 'store']);
    Route::put('/groups/{id}', [GroupController::class, 'update']);
    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{id}', [GroupController::class, 'show']);
    Route::get('/groups/{groupId}/students', [GroupController::class, 'getStudentsInGroup']);
    Route::delete('/groups/{id}', [GroupController::class, 'destroy']);

    Route::get('/lectures/{id}', [LectureController::class, 'show']);
    Route::put('/lectures/{id}', [LectureController::class, 'update']);
    Route::post('/lectures', [LectureController::class, 'store']);
    Route::delete('/lectures/{id}', [LectureController::class, 'destroy']);

    Route::post('/marks', [MarkController::class, 'storeOrUpdate']);
    Route::get('/specialties', [SpecialityController::class, 'index']);

    Route::get('/groups-empty-year', [TimetableController::class, 'getGroupsWithEmptyYear']);
    Route::post('/schedule', [TimetableController::class, 'saveSchedule']);
    Route::get('/schedule/{date}', [TimetableController::class, 'getScheduleByDate']);
    // Получение расписания для преподавателя на определенную дату
    Route::get('schedule/teacher/{teacherId}/date/{date}', [TimetableController::class, 'getTeacherSchedule']);
    // Получение расписания для группы студентов на определенную дату
    Route::get('schedule/group/{groupId}/date/{date}', [TimetableController::class, 'getGroupSchedule']);
    
    Route::get('/api/template', function () {
        $path = public_path('resources/assets/documents/exampel1.xlsx');
        return response()->download($path);
    });
});
