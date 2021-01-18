<?php

/*
* /user_settings.php
*
* Страница пользовательских настроек.
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
$title = "Настройки";

$error_message = $_SESSION['error_message'] ?? "";
$_SESSION['error_message'] = "";

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
<?php if ($error_message): ?>
    <div id="user-settings-error-message" class="error-message">
      <?= $error_message ?>
    </div>
<?php endif; ?>
    <h1><?= $title ?></h1>
    <form action="/scripts/set_user_settings.php" method="post" id="user-settings-form" class="grid-container two-columns">
      <label for="email">Адрес e-mail:</label>
      <input type="email" id="email" name="email" value="<?= $user->email ?>">
      <div class="description">Ваш адрес почты.</div>

      <label for="password">Новый пароль:</label>
      <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}">
      <div class="description">Пароль должен содержать по крайней мере одно число, одну заглавную и строчную буквы и быть длинной не менее 10 символов</div>

      <label for="confirm-password" id="confirm-password-label">Повторите пароль:</label>
      <input type="password" id="confirm-password"  name="confirm-password">
      <div class="description">Пароли должны совпадать.</div>

      <label for="old-password" id="old-password-label">Старый пароль:</label>
      <input type="password" id="old-password" name="old-password">
      <div class="description">Введите старый пароль.</div>
      <button>Сохранить</button>
    </form>
    </main>
  </body>
  <script src="js/check-input-data.js"></script>
  <script src="/js/user-settings.js"></script>
  <script src="/js/header.js"></script>
</html>
