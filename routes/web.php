<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('', ['uses' => 'CapturesController@index', 'as' => 'captures.index']);

    Route::get('stations', ['uses' => 'StationsController@index', 'as' => 'stations.index', 'middleware' => ['admin']]);

    Route::get('stations/new', ['uses' => 'StationsController@new', 'as' => 'stations.new', 'middleware' => ['admin']]);
    Route::post('stations/new', ['uses' => 'StationsController@add', 'as' => 'stations.add', 'middleware' => ['admin']]);

    Route::get('stations/{id}', ['uses' => 'StationsController@edit', 'as' => 'stations.edit', 'middleware' => ['admin']]);
    Route::post('stations/{id}', ['uses' => 'StationsController@save', 'as' => 'stations.save', 'middleware' => ['admin']]);

    Route::get('operators', ['uses' => 'OperatorsController@index', 'as' => 'operators.index', 'middleware' => ['admin']]);
});
