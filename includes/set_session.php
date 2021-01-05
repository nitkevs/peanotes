<?php

/*
*
* /includes/set_session.php
*
* Скрипт авторизации пользователей
* Реализует авторизацию по куки-файлу.
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


if (isset($_COOKIE['session'])) { // Сработает, если сессия php была окончена, но куки-файл авторизации найден.
 // Получаем сессию соответствующую полученной куке
  $cookies_session_hash = get_hash($_COOKIE['session'], $user_agent_hash, HASH_KEY);
  // Ищем эту сессию в БД
  $query = "SELECT * FROM `pn_sessions` WHERE `hash` = '{$cookies_session_hash}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
  // Записываем данные о найденной сессии в переменную
  $session_data = mysqli_fetch_assoc($result);

  if (empty($session_data)) { // Если такой сессии нет

    header("Location: /login.php");
    exit;
  }

  // Если браузер совпадает с записанным в сессии,
  if ($session_data['user_agent'] === $user_agent_hash) {

    // Заменить куку и сессию
    remove_session($cookies_session_hash);
    set_session($session_data['user_id']);

    $query = "SELECT * FROM `pn_users` WHERE `id` = '{$session_data['user_id']}'";
    $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
    // Записываем данные пользователя в переменную
    $user_data = mysqli_fetch_assoc($result);

    $user_id = $user_data['id'];
    $user_name = $user_data['name'];
    $user_login = $user_data['login'];
    $user_pass = $user_data['pass'];
    $salt = $user_data['salt'];
    $user_group = (integer)$user_data['group'];
    $ban_severity = (integer)$user_data['ban_severity'];
    $ban_expires = (integer)$user_data['ban_expires'];
    $email = $user_data['email'];

    // устанавливаем $_SESSION данные пользователя.
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_login'] = $user_login;
    $_SESSION['user_group'] = $user_group;
    $_SESSION['user_email'] = $email;

    } else  { // Если сессия из куки есть, но браузер не совпадает
    // удалить куку и сессию
    remove_session($cookies_session_hash);

    header("Location: /login.php");
    exit;
    }
} else {

    header("Location: /login.php"); // Кука не получена, уходим на login.php.
    exit;
  }
