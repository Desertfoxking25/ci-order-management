<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'OrderController::list');

$routes->get('order', 'OrderController::index');
$routes->post('order/create', 'OrderController::create');
$routes->post('order/addItem/(:num)', 'OrderController::addItem/$1');
$routes->post('order/changeStatus/(:num)', 'OrderController::changeStatus/$1');
$routes->get('order/status-log/(:num)', 'OrderController::statusLog/$1');
