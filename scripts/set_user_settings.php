<?php

/*
*
* /scripts/set_user_settings.php
*
* Сохранение настроек пользователя.
*
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header ('Location: /');
  exit;
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_tables.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_functions.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_vars.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";


// Нужно проверять, если есть юзер.имейл, заменять запись, иначе вставлять
// Если новый пароль совпадает со старым, ошибка. Новый пароль совпадает со старым.
// Отсылать ошибки назад, как в форме регистрации. Или записать данные

if (!session_id()) session_start();

if (!isset($_SESSION['user_id'])) {
  header ('Location: /login.php');
  exit;
}

$error_message = "";
$user = new User();

function validate_input_data($pattern, $data) { // вынести в global functions + из /includes/registration.php
  preg_match($pattern, $data, $match);
  return $match[0]?? null;
}

if (!empty($_POST['email'])) {
  $email = validate_input_data("/^[a-zA-Z0-9_\-]+@[a-zA-Z0-9_\.\-]+$/", $_POST['email']);
}

if (!empty($_POST['email']) and empty($email)) {
  $error_message .= "<p>Адрес e-mail указан неверно.</p>\n\n";
}

if (empty($_POST['email'])) {
  $email = "";
}

if (!empty($_POST['password'])) {
  $new_pass = validate_input_data("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}$/", $_POST['password']);
}

if (!empty($_POST['password']) and $_POST['password'] === $_POST['confirm-password']) {
  $confirm_pass = true;
} else if  (!empty($_POST['password'])){
  $error_message .= "<p>Пароли не совпадают.</p>\n\n";
  $confirm_pass = false;
}

// Проверить, правилен ли старый пароль.
$inputed_old_pass = get_hash($_POST['old-password'], $user->salt, HASH_KEY);
if ($inputed_old_pass !== $user->pass) {
  $error_message .= "<p>Пароль не верен.</p>\n\n";
}

if ($email !== $user->email and empty($error_message)) {
  // Заменить имейл
  $query = "UPDATE `pn_users` SET `email` = '{$email}' WHERE `id` = '{$user->id}'";
  mysqli_query($db_connection, $query) or die("$query");
  $user->email = $_SESSION['user_email'] = $email;
}

if (!empty($new_pass) and $confirm_pass and empty($error_message)) {
  // Заменить пароль и соль
  $new_salt = get_hash(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX));
  $new_pass = get_hash($new_pass, $new_salt, HASH_KEY);
  $query = "UPDATE `pn_users` SET `pass` = '{$new_pass}', `salt` = '{$new_salt}' WHERE `id` = '{$user->id}'";
  mysqli_query($db_connection, $query) or die("$query");
  $user->pass = $_SESSION['user_pass'] = $new_pass;
  $user->salt = $_SESSION['user_salt'] = $new_salt;
}

$_SESSION['error_message'] = $error_message;
header ("Location: /user_settings.php");
exit;
