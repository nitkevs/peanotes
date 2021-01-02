<?php

/*
* /greeting.php
*
* Страница приветствия нового пользователя.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB_tables.php';

$title = "Вы зарегистрированы!";
$favicon = "/images/icons/favicon.ico";

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $favicon ?>">
    <style>
      main.gretting {
        margin: 50px auto;
        text-align: center;
      }
    </style>
  </head>
  <body>
    <main class="gretting">
    <h1><?= $title ?></h1>
      <p>Поздравляем с успешной регистрацией.</p>
      <p><a href="/">Прейти на главную страницу сайта.</p>
    </main>
  </body>
</html>
