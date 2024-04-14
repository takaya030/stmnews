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
    //return $router->app->version();
    return '404 Not Found';
});

// Test Timeline
//$router->get('/timeline',  'TwitterController@getTimeline' );

// Test Login
$router->get('/login',  'App\Http\Controllers\TwitterController@getLogin' );
$router->get('/loginv2',  'App\Http\Controllers\TwitterController@getLoginv2' );

// Test RSS
$router->get('/rss',  App\Http\Actions\GetRssToSlackAction::class );
$router->get('/gamerss',  App\Http\Actions\GetGameRssToSlackAction::class );

// Test Delete Entities
$router->get('/delent',  App\Http\Actions\DelEntAction::class );
