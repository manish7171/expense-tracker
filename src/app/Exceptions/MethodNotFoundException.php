<?php

declare(strict_types=1);

namespace App\Exceptions;

class MethodNotFoundException extends \Exception
{
  protected $message = "Method not defined.";
}
