<!DOCTYPE html>
<html>

<head lang="en">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Untitled</title>
  <meta name="description" content="This is an example of a meta description.">
</head>

<body>
  <div>
    <form action="/transactions/upload" method="post" enctype="multipart/form-data">
      <input type="file" name="transaction" />
      <button type="submit">Upload</button>
    </form>
  </div>
  <div>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Check no</th>
          <th>Description</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $totalIncome = 0;
        $totalExpense = 0;
        ?>
        <?php foreach ($transactions as $transaction) {
        ?>

          <tr>
            <td><?= $transaction['transaction_date'] ?></td>
            <td><?= $transaction['check_no'] ?></td>
            <td><?= $transaction['description'] ?></td>
            <td><?= $transaction['amount'] ?></td>
          </tr>

        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <th>TOTAL INCOME:</th>
          <td><?= $total['totalIncome'] ?></td>
        </tr>
        <tr>
          <th>TOTAL EXPENSE:</th>
          <td><?= $total['totalExpense'] ?></td>
        </tr>
        <tr>
          <th>NET TOTAL:</th>
          <td><?= $total['net'] ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</body>

</html>
