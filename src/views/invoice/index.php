<!DOCTYPE html>
<html>

<head lang="en">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Untitled</title>
  <meta name="description" content="This is an example of a meta description.">
</head>

<body>
  <h1>Home Page</h1>
  <hr />
  <div>
    <?php
    if (!empty($invoice)) {
      foreach ($invoice as $i) { ?>
        Invoice ID: <?= htmlspecialchars($i['id']) ?> <br />
        Invoice Amount: <?= htmlspecialchars($i['amount']) ?> <br />
        status: <?= \App\Enums\InvoiceStatus::tryFrom(($i['status']))->toString() ?> <br />
    <?php }
    }
    ?>
  </div>

</body>

</html>
