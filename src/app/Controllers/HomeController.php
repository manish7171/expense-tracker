<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\SignUp;
use App\View;
use App\Models\User;
use App\Models\Invoice;

class HomeController
{
  public static function index(): View
  {
    $user = new User();
    $invoice = new Invoice();
    $email = "test5@test.com";
    $name = "test4 test4";
    $amount = 25;

    $invoiceId = (new SignUp($user, $invoice))->register(
      [
        "email" => $email,
        "name" => $name
      ],
      [
        "amount" => $amount
      ]
    );

    return View::make('index', ["invoice" => $invoice->find($invoiceId)]);
  }

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
}