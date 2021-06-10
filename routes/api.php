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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// api/auth/
Route::group(['prefix' => 'auth', 'namespace' => 'Api'], function () {
    Route::post('login', 'LoginController@login')->name('auth.login');
    Route::get('login', 'LoginController@fallback')->name('login');

    Route::middleware('auth:api')->get('userData', 'LoginController@userData')->name('userData');
    Route::middleware('auth:api')->get('logout', 'LoginController@logout')->name('logout');
});