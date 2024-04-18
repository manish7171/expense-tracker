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
    if (!empty($invoice)) { ?>
      Invoice ID: <?= htmlspecialchars($invoice['id']) ?> <br />
      Invoice Amount: <?= htmlspecialchars($invoice['amount']) ?> <br />
      Username: <?= htmlspecialchars($invoice['full_name']) ?> <br />
    <?php }
    ?>
  </div>

</body>

</html>
