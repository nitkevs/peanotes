<?php

$page_content = nl2br(file_get_contents("README.txt"));

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
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
