<?php

/** @var Router $router */

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return response()->json([
        'status' => 200,
        'success' => true
    ], 200);
});

$router->post('register', ['uses' => 'AuthController@register']);
