<?php

  include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB_connection.php';
  include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB_tables.php';
//   include_once $_SERVER['DOCUMENT_ROOT'].'/includes/classes/User.php';

  $title = "Вы зарегистрированы!";

//   $user = new User;

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

      <!--<p>Ваш никнейм: </p>

      <p>Ваш логин: </p>-->

      <p><a href="/">Прейти на главную страницу сайта.</p>

      <p>

    </main>
  </body>
</html>
