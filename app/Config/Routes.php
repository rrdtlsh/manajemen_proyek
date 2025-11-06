<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Homepage untuk pelanggan/public
$routes->get('/', 'Home::homepage');
$routes->get('/homepage', 'Home::homepage');

// Login untuk karyawan/internal toko
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/login/switch/(:segment)', 'Login::switchLanguage/$1');

// Dashboard Owner
$routes->get('/dashboardowner', 'Owner::dashboard');
