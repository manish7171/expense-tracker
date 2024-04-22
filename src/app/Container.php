<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\Container\NotfoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
  private array $entries = [];

  public function get(string $id)
  {
    if (!$this->has($id)) {
      throw new NotfoundException('Class "' . $id . '" has no binding');
    }

    $entry = $this->entries[$id];
    //NOTE: $entry is a callable here
    //NOTE: $entry() will return the returning value of the callback
    //NOTE: in $entry($this), i am passing the current instance of the Container as the argument
    //NOTE: this way the callback function has the access to the container,
    //NOTE: so it could get its own dependencies if needed within that callback
    return $entry($this);
  }

  public function has(string $id): bool
  {
    return isset($this->entries[$id]);
  }

  public function set(string $id, callable $concrete): void
  {
    $this->entries[$id] = $concrete;
  }
}
