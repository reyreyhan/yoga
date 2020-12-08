<?php

use Illuminate\Http\Request;

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

Route::namespace('API')->group(function () {
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');

    Route::middleware('jwt.verify')->group(function () {
        Route::get('user', 'UserController@getAuthenticatedUser');

        Route::post('yoga-session/', 'YogaSessionController@store');
        Route::put('yoga-session/{id}', 'YogaSessionController@update');
        Route::delete('yoga-session/{id}', 'YogaSessionController@delete');
    });

    Route::get('yoga-session', 'YogaSessionController@index');
    Route::get('yoga-session/{id}', 'YogaSessionController@show');

});



