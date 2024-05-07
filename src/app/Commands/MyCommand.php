<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\InvoiceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:My-command', description: 'Export code table to a CSV file')]
class MyCommand extends Command
{
  protected static $name = 'app:my-command';
  protected static $description = 'My command';

  public function __construct(private readonly InvoiceService $invoice)
  {
    parent::__construct();
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $output->write('Paid invoices ' . count($this->invoice->getPaidInvoices()), true);
    return Command::SUCCESS;
  }
}
