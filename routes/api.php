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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/testapi', function(){

	return response()->json(['message' => 'hello']);
});

Route::post('auth/register', 'UsersController@register');
Route::post('auth/login', 'UsersController@login');
Route::group(['middleware' =>['jwt'] ], function () {
    Route::get('logout', 'UsersController@logout');
    Route::get('user', 'UsersController@getUserInfo');
    Route::get('test', 'UsersController@test');
});
