<?php

declare(strict_types=1);

namespace App;

class ClassA
{
  protected static string $name = "A";

  public static function getName(): string
  {
    //return self::$name;
    return static::$name;
  }
}
