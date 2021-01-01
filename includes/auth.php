<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!session_id()) {
  session_start();
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_tables.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";

function get_hash($arg1, $arg2, $arg3) {

      $hash_1 = md5($arg1);
      $hash_2 = sha1($arg2);
      $hash_3 = md5($arg3);

      $hash_4 = md5(substr($hash_1, 0, 16).substr($hash_3, 16, 32).substr($hash_2, 20, 40));
      $hash_5 = md5(substr($hash_2, 0, 20).substr($hash_1, 16, 32).substr($hash_3, 0, 16));

      return substr(sha1($hash_5.$hash_4.$arg2), 8, 40);
    }

    // Создать хэш сессии
    // Создать сессию
    // Создать куку на 30 дней
    // Перейти на главную

    function set_session($user_id) {
      global $user_agent_hash;
      global $db_connection;

      $random_hash = get_hash(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX));
      $coockie_hash = get_hash($random_hash, $user_id, $user_agent_hash);
      $session_expires = time() + (60*60*24*30);
      $new_session_hash = get_hash($coockie_hash, $user_agent_hash, HASH_KEY);

      setcookie('session', $coockie_hash, $session_expires, "/");

      $query = "INSERT INTO `pn_sessions` SET `user_id` = '{$user_id}', `hash` = '{$new_session_hash}', `user_agent` = '{$user_agent_hash}', `expires` = '{$session_expires}'";
      $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);

    }

$user_agent_hash = md5($_SERVER['HTTP_USER_AGENT']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user = new User();

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

   if ($user_info['pass'] == get_hash($received_pass, $user->salt, HASH_KEY)) {

    set_session($user_info['id']);
    $_SESSION['user_id'] = $user_info['id'];

    header("Location: /");
    exit;

  } else {

    header("Location: /login.php?login=0");
    exit;
  }


  //- Если запрошен методом get, то есть сессия php удалена
} else if (isset($_COOKIE['session'])) {

  // Если файл не запрошн методом post, то...
  //- Проверить легимитность куки
  $cookies_session_hash = get_hash($_COOKIE['session'], $user_agent_hash, HASH_KEY);
  $query = "SELECT * FROM `pn_sessions` WHERE `hash` = '{$cookies_session_hash}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
  $session = mysqli_fetch_assoc($result);

  // Если кука не просрочена и браузер совпадает с записанным в БД,
  if ((integer)$session['expires'] > time() and $session['user_agent'] === $user_agent_hash) {

    // ЗАМЕНИТЬ КУКУ И СЕССИЮ
    setcookie('session', '', time());
    $query = "DELETE FROM `pn_sessions` WHERE `hash` = '{$cookies_session_hash}'";
    $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
    session_destroy();

    set_session($session['user_id']);

    return $session['user_id'];
  } else {
    // или удалить куку и сессию
    setcookie('session', '', time());
    $query = "DELETE FROM `pn_sessions` WHERE `hash` = '{$cookies_session_hash}'";
    $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
    session_destroy();
    die ("Сессия просрочена или браузер не совпадает.".(integer)$session['expires']." ".time(). " ".$session['user_agent']." ".$user_agent_hash);
    header("Location: /login.php");
    exit;
  }
} else {
      header("Location: /login.php");
      exit;
}


// Файл возвращает стр()"2", хотя сессия удалена
