<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $this->view->title; ?></title>
</head>
<body>
<?php if ($this->view->header) require_once "header.php"; ?>
<div class="view-content">
    <?php $this->viewContent(GENERAL_EXTENSION); ?>
</div>
<?php if ($this->view->footer) require_once "footer.php"; ?>
</body>
</html>
