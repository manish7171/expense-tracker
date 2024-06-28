<?php

declare(strict_types=1);

namespace App\DataObjects;

class EmailValidationResult
{
  public function __construct(public readonly int $score, public readonly bool $deliverable)
  {
  }
}
