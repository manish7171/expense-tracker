<?php

declare(strict_types=1);

use App\Enums\InvoiceStatus;
use App\Models\Invoice;

require_once __DIR__ . '/../eloquent.php';

$invoiceId = 2;

Invoice::query()->where('id', $invoiceId)->update(['status' => InvoiceStatus::Paid]);

Invoice::query()->where('status', InvoiceStatus::Paid)->get()->each(function (Invoice $invoice) {
  echo $invoice->id . ', ' . $invoice->status->toString() . ', ' . $invoice->created_at->format('m/d/Y') . PHP_EOL;
});
