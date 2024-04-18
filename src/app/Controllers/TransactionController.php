<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;

use App\Models\Transaction;

class TransactionController
{
  public function index(): View
  {
    $transactionList = (new Transaction())->all();
    $totals = $this->calculateTotals($transactionList);
    $totalFormated = $this->formatTotals($totals);
    $transactionListFormated  = $this->formateAmount($transactionList);
    return View::make('transaction/index', ['transactions' => $transactionListFormated, "total" => $totalFormated]);
  }


  private function formatTotals(array $totals): array
  {
    if ($totals['net'] >= 0) {
      $totals['net']  = '$' . number_format(abs($totals['net']), 2);
    } else {
      $totals['net']  = '-$' . number_format(abs((float)$totals['net']), 2);
    }

    if ($totals['totalIncome'] >= 0) {
      $totals['totalIncome']  = '$' . number_format(abs($totals['totalIncome']), 2);
    } else {
      $totals['totalIncome']  = '-$' . number_format(abs((float)$totals['totalIncome']), 2);
    }
    if ($totals['totalExpense'] >= 0) {
      $totals['totalExpense']  = '$' . number_format(abs($totals['totalExpense']), 2);
    } else {
      $totals['totalExpense']  = '-$' . number_format(abs((float)$totals['totalExpense']), 2);
    }
    return $totals;
  }

  private function formateAmount($transactions)
  {
    $newTransaction = [];
    for ($i = 0; $i < count($transactions); ++$i) {
      $newTransaction[$i]['transaction_date']  = $transactions[$i]['transaction_date'];
      $newTransaction[$i]['check_no']  = $transactions[$i]['check_no'];
      $newTransaction[$i]['description']  = $transactions[$i]['description'];
      if ($transactions[$i]['amount'] >= 0) {
        $newTransaction[$i]['amount']  = '$' . number_format(abs((float)$transactions[$i]['amount']), 2);
      } else {
        $newTransaction[$i]['amount']  = '-$' . number_format(abs((float)$transactions[$i]['amount']), 2);
      }
    }
    return $newTransaction;
  }

  private function calculateTotals($transactions)
  {
    $total = ["totalIncome" => 0, "totalExpense" => 0, "net" => 0];
    foreach ($transactions as $transaction) {
      $total["net"] += $transaction["amount"];
      if ($transaction['amount'] >= 0) {
        $total["totalIncome"] += $transaction['amount'];
      } else {
        $total["totalExpense"] += $transaction['amount'];
      }
    }
    return $total;
  }

  public function upload()
  {
    $transaction = new Transaction();
    if ($transaction->insertCsv($_FILES) === 1) {
      header("location: localhost:8000/transactions");
    }

    throw new \Exception("Upload failed!");
    //fetch from database
    //show in page

  }
}
