<?php

/*
*
* /help.php
*
* Страница справочной информации.
*
*/

$page_content = nl2br(file_get_contents("README.txt"));
$favicon = "/images/icons/favicon.ico";

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $favicon ?>">
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
