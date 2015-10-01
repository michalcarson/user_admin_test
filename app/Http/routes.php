<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('home');
});

Route::group(['middleware' => 'auth'], function($app) {

    Route::resource('/admin/user', 'UserController');
    Route::post('/user/{id}', 'UserController@updateSelf');

    Route::get('/user/home', function() {
        return View('user.home');
    });

    Route::get('/admin/home', function() {
        if (Auth::user()->admin) {
            return redirect('/admin/user');
        }
        return redirect('/user/home');
    });

});

// log in and log out routes
Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function ()
{
    // Authentication routes...
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    Route::get('logout', 'AuthController@getLogout');

    // Registration routes...
    Route::get('register', 'AuthController@getRegister');
    Route::post('register', 'AuthController@postRegister');

});
