<?php

declare(strict_types=1);

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Capsule\Manager as Capsule;

//require_once __DIR__ . './vendor/autoload.php';
require_once __DIR__ . '/../eloquent.php';

Capsule::connection()->transaction(function () {
  $invoice = new Invoice();

  $invoice->amount = 45;
  $invoice->invoice_number = '1';
  $invoice->status = InvoiceStatus::Pending;
  $invoice->due_date = (new \Carbon\Carbon())->addDays(10);
  $invoice->save();

  $items = [['item1', 1, 15], ['item2', 2, 7.5], ['item3', 3, 3.75]];

  foreach ($items as [$desc, $quantity, $unitPrice]) {
    $invoiceItem = (new InvoiceItem());
    $invoiceItem->description = $desc;
    $invoiceItem->quantity = $quantity;
    $invoiceItem->unit_price = $unitPrice;

    $invoiceItem->invoice()->associate($invoice);

    $invoiceItem->save();
  }
});
