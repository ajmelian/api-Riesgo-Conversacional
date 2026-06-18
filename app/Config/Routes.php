<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API v1
$routes->group('v1', ['namespace' => 'App\Controllers\Api\V1'], function ($routes) {
    $routes->get('health', 'Health::index');
});
