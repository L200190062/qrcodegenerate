<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('qr', 'QrController::index');
$routes->get('qr/create', 'QrController::create');
$routes->post('qr/store', 'QrController::store');
$routes->get('qr/show/(:num)', 'QrController::show/$1');
$routes->group('bulk-qr', function ($routes) {
    $routes->get('/', 'BulkQrController::index');
    $routes->get('create', 'BulkQrController::create');
    $routes->post('process', 'BulkQrController::process');
    $routes->get('download/(:num)', 'BulkQrController::download/$1');
    $routes->get('view/(:num)', 'BulkQrController::view/$1');
    $routes->get('verify-captcha/(:any)', 'CaptchaController::index/$1');
    $routes->post('verify-captcha', 'CaptchaController::verify');
});

$routes->get('view-file/(:any)/download', 'ViewFileController::download/$1');
$routes->get('view-file/(:any)', 'ViewFileController::index/$1');

$routes->get('captcha-verify', 'CaptchaVerifyController::index');
$routes->post('captcha-verify/verify', 'CaptchaVerifyController::verify');