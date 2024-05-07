<?php

declare(strict_types=1);

namespace App;

/**
 * @property-read ?array $db
 */
class Config
{
  protected array $config = [];

  /**
   * @param array $env
   * */
  public function __construct(array $env)
  {
    //Doctrine config
    $params = [
      'host' => $_ENV['DB_HOST'],
      'user' => $_ENV['DB_USER'],
      'password' => $_ENV['DB_PASS'],
      'dbname' => $_ENV['DB_DATABASE'],
      'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
    ];


    //laravel db config
    // $params = [
    //   'host' => $env['DB_HOST'],
    //   'username' => $env['DB_USER'],
    //   'password' => $env['DB_PASS'],
    //   'database' => $env['DB_DATABASE'],
    //   'driver' => $env['DB_DRIVER'] ?? 'mysql',
    //   'charset' => 'utf8',
    //   'collation' => 'utf8_unicode_ci',
    //   'prefix' => '',
    // ];

    $this->config = [
      'appName' => $env['APP_NAME'] ?? 'My APP',
      'appVersion' => $env['APP_VERSION'] ?? '1.0',
      'db' => $params,
      'apiKeys' => [
        'emailable' => $env['EMAILABLE_API_KEY'] ?? '',
        'abstract' => $env['ABSTRACT_API_KEY'] ?? ''
      ],
      'environment' => $env['APP_ENVIRONMENT'] ?? 'production'
    ];
  }

  public function __get(string $name): string|array
  {
    return $this->config[$name] ?? null;
  }
}
