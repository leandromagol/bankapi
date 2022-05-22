<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

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

$router->post('/reset',function(Request $request){
    Cache::flush();
    return (new Response)->header('Content-Type', 'application/json');
});

$router->get('/balance','BalanceController@index');

$router->post('/event','EventController@post');