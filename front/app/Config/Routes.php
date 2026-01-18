<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('admin/modules', 'Administrative::modules');
$routes->post('admin/register', 'Administrative::register');
$routes->get('cerrarsesion', 'Home::cerrarsession');
$routes->get('/', 'Login::index');
