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

/**
 * Routes NOT Requiring Authentication
 */
Route::get('/', 'WelcomeController@show');


/**
 * Routes Requiring Authentication
 */
Route::group([
    'middleware' => 'auth'
], function(){

    Route::get('/home', 'HomeController@show');

    Route::get('/blogs', 'BlogController@index');

   /**
     * Routes marked for Deprecation but still need to be supported.
     * For example, routes that are from version 1 now that we are on a new version.
     */
    Route::get('/v1/blogs', function(){
        return view('v1.blogs');
    });
});


