<?php

declare(strict_types=1);

namespace App\Classes;

class Invoice
{
  public function index()
  {
    echo "Invoice";
  }

  public function create()
  {
    echo '<form action="/invoices/create" method="POST">
      <input type="text" name="amount"  value=""/>
      </form>';
  }

  public function store()
  {
    $amount = $_POST['amount'];
    var_dump($amount);
  }
}
