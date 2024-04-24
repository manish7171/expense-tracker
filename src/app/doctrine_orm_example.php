<?php

declare(strict_types=1);

use App\Entities\Invoice;
use App\Entities\InvoiceItem;
use App\Enums\InvoiceStatus;
use Dotenv\Dotenv;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$params = [
  'host' => $_ENV['DB_HOST'],
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'dbname' => $_ENV['DB_DATABASE'],
  'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
];

$config = ORMSetup::createAttributeMetadataConfiguration(
  paths: array(__DIR__ . "/Entities"),
  isDevMode: true,
);
// or if you prefer XML
// $config = ORMSetup::createXMLMetadataConfiguration(
//    paths: array(__DIR__."/config/xml"),
//    isDevMode: true,
//);

// configuring the database connection
$connection = DriverManager::getConnection($params, $config);

// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);

$items = [['item1', 1, 15], ['item2', 2, 7.5], ['item3', 3, 3.75]];

$invoice = (new Invoice())
  ->setAmount(45)
  ->setInvoiceNumber('1')
  ->setStatus(InvoiceStatus::Pending)
  ->setCreatedAt(new DateTime());

foreach ($items as [$desc, $quanity, $unitPrice]) {
  $item = (new InvoiceItem())
    ->setDescription($desc)
    ->setQuantity($quanity)
    ->setUnitPrice($unitPrice);

  $invoice->addItem($item);
  $entityManager->persist($item);
}
$entityManager->persist($invoice);
$entityManager->flush();
