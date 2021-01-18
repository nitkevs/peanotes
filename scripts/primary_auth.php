<?php

/*
*
* /scripts/primary_auth.php
*
* Скрипт авторизации пользователей
* Реализует авторизацию по логину и паролю.
* Файл инклюдится в login.php только в случае
* отправки пользователем формы входа.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!session_id()) session_start();

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_tables.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_functions.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_vars.php";

// Переводим логин в нижний регистр, потому что пользователь
// может вводить вместо него никнейм, выбранный во время
// регистрации. Если ему так удобнее, переводим никнейм в нижний
// регистр и получаем логин.
$received_login = strtolower($_POST['login']);
$received_login = htmlspecialchars($received_login);
$received_pass = $_POST['password'];

// Получаем данные юзера из БД
$query = "SELECT * FROM `pn_users` WHERE `login` = '{$received_login}'";
$result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
// Записываем данные пользователя в переменную
$user_data = mysqli_fetch_assoc($result);

if (!empty($user_data)) {
  $user_id = $user_data['id'];
  $user_name = $user_data['name'];
  $user_login = $user_data['login'];
  $user_pass = $user_data['pass'];
  $salt = $user_data['salt'];
  $user_group = (integer)$user_data['group'];
  $ban_severity = (integer)$user_data['ban_severity'];
  $ban_expires = (integer)$user_data['ban_expires'];
  $email = $user_data['email'];
}

// Если пользователь с таким логином не найден,
// возвращаемся к форме входа с сообщением об ошибке.
if (empty($user_data)) {
  header("Location: /login.php?user-does-not-exist=1&non-existent-name={$received_login}");
  exit;
}

// Если установлен и ещё не просрочен запрет авторизации,
// возвращаемся к форме входа с сообщением об ошибке.
if ($ban_expires > time()) {
  header("Location: /login.php?ban-expires={$ban_expires}&ban-severity={$ban_severity}&login={$received_login}");
  exit;
}

if ($user_pass == get_hash($received_pass, $salt, HASH_KEY)) { // Если пароль прошёл проверку
  if ($ban_severity > 0) { // Но ранее был установлен запрет авторизации
    $query = "UPDATE `pn_users` SET `ban_expires` = '0', `ban_severity` = 0 WHERE `id` = {$user_id}";
    mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query); // Сбрасываем его.
  }

  set_session($user_id); // Создаём сессию в БД и куку
  // устанавливаем $_SESSION['user_id'], так как именно отсюда система будет
  // брать информацию о том, вошёл ли кто-то в систему.
  $_SESSION['user_id'] = $user_id;
  $_SESSION['user_name'] = $user_name;
  $_SESSION['user_login'] = $user_login;
  $_SESSION['user_group'] = $user_group;
  $_SESSION['user_email'] = $email;
  $_SESSION['user_pass'] = $user_pass;
  $_SESSION['user_salt'] = $salt;

  header("Location: /");
  exit;

} else { // Если пароль не соответствует

  if ($ban_severity < 4) {
    $ban_severity++;
}

  $severity_rules = [0, 0, 30, 5*60, 30*60]; // Список таймаутов для разных степеней строгости бана (0-4)
  $ban_timeout = $severity_rules[$ban_severity];
  $ban_expires = time() + $ban_timeout;
  $query = "UPDATE `pn_users` SET `ban_expires` = '{$ban_expires}', `ban_severity` = {$ban_severity} WHERE `id` = {$user_id}";
  mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);

  header("Location: /login.php?ban-expires={$ban_expires}&ban-severity={$ban_severity}&login={$received_login}");
  exit;
}
