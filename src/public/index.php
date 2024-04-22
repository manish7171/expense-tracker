<?php

require __DIR__ . "/../vendor/autoload.php";

use App\Config;
use App\Controllers\HomeController;
use App\Controllers\InvoiceController;
use App\Controllers\TransactionController;
use Dotenv\Dotenv;
use App\App;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));

$dotenv->load();

session_start();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');
$container = new \App\Container();
$router = new \App\Router($container);

$router
  ->get('/', [HomeController::class, 'index'])
  ->post('/upload', [HomeController::class, 'upload'])
  ->get('/invoices', [InvoiceController::class, 'index'])
  ->get('/invoices/create', [InvoiceController::class, 'create'])
  ->post('/invoices/create', [InvoiceController::class, 'store'])
  ->get('/transactions', [TransactionController::class, 'index'])
  ->post('/transactions/upload', [TransactionController::class, 'upload']);
(new App(
  $router,
  ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
  new Config($_ENV)
))
  ->run();
