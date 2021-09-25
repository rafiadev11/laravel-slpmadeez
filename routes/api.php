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

    });
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
