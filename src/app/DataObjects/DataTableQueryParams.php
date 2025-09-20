<?php

declare(strict_types=1);

namespace App\DataObjects;

class DataTableQueryParams
{
  public function __construct(
    public readonly int $start,
    public readonly int $length,
    public readonly string $orderBy,
    public readonly string $orderDir,
    public readonly string $searchTerm,
    public readonly int $draw,
    public readonly int $year,
    public readonly int $month,
    public readonly int $category
  ) {}
}
