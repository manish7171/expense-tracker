<?php

require __DIR__."/../vendor/autoload.php";

$router = new \App\Router();

$router
  ->register('/', [App\Classes\Home::class,'index'])
  ->register('/invoices', [App\Classes\Invoice::class, 'index'])
  ->register('/invoice/create', [App\Classes\Invoice::class, 'create']);

// $router->register('/invoices', function() {
//   echo 'Invoices';
// });

var_dump( $router->resolve($_SERVER['REQUEST_URI']));
