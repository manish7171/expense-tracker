<?php

declare(strict_types=1);

use App\Config;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Slim\Views\Twig;
use function DI\create;
use Twig\Extra\Intl\IntlExtension;

return [
  Config::class => create(Config::class)->constructor($_ENV),
  EntityManager::class =>
  function (Config $config) {
    $setup = ORMSetup::createAttributeMetadataConfiguration(
      paths: array(__DIR__ . "../app/Entity"),
      isDevMode: $config->environment === 'development',
    );
    $connection = DriverManager::getConnection($config->db, $setup);
    return new EntityManager($connection, $setup);
  },
  Twig::class => function (Config $config) {
    $twig = Twig::create(VIEW_PATH, [
      'cache' => STORAGE_PATH . '/cache',
      'auto_reload' => $config->environment === 'development'
    ]);
    $twig->addExtension(new IntlExtension());
    return $twig;
  }
];
