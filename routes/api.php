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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::group([
        'middleware' => 'auth:api'
      ], function() {
          Route::get('logout', 'App\Http\Controllers\AuthController@logout');
    });
});
Route::group([
    'prefix' => 'v1'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::post('users', 'App\Http\Controllers\UserController@create');
        Route::get('users/{id}', 'App\Http\Controllers\UserController@view');
    });
});
