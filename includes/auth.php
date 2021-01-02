<?php

/*
*
* auth.php
*
* Скрипт авторизации пользователей
* Реализует авторизацию по логину и паролю,
* либо по куки-файлам.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!session_id()) {
  session_start();
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_tables.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_functions.php";

function remove_session($session_hash) {
  global $db_connection;

  setcookie('session', '', time());

  $query = "DELETE FROM `pn_sessions` WHERE `hash` = '{$session_hash}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
  session_destroy();
}

function set_session($user_id) {
  global $user_agent_hash;
  global $db_connection;

  $random_hash = get_hash(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX));
  $coockie_hash = get_hash($random_hash, $user_id, $user_agent_hash);
  $session_expires = time() + (60*60*24*30);
  $new_session_hash = get_hash($coockie_hash, $user_agent_hash, HASH_KEY);

  setcookie('session', $coockie_hash, $session_expires, "/"); // Создать куку на 30 дней

  $query = "INSERT INTO `pn_sessions` SET `user_id` = '{$user_id}', `hash` = '{$new_session_hash}', `user_agent` = '{$user_agent_hash}', `expires` = '{$session_expires}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query); // Создать сессию
}

$user_agent_hash = md5($_SERVER['HTTP_USER_AGENT']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
    $ban_severity = (integer)$user_data['ban_severity'];
    $ban_expires = (integer)$user_data['ban_expires'];
    $user_id = $user_data['id'];
    $salt = $user_data['salt'];
  }

  // Если пользователь с таким логином не найден,
  // возвращаемся к форме входа с сообщением об ошибке.
  if (empty($user_data)) {
    header("Location: /login.php?user-does-not-exist=1&&name={$received_login}");
    exit;
  }

  // Если установлен и ещё не просрочен запрет авторизации,
  // возвращаемся к форме входа с сообщением об ошибке.
  if ($ban_expires > time()) {
    header("Location: /login.php?ban-expires={$ban_expires}&&login={$received_login}");
    exit;
  }

  if ($user_data['pass'] == get_hash($received_pass, $salt, HASH_KEY)) { // Если пароль прошёл проверку
    if ($ban_severity > 0) { // Но ранее был установлен запрет авторизации
      $query = "UPDATE `pn_users` SET `ban_expires` = '0', `ban_severity` = 0 WHERE `id` = {$user_id}";
      mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query); // Сбрасываем его.
    }

    set_session($user_id); // Создаём сессию в БД и куку
    // устанавливаем $_SESSION['user_id'], так как именно отсюда index.php будет
    // брать информацию о том, вошёл ли кто-то в систему.
    $_SESSION['user_id'] = $user_id;
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

    header("Location: /login.php?ban-expires={$ban_expires}&&login={$received_login}");
    exit;
  }

}

if ($_SERVER['REQUEST_METHOD'] == 'GET' and isset($_COOKIE['session'])) { // Сработает, если сессия php была окончена

  // Получаем хэш сессии, соответствующий хэшу куки
  $cookies_session_hash = get_hash($_COOKIE['session'], $user_agent_hash, HASH_KEY);
  // Ищем эту сессию в БД
  $query = "SELECT * FROM `pn_sessions` WHERE `hash` = '{$cookies_session_hash}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
  // Записываем данные о найденной сессии в переменную
  $session = mysqli_fetch_assoc($result);

  // Если браузер совпадает с записанным в сессии,
  if ($session['user_agent'] === $user_agent_hash) {

    // Заменить куку и сессию
    remove_session($cookies_session_hash);
    set_session($session['user_id']);

    return $session['user_id'];
  } else if (isset($_COOKIE['session'])) { // Если сессия из куки есть, но браузер не совпадает
    // удалить куку и сессию
    remove_session($cookies_session_hash);

    header("Location: /login.php");
    exit;
  }
} else {
    header("Location: /login.php");
    exit;
}
