<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Aggiungo la funzione ini_set per aggiungere i cookie
 */
ini_set('session.cookie_lifetime', '864000'); // dieci giorni in secondi

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Aggiungo la Sessione
 */
session_start();


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);

// Route custom per il login
$router->add('login', ['controller' => 'Login', 'action' => 'new']);

// Route custom per il logout
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);

// Route password reset
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);

$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
