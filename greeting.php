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
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";

if (!session_id()) session_start();

if (!isset($_SESSION['user_id'])) {
  require_once './includes/set_session.php';
}

$user = new User();
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/templates/header.php";
$favicon = "/images/icons/favicon.ico";
$title = "Вы зарегистрированы!";

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $favicon ?>">
  </head>
  <body>
<?= $page_header ?>
    <main class="gretting">
    <h1><?= $title ?></h1>
      <p>Поздравляем с успешной регистрацией.</p>
      <p>Ваш логин для входа: <span class="user-data"><?= $user->login ?></span><br>
         Ваш ник: <span class="user-data"><?= $user->name ?></span><br>
         Ваш e-mail: <span class="user-data"><?= $user->email ?: "Не указан"; ?></span>
      </p>
      <p><a href="/">Прейти к созданию заметок.</a></p>
    </main>
  </body>
</html>
