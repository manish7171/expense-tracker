<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\PaymentGatewayService;
use App\Services\SalesTaxService;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
  /** @test */
  public function it_processes_invoice(): void
  {
    $customer = ["name" => "manish"];
    $amount = 25;
    $salesTaxServiceMock = $this->createMock(SalesTaxService::class); //new SalesTaxService();
    $paymenetGatewayServiceMock = $this->createMock(PaymentGatewayService::class); //new PaymentGatewayService();
    $emailServiceMock = $this->createMock(EmailService::class); //new EmailService();
    $paymenetGatewayServiceMock->method('charge')->willReturn(true);

    //$emailService->method('send')->willReturn(true);
    $this->assertTrue((new InvoiceService($salesTaxServiceMock, $paymenetGatewayServiceMock, $emailServiceMock))->process($customer, $amount));
  }

  /** @test */
  public function it_sends_receipt_email_when_invoice_is_processed(): void
  {

    $customer = ["name" => "manish"];
    $salesTaxServiceMock = $this->createMock(SalesTaxService::class); //new SalesTaxService();
    $paymenetGatewayServiceMock = $this->createMock(PaymentGatewayService::class); //new PaymentGatewayService();
    $emailServiceMock = $this->createMock(EmailService::class); //new EmailService();

    $paymenetGatewayServiceMock->method('charge')->willReturn(true);

    $emailServiceMock->expects($this->once())->method('send')->with($customer, 'receipt');

    $amount = 25;

    $this->assertTrue((new InvoiceService($salesTaxServiceMock, $paymenetGatewayServiceMock, $emailServiceMock))->process($customer, $amount));
  }
}
