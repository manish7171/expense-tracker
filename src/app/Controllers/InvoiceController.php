<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\InvoiceStatus;
use App\View;
use App\Models\Invoice;
use App\Models\SignUp;
use App\Services\InvoiceService;
use App\Models\User;
use App\Attributes\Get;
use App\Attributes\Post;
use Twig\Environment;

class InvoiceController
{
  public function __construct(private InvoiceService $invoiceService, private Environment $twig)
  {
  }

  #[Get('/invoices')]
  public function index(): string
  {
    xdebug_info();
    throw new \Exception('exception');
    $invoices = Invoice::query()->where('status', InvoiceStatus::Paid)->get()->toArray();
    // $this->invoiceService->process([], 25);
    //
    // $user = new User();
    // $invoice = new Invoice();
    // $email = "test5@test.com";
    // $name = "test4 test4";
    // $amount = 25;
    //
    // $invoiceId = (new SignUp($user, $invoice))->register(
    //   [
    //     "email" => $email,
    //     "name" => $name
    //   ],
    //   [
    //     "amount" => $amount
    //   ]
    // );
    //
    //return View::make('/invoice/index', ["invoice" => $invoice]);
    return $this->twig->render('/invoice/index.twig', ["invoices" => $invoices]);
  }
  #[Get('/invoices/new')]
  public function create(): View
  {
    $invoice = new Invoice();
    $invoice->invoice_number = '2';
    $invoice->amount = 45;
    $invoice->status = InvoiceStatus::Pending;
    $invoice->save();
    return View::make('invoice/create');
  }

  #[Post('/invoices/upload')]
  public function upload()
  {
    echo "<pre>";
    var_dump($_FILES);
    echo "</pre>";
    $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];
    move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);
    echo "<pre>";
    var_dump(pathinfo($filePath));
    echo "</pre>";
  }
  public function store(): void
  {
    $amount = $_POST['amount'];
    var_dump($amount);
  }
}
