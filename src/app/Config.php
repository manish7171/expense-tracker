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
    $params = [
      'host' => $env['DB_HOST'],
      'username' => $env['DB_USER'],
      'password' => $env['DB_PASS'],
      'database' => $env['DB_DATABASE'],
      'driver' => $env['DB_DRIVER'] ?? 'mysql',
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ];

    $this->config = [
      'db' => $params,
      'apiKeys' => [
        'emailable' => $env['EMAILABLE_API_KEY'] ?? '',
        'abstract' => $env['ABSTRACT_API_KEY'] ?? ''
      ],
    ];
  }

  public function __get(string $name): array
  {
    return $this->config[$name] ?? [];
  }
}
