<?php

/*
* /view_profile.php
*
* Страница просмотра профиля пользователя.
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
$title = "Профиль пользователя {$user->name}";

$user_groups_list = array(
  1 => "Администратор",
  4 => "Пользователь",
);

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
    <main>
    <h1><?= $title ?></h1>
      <div class="grid-container two-columns">
<?php if ($user->group === 1):?>
      <div class="user-property">id:</div><div><?= $user->id ?></div>
<?php endif; ?>
      <div class="user-property">Имя (никнейм):</div><div><?= $user->name ?></div>
      <div class="user-property">Логин:</div><div><?= $user->login ?></div>
      <div class="user-property">Адрес e-mail:</div><div><?= $user->email ?: "Не указан"; ?></div>
      <div class="user-property">Группа:</div><div><?= $user_groups_list[$user->group] ?></div>
      </div>
    </main>
  </body>
  <script src="js/header.js"></script>
</html>
