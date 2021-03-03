<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'api'
], function () use ($router){
    $router->get('generate', 'AuthController@generate');
    $router->get('debug', 'AuthController@debug');
    $router->post('debug', 'AuthController@debug');
    $router->post('login', 'AuthController@login');
    $router->post('authorized', 'AuthController@authorized');
    $router->post('me', 'AuthController@me');
    $router->get('logout', 'AuthController@logout');
    $router->post('logout', 'AuthController@logout');

    $router->group([
        'prefix' => 'page'
    ], function () use ($router){
        $router->get('kanji', 'PageController@kanji');
        $router->post('kanji', 'PageController@kanji');
    });

    $router->group([
        'prefix' => 'kanji'
    ], function () use ($router){
        $router->get('kanji', 'KanjiController@kanji');
        $router->post('store', 'KanjiController@store');
    });
});
