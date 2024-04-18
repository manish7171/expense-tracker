<?php

declare(strict_types=1);

namespace App;

use PDO;

/**
 *
 * @mixin PDO
 *
 */

class DB
{

  private PDO $pdo;

  public function __construct(array $config)
  {
    $defaultOptions = [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $dsn = $config['driver'] . ":host=" . $config['host'] . ";dbname=" . $config['database'];
    $username = $config['user'];
    $password = $config['pass'];
    try {
      $this->pdo = new PDO($dsn, $username, $password, $config['options'] ?? $defaultOptions);
    } catch (\PDOException $e) {
      throw new \PDOException($e->getMessage(), $e->getCode());
    }
  }

  public function __call(string $name, array $arguments)
  {
    return call_user_func_array([$this->pdo, $name], $arguments);
  }
}
