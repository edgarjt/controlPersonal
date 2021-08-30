<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Author: Edgar Salomon Jimenez Torres
| Email: edgarjt97@gmail.com
| Phone: 9934456273
| Created: 2021-08-24 02:23:54
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth.jwt')->group( function () {
    Route::prefix('users')->group(function () {
        Route::get('getUsers', 'UsersController@getUsers');
        Route::post('addUser', 'UsersController@addUser');
        Route::delete('deleteUser', 'UsersController@deleteUser');
        Route::put('updateUser', 'UsersController@updateUser');
        Route::put('theme', 'UsersController@theme');
    });

    Route::prefix('roles')->group(function () {
        Route::get('getRoles', 'RolesController@getRoles');
        Route::post('addRole', 'RolesController@addRole');
        Route::delete('deleteRole', 'RolesController@deleteRole');
        Route::put('updateRole', 'RolesController@updateRole');
    });

    Route::prefix('work')->group(function () {
        Route::get('getWorks', 'WorkPositionController@getWorksPosition');
        Route::post('addWork', 'WorkPositionController@addWorkPosition');
        Route::delete('deleteWork', 'WorkPositionController@deleteWorkPosition');
        Route::put('updateWork', 'WorkPositionController@updateWorkPosition');
    });
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::middleware('auth.jwt')->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    });
});
