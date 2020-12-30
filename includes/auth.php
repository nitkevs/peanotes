<?php



ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_tables.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";

$user = new User();

function get_hash($arg1, $arg2, $arg3) {

      $hash_1 = md5($arg1);
      $hash_2 = sha1($arg2);
      $hash_3 = md5($arg3);

      $hash_4 = md5(substr($hash_1, 0, 16).substr($hash_3, 16, 32).substr($hash_2, 20, 40));
      $hash_5 = md5(substr($hash_2, 0, 20).substr($hash_1, 16, 32).substr($hash_3, 0, 16));

      return substr(sha1($hash_5.$hash_4.$arg2), 8, 40);
    }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  //   Переводим логин в нижний регистр, потому что пользователь
  //   может вводить вместо него никнейм, выбранный во время
  //   регистрации. Если ему так удобнее, переводим никнейм в нижний
  //   регистр и получаем лоигн.
  $received_login = strtolower($_POST['login']);
  $received_login = htmlspecialchars($received_login);
  $received_pass = $_POST['password'];

  // Получаем данные юзера из БД

  $query = "SELECT * FROM `pn_users` WHERE `login` = '{$received_login}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
  $user_info = mysqli_fetch_assoc($result);

  // Если пользователь с таким логином не найден,
  // возвращаемся к форме входа с сообщением об ошибке.
  if (empty($user_info)) {
      header("Location: /login.php?user-does-not-exist={$received_login}");
      exit;
  }

  // Если установлен запрет авторизации, возвращаемся к форме входа
  // с сообщением об ошибке.
  if (!empty($user_info['ban_expires'])) {
      $ban_timeout = $user_info['ban_expires'] - time();
      header("Location: /login.php?ban-timeout={$ban_timeout}&&login={$received_login}");
      exit;
  }

  // Следующие шаги определяют идентичность пароля.

  $user->salt = $user_info['salt'];


  $_SESSION['hash1'] = $user_info['pass'];
  $_SESSION['hash2'] = get_hash($received_pass, $user->salt, $key);
  $_SESSION['user_info'] = $user_info;


   if ($user_info['pass'] == get_hash($received_pass, $user->salt, $key)) {
     $user->id = $_SESSION['user_id'] = $user_info['id'];

    // Устанавливаем соль сессии2 (rand(0, пхп-макс-инт),rand(0, пхп-макс-инт), rand(0, пхп-макс-инт))
    // Создать сессию2 с хешем (соль сессии2, id, юзер_агент) и временем истечения (30 дней);
    // Создать куку с хешем (хеша сессии2, юзер_агента и ключ) на 30 дней;
    // Переходим на главную

    $random_hash = get_hash(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX));
    $new_session_hash = get_hash($random_hash, $user->id, $_SERVER['HTTP_USER_AGENT']);
    $session_expires = time() + (60*60*24*30);
    $coockie_hash = get_hash($new_session_hash, $_SERVER['HTTP_USER_AGENT'], $key);
    $user_agent_hash = md5($_SERVER['HTTP_USER_AGENT']);

    setcookie('session', "{$coockie_hash}");

    $query = "INSERT INTO `pn_sessions` SET `user_id` = '{$user->id}', `hash` = '{$new_session_hash}', `user_agent` = '{$user_agent_hash}', `expires` = '{$session_expires}'";

    $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);

    header("Location: /");
    exit;

   } else {

    header("Location: /login.php?login=0");
    exit;
   }

}



