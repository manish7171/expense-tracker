<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;

class Transaction extends Model
{

  public function all(): array|bool
  {
    $query = 'select t_date as transaction_date, check_no , description, amount from transaction';
    $stmt = $this->db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function insertCsv(array $file): int
  {
    $file = $file['transaction']['tmp_name'];

    if (!file_exists($file)) {
      throw new \Exception("File doesn't exist!");
    }

    $fOpen = fopen($file, 'r');

    //remove first row
    fgetcsv($fOpen, 4000, ',');

    $rows = [];
    while (($row = fgetcsv($fOpen, 4000, ',')) !== false) {
      $rows[] = $this->reformatRow($row);
      if (count($rows) >= 10) {
        $this->insertRows($this->db, 'transaction', $rows);
        $rows = [];
      }
    }

    if (count($rows) > 0) {
      $this->insertRows($this->db, 'transaction', $rows);
    }

    fclose($fOpen);

    return 1;
  }

  private function reformatRow(array $row): array
  {
    return [$this->convertDateFormat($row[0]), $row[1], $row[2], $this->extractAmount($row[3]), date('Y-m-d h:i:s')];
  }

  private function convertDateFormat(string $date): string
  {
    $time = strtotime($date);
    return date('Y-m-d', $time);
  }

  private function extractAmount(string $amount): float
  {
    return (float)str_replace(["$", ","], "", $amount);
  }

  public function insertRows(\App\DB $db, string $table, array $rows): void
  {
    $values    = '(' . implode(',', array_fill(0, count($rows[0]), '?')) . ')';
    $query     = 'INSERT INTO ' . $table . ' (t_date, check_no, description, amount, created_at) VALUES ' .
      implode(',', array_fill(0, count($rows), $values));
    $statement = $db->prepare($query);
    $index     = 1;
    foreach ($rows as $row) {
      foreach ($row as $value) {
        $statement->bindValue($index++, $value);
      }
    }
    $statement->execute();
  }
}
