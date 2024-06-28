<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;

use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;

class BuildEntryPoints implements ContainerInterface
{
  public function get(string $id)
  {
    return new EntrypointLookup(BUILD_PATH . '/entrypoints.json');
  }

  public function has(string $id): bool
  {
    return true;
  }
}
