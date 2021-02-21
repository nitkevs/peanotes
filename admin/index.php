<?php

/*
* /admin/index.php
*
* страница администрирования сайта.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";

if (!session_id()) session_start();

if (!isset($_SESSION['user_id'])) {
  require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/set_session.php";
}

$user = new User();

if ($user->group > 1) {
  $groups = ["", "Администратор", "Модератор", "Пользователь", "Пользователь"];
  echo "Пользователи группы \"{$groups[$user->group]}\" не имеют доступа к этой странице. <a href=\"/\">Перейти на главную</a>.";
  exit;
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/templates/header.php";
$favicon = "/images/icons/favicon.ico";
$title = 'Управление сайтом';

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="/style.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
  </head>
  <body>
  <?= $page_header ?>
    <main class="">

    <div class="">
      <h1 class="centered"><?= $title ?></h1>
      <a href="/admin/users.php">Пользователи</a>
    </div>
    </main>
  </body>
  <script src="/js/header.js"></script>
</html>