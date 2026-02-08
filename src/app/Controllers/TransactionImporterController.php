<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\TransactionImportRequestValidator;
use App\Services\TransactionImportService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;

class TransactionImporterController
{
  public function __construct(
    private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
    private readonly TransactionImportService $transactionImportService
  ) {
  }

  public function import(Request $request, Response $response): Response
  {
    /** @var UploadedFileInterface $file */
    $file = $this->requestValidatorFactory->make(TransactionImportRequestValidator::class)->validate(
      $request->getUploadedFiles()
    )['importFile'];

    $user = $request->getAttribute('user');

    $this->transactionImportService->importFromFile($file->getStream()->getMetadata('uri'), $user);

    return $response;
  }

  public function formatRaw(Request $request, Response $response): void
  {
      //open file
      $file = fopen(APP_PATH.'/expense.txt', 'r');
      $rfile = APP_PATH.'/expense.csv';
      $result = [];
      while(($row = fgets($file)) !== false) {
          //echo $row."\n";
          $cols = explode(' ', $row);
          $date = $cols[0];
          $expenses = $row;
          $expensesData = explode(',', $expenses);
          //var_dump($row);
          $index = 0;
          foreach($expensesData as $key => $ex) {
              //var_dump($key);
              //var_dump($ex);
              if ($index === 0) {
                  $result[] = $ex;
                  $index++;
                  continue;
              }
              //var_dump($date);
              $t = $date.''. $ex;
              //var_dump($t);
              //die;
             $result[] = $t;
          }

      }
      fclose($file);
      foreach($result as $r){
          [$date, $price, $description] = preg_split('/\s+/', $r, 3);
          file_put_contents($rfile, "$date,$price,$description\n", FILE_APPEND);
      }
  }
}
