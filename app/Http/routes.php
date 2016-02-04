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

$app->group(['prefix' => 'api'], function() use ($app) {

    $app->get('/ping', function() use ($app) {
        return response()->json('pong');
    });

    $app->post('/webshot', ['middleware' => 'auth', 'uses' => 'App\Http\Controllers\WebshotController@store']);

});


