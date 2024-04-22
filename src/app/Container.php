<?php

declare(strict_types=1);

namespace App;

//use App\Exceptions\Container\NotfoundException;
use App\Exceptions\Container\ContainerException;
use Psr\Container\ContainerInterface;
use ReflectionNamedType;
use ReflectionUnionType;

class Container implements ContainerInterface
{
  private array $entries = [];

  public function get(string $id)
  {
    if ($this->has($id)) {
      $entry = $this->entries[$id];
      //NOTE: $entry is a callable here
      //NOTE: $entry() will return the returning value of the callback
      //NOTE: in $entry($this), i am passing the current instance of the Container as the argument
      //NOTE: this way the callback function has the access to the container,
      //NOTE: so it could get its own dependencies if needed within that callback
      return $entry($this);
    }

    return $this->resolve($id);
  }

  public function has(string $id): bool
  {
    return isset($this->entries[$id]);
  }

  public function set(string $id, callable $concrete): void
  {
    $this->entries[$id] = $concrete;
  }

  public function resolve(string $id)
  {
    // 1. Inspect the case that we are trying to get from the container
    $reflectionClass = new \ReflectionClass($id);
    if (!$reflectionClass->isInstantiable()) {
      throw new ContainerException('Class "' . $id . '" is not instansiable');
    }

    // 2. Inspect the constructor of the class
    $constructor = $reflectionClass->getConstructor();
    if (!$constructor) {
      return new $id;
    }
    // 3. Inpect the constructor params (Depenecies)
    $parameters = $constructor->getParameters();
    if (!$parameters) {
      return new $id;
    }
    // 4. If the constructor params is a class then we need to resolve that class using the container
    $dependencies = array_map(function (\ReflectionParameter $param) use ($id) {
      $name = $param->getName();
      $type = $param->getType();

      if (!$type) {
        throw new ContainerException('Failed to resolve class "' . $id . '" because param "' . $name . '" is missing type hint');
      }
      if ($type instanceof ReflectionUnionType) {
        throw new ContainerException('Failed to resolve class "' . $id . '" because of union type for param "' . $name . '"');
      }

      if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
        return $this->get($type->getName());
      }

      throw new ContainerException('Failed to resolve class "' . $id . '" because of invalid param "' . $name . '"');
    }, $parameters);


    return $reflectionClass->newInstanceArgs($dependencies);
  }
}
