<?php

  /*
   * Обработка введённых пользователем данных и создание капчи для формы регистрации.
   *
   */

  session_start();

  include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/Captcha.php";
  include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
  include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_tables.php";
  include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";


  function validate_input_data($pattern, $data) {

    preg_match($pattern, $data, $match);

    return $match[0]?? null;

  }

  function check_login($name, $db_connection) {

    $query = "SELECT `login` FROM `pn_users` WHERE `login` = '".strtolower($name)."'";
    $result = mysqli_query($db_connection, $query) or die("Ошибка5"."<br>".$query."<br>");
    $result = mysqli_fetch_assoc($result);

    return !$result;
  }

    /* Обработка входных данных */

  $name = "";
  $login = "";
  $email = "";
  $pass = "";

  $error_message = "";

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Определяем валидность и доступность имени пользователя

    if ($_POST['name']) {

      $name = validate_input_data("/^[a-zA-Z0-9_]{1,24}$/", $_POST['name']);

    }

    if (!$name) {

      $error_message .= "<p>Неверно указано имя пользователя.</p>\n\n";

    }

    if (check_login($name, $db_connection)) {

      $login = strtolower($name);

    } else if ($_POST['name']) {

      $error_message .= "<p>К сожалению, это имя уже занято. Попробуйте другое.</p>\n\n";

    }

    // Определяем валидность и совпадение пароля

    if ($_POST['password']) {

      $pass = validate_input_data("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}$/", $_POST['password']);

    }

    if (!$pass) {

      $error_message .= "<p>Неверный формат пароля.</p>\n\n";

    }

    if ($_POST['password'] === $_POST['confirm-password']) {

      $confirm_pass = true;

    } else {

      $error_message .= "<p>Пароли не совпадают.</p>\n\n";

    }

    // Определяем валидность адреса e-mail

    if ($_POST['email']) {

      $email = validate_input_data("/^[a-zA-Z0-9_\-]+@[a-zA-Z0-9_\.\-]+$/", $_POST['email']);

    }

    if ($_POST['email'] && !$email) {

      $error_message .= "<p>Адрес e-mail указан неверно.</p>\n\n";

    }

    // Определяем правильно ли введён ответ капчи

    if ($_POST['captcha-answer'] === (string)$_SESSION['captcha-answer']) {

      $captcha = true;

    } else {

      $error_message .= "<p>Неверный ответ на контрольный вопрос.</p>\n\n";

    }

    // Функция вычисляет хэш на основании трёх аргументов
    // Намеренно используется алгоритм который сложно угадать

    function get_hash($arg1, $arg2, $arg3) {

      $hash_1 = md5($arg1);
      $hash_2 = sha1($arg2);
      $hash_3 = md5($arg3);

      $hash_4 = md5(substr($hash_1, 0, 16).substr($hash_3, 16, 32).substr($hash_2, 20, 40));
      $hash_5 = md5(substr($hash_2, 0, 20).substr($hash_1, 16, 32).substr($hash_3, 0, 16));

      return substr(sha1($hash_5.$hash_4.$arg2), 8, 40);
    }

    // Устанавливаем рандомную соль для пароля и сам пароль

    $salt = get_hash(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX));

    $pass_hash = get_hash($pass, $salt, $key);

    // Если всё верно, записываем аккаунт в БД и переадресовываем браузер на страницу приветствия.

    if ($name && $login && $pass && $confirm_pass && ($email || !$_POST['email']) && $captcha ) {

      $query = "INSERT INTO `pn_users` SET `name` = '{$name}', `login` = '{$login}', `hash` = '{$pass_hash}', `salt` = '{$salt}', `group` = 4";

      mysqli_query($db_connection, $query) or die (mysqli_error($db_connection)."<p>".$query);

      header ("Location: ./greeting.php");

    }
  }

  $captcha = new Captcha();

  $captcha->html_content = $captcha->captcha_html();

  $_SESSION['captcha-answer'] = $captcha->answer;
