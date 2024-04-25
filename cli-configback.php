<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Dotenv\Dotenv;
use Doctrine\ORM\ORMSetup;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$params = [
  'host' => $_ENV['DB_HOST'],
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'dbname' => $_ENV['DB_DATABASE'],
  'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
];

var_dump($params);
die;

$config = ORMSetup::createAttributeMetadataConfiguration(
  paths: array(__DIR__ . "/app/Entities"),
  isDevMode: true,
);

$config = new PhpFile('migrations.php'); // Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders

$conn = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);

return DependencyFactory::fromConnection($config, new ExistingConnection($conn));
