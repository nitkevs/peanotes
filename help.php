<?php

  $page_content = nl2br(file_get_contents("README.txt"));
  $root_dir = "/php/peanotes";
  $favicon = "/images/icons/favicon.ico";

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $root_dir.$favicon ?>">
    <title>Справка</title>
    <style>
      body {
        padding: 18px;
      }
    </style>
  </head>
  <body>
  <p>
  <a href="./">На главную</a>
  </p>
  <?= $page_content ?>
  <p>
  <a href="./">На главную</a>
  </p>
  </body>
</html>
