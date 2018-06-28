<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::group([
    'middleware' => 'auth:api'
], function () {

    // prefix '/api' is already predefined in RouteServiceProvider.
    // Therefore, '/' within this group is actually '/api/'
    Route::get('/', function(){
        return 'Requires Authentication';
    });
    Route::get('blogs', 'Api\BlogController@index');

});
