<?php

require __DIR__ . "/../vendor/autoload.php";

session_start();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');

try {
  $router = new \App\Router();

  $router
    ->get('/', [App\Controllers\HomeController::class, 'index'])
    ->post('/upload', [App\Controllers\HomeController::class, 'upload'])
    ->get('/invoices', [App\Controllers\InvoiceController::class, 'index'])
    ->get('/invoices/create', [App\Controllers\InvoiceController::class, 'create'])
    ->post('/invoices/create', [App\Controllers\InvoiceController::class, 'store']);

  // $router->register('/invoices', function() {
  //   echo 'Invoices';
  // });
  echo $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (\App\Exceptions\RouteNotFoundException $e) {
  header('HTTP/1.1 404 NOT FOUND');
  echo App\View::make('errors/404');
}
