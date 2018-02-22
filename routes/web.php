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

Route::group([
        'prefix' => 'api',
        //'middleware' => ['web', 'auth']
    ], function() {
    $controller = '\App\Http\Controllers\ApiController';
    
    Route::get('/', $controller.'@index');

    Route::get('/ingest', $controller.'@ingest');

    Route::get('/ingest/save', $controller.'@ingestAndSave');

});