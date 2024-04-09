<?php

require __DIR__ . "/../vendor/autoload.php";

$router = new \App\Router();

$router
  ->get('/', [App\Classes\Home::class, 'index'])
  ->get('/invoices', [App\Classes\Invoice::class, 'index'])
  ->get('/invoices/create', [App\Classes\Invoice::class, 'create'])
  ->post('/invoices/create', [App\Classes\Invoice::class, 'store']);

// $router->register('/invoices', function() {
//   echo 'Invoices';
// });
$router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
