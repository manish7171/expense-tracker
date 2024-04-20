<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\InvoiceService;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
  /** @test */
  public function it_processes_invoice(): void
  {
    $this->assertTrue((new InvoiceService())->process([], 25));
  }
}
