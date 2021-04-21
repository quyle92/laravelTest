<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('event', function () {
    event(new App\Events\StatusLiked('Someone'));
    return "Event has been sent!";
});

Route::get('listener', function () {
    return view('listener');
});




Route::get('axios', function () {
    return view('axios');	
});

Route::resource('products', \ProductController::class);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/welcome', function(){
	return  view('welcome');
})->name('welcome');
// Route::get('/test', function(){
// 	return  view('test');
// })->name('test');


