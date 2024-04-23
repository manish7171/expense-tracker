<?php

declare(strict_types=1);

namespace App\Attributes;

use App\Enums\HttpMethod;
use Attribute;

#[Attribute()]
class Route
{
  public function __construct(public string $routePath, public HttpMethod $method = HttpMethod::Get)
  {
  }
}
