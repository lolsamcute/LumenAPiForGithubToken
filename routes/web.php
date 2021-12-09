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

// $router->get('/key', function() {
//     return \Illuminate\Support\Str::random(32);
// });

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1/api'], function () use ($router) {
    $router->post('login', 'AuthController@login'); //login
    $router->post('register', 'AuthController@register'); //create
});

$router->group(['middleware' => ['auth']], function () use ($router) {
    $router->post('logout', 'GeneralController@logout'); //Logout
    $router->get('validate/token', 'GeneralController@validateToken'); // Validate Token
    $router->post('encrypted/githubToken/{id}', 'GeneralController@githubToken'); //githubToken
    $router->get('get/user/token/{id}', 'GeneralController@getUserToken'); //getUserToken
});
