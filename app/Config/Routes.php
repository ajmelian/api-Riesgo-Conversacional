<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API v1
$routes->group('v1', ['namespace' => 'App\Controllers\Api\V1'], function ($routes) {
    $routes->get('health', 'Health::index');

    $routes->post('auth/llm-session', 'Auth::createSession', ['filter' => 'apiClientAuth']);
    $routes->delete('auth/llm-session', 'Auth::revokeSession', ['filter' => 'bearerSession']);
    $routes->post('conversations/analyze', 'Analysis::analyze', ['filter' => 'bearerSession']);
});
