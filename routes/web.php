<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::get('/', 'ApiController@home');

/* Route::get('/', function () {
    return response()->json([
        'platform' => 'SmartPorts',
        'version'  => '0.1.0',
        'contact'  => 'info@smartports.app'
    ]);
}); */

Route::group(['prefix' => 'track/','middleware' => 'throttle,rate_limit,1'], function () {
    # BL
    Route::post('bl', 'ApiController@track_bl');
    # CONTAINER
    Route::post('container', 'ApiController@track_container');
    # AWB
    Route::post('awb', 'ApiController@track_awb');
});

/* Route::group(['prefix' => 'v1/'], function () {
    # Login
    Route::post('login', 'AuthController@login');
    # Register
    Route::post('register', 'AuthController@register');
    # Logout
    Route::post('logout', 'AuthController@logout');
    # Refresh token
    Route::post('refresh', 'AuthController@refresh');
    # Get user profile
    Route::post('profile', 'AuthController@me');
}); */
