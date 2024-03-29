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

$router->get('/key', function () use ($router) {
    return \Illuminate\Support\Str::random(32);
});

$router->group([
    'prefix' => 'api'
], function () use ($router){
    $router->get('generate', 'AuthController@generate');
    $router->get('debug', 'AuthController@debug');
    $router->post('debug', 'AuthController@debug');
    $router->post('login', 'AuthController@login');
    $router->get('authorized', 'AuthController@authorized');
    $router->post('authorized', 'AuthController@authorized');
    $router->post('me', 'AuthController@me');
    $router->get('logout', 'AuthController@logout');
    $router->post('logout', 'AuthController@logout');

    $router->group([
        'prefix' => 'page'
    ], function () use ($router){
        $router->post('kanji', 'PageController@kanji');
        $router->post('word', 'PageController@word');
        $router->post('vocabulary', 'PageController@vocabulary');
    });

    $router->group([
        'prefix' => 'kanji'
    ], function () use ($router){
        $router->post('form', 'KanjiController@form');
        $router->post('store', 'KanjiController@store');
        $router->put('update', 'KanjiController@update');
    });

    $router->group([
        'prefix' => 'word'
    ], function () use ($router){
        $router->post('form', 'WordController@form');
        $router->post('store', 'WordController@store');
        $router->put('update', 'WordController@update');
    });

    $router->group([
        'prefix' => 'vocabulary'
    ], function () use ($router){
        $router->post('form', 'VocabularyController@form');
        $router->post('store', 'VocabularyController@store');
        $router->put('update', 'VocabularyController@update');
    });
});
