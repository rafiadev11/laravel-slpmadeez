<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

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
    Route::group(['middleware' => 'auth:sanctum', 'namespace' => 'Portal'], function () {
        Route::resource('/schools', 'SchoolsController');
        Route::get('/school-years/school/{school_id}', 'SchoolYearsController@index');
        Route::resource('/school-years', 'SchoolYearsController', ["except" => ['index', 'show']]);
        Route::resource('/disorders', 'DisordersController');
        Route::get('/unused-disorders/{studentId}', 'DisordersController@unused');
        Route::get('/students/{school_year_id}/{disorder_id?}', 'StudentsController@index');
        Route::patch('/students/goal/{goal_id}', 'StudentsController@updateGoal');
        Route::patch('/students/goal/{goal_id}/deactivate', 'StudentsController@deactivateGoal');
        Route::resource('/students', 'StudentsController', ['except' => ['index']]);
        Route::post('/student/disorder', 'StudentsController@addDisorder');
        Route::get('/student/schedule/{goal_id}', 'StudentsController@getSchedule');
        Route::post('/student/update-schedule', 'StudentsController@updateSchedule');
        Route::get('/student/objectives/{goal_id}', 'StudentsController@getObjectives');
        Route::post('/student/update-objectives', 'StudentsController@updateObjectives');
        Route::post('/student/deactivate', 'StudentsController@deactivate');
        Route::post('/student/transfer', 'StudentsController@transfer');
    });
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
