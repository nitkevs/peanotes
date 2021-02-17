<?php

/*
*
* /includes/registration.php
*
* Скрипт осуществляет регистрацию пользователя.
* Обрабатывает введённые пользователем данные,
* в случае успеха записывает в БД новый аккаунт.
*
*/

if (!session_id()) session_start();

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_tables.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_functions.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_vars.php";

// Функция проверяет, есть ли заданный логин в БД.
function check_login($name, $db_connection) {
  $query = "SELECT `login` FROM `pn_users` WHERE `login` = '".strtolower($name)."'";
  $result = mysqli_query($db_connection, $query) or die("Ошибка5"."<br>".$query."<br>");
  $result = mysqli_fetch_assoc($result);

  return empty($result);
}

$name = "";
$login = "";
$email = "";
$pass = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Определяем валидность и доступность имени пользователя

  if (isset($_POST['name'])) {
    $name = validate_input_data("/^[a-zA-Z0-9_]{1,24}$/", $_POST['name']);
  }

  if (empty($name)) {
    $error_message .= "<p>Неверно указано имя пользователя.</p>\n\n";
  }

  if (check_login($name, $db_connection)) {  // Если логин не занят
    $login = strtolower($name);
  } else if ($_POST['name']) { // Сработает, если имя введено, но занято
    $error_message .= "<p>К сожалению, это имя уже занято. Попробуйте другое.</p>\n\n";
  }

  // Определяем валидность и совпадение пароля
  if (!empty($_POST['password'])) {
    $pass = validate_input_data("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}$/", $_POST['password']);
  }

  if (empty($pass)) { // Если validate_input_data вернула null
    $error_message .= "<p>Неверный формат пароля.</p>\n\n";
  }

  if (!empty($_POST['password']) and $_POST['password'] === $_POST['confirm-password']) {
    $confirm_pass = true;
  } else {
    $error_message .= "<p>Пароли не совпадают.</p>\n\n";
    $confirm_pass = false;
  }

  // Определяем валидность адреса e-mail
  if (!empty($_POST['email'])) {
    $email = validate_input_data("/^[a-zA-Z0-9_\-]+@[a-zA-Z0-9_\.\-]+$/", $_POST['email']);
  }

  if (!empty($_POST['email']) and empty($email)) {
    $error_message .= "<p>Адрес e-mail указан неверно.</p>\n\n";
  }

  // Определяем правильно ли введён ответ капчи
  if (!empty($_POST['captcha-answer']) and $_POST['captcha-answer'] === (string)$_SESSION['captcha-answer']) {
    $captcha_answer_is_correct = true;
  } else {
    $error_message .= "<p>Неверный ответ на контрольный вопрос.</p>\n\n";
    $captcha_answer_is_correct = false;
  }

  // Устанавливаем рандомную соль для пароля и сам пароль
  $salt = get_hash(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX));
  $pass_hash = get_hash($pass, $salt, HASH_KEY);

  // Если всё верно, записываем аккаунт в БД и переадресовываем браузер на страницу приветствия.
  if (!empty($name) and !empty($login) and !empty($pass) and $confirm_pass and (!empty($email) or empty($_POST['email'])) and $captcha_answer_is_correct ) {

    $query = "INSERT INTO `pn_users` SET `name` = '{$name}', `login` = '{$login}', `pass` = '{$pass_hash}', `salt` = '{$salt}', `group` = 4, `ban_expires` = '0', `ban_severity` = 0, `email` = '{$email}'";
    mysqli_query($db_connection, $query) or die (mysqli_error($db_connection)."<p>".$query);

  // Авторизируем нового пользователя.
  set_session(mysqli_insert_id($db_connection));

  header ("Location: ./greeting.php");
  exit;
  }
}
