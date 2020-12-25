<?php

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

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    <style>
    .description {
      grid-column-start: 1;
      grid-column-end: 3;
      padding-left: 150px;
      color: #999;
      font-family: sans-serif;
      font-size: 12px;
    }

    #confirm-password + .description {
      font-weight: bold;
    }

    .term-conditions, #captcha {
      grid-column-start: 1;
      grid-column-end: 3;
    }

    iframe#term-conditions {
      display: none;
      width: 75%;
      margin: 0 auto;
      height: 300px;
      border: 1px solid #bbb;
      border-radius: 2px;
    }

    .term-conditions a:hover {
      text-decoration: underline;
    }

    #captcha {
      margin: 0;
      padding: 12px 18px;
      background: #f8f7d3;
      border: 1px solid #e6e6bc;
    }

    #reg-errors {
      grid-column-start: 1;
      grid-column-end: 3;
      margin: -12px 0 18px 0;
      padding: 4px 16px;
      line-height: 18px;
      font-size: 13px;
      font-family: sans-serif;
      background: #ffb8b8;
      border: 1px solid #bf9797;
    }

    </style>
  </head>
  <body>
    <main>
    <h1>Регистрация</h1>
    <p>Поля, отмеченные звёздочками обязательны для заполнения.</p>
    <form action="" method="post" id="register-form">
    <?php
      if ($error_message) {
        echo "<div id=\"reg-errors\">{$error_message}</div>";
      }
    ?>
      <label for="name" class="required">Имя:</label>
      <input type="text" id="login" name="name" maxlength="24" required pattern="[A-Za-z0-9_]{1,24}" value="<?= $name ?>">
      <div class="description">Имя может состоять из <span class="highlight">латинских букв, цифр и знака _ и быть длинной не более 24 символов</span>.</div>

      <label for="password" class="required">Пароль:</label>
      <input type="password" id="password" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}">
      <div class="description">Пароль должен содержать по крайней мере одно число, одну заглавную и строчную буквы и быть длинной не менее 10 символов</div>

      <label for="confirm-password" class="required">Повторите пароль:</label>
      <input type="password" id="confirm-password"  name="confirm-password" required>
      <div class="description">Пароли должны совпадать.</div>

      <label for="email">Адрес e-mail:</label>
      <input type="email" id="email" name="email" value="<?= $email ?>">
      <div class="description">Это поле необязательно, но с помощью e-mail можно, в случае чего, восстановить пароль.</div>

        <?= $captcha->html_content; ?>

      <div class="term-conditions">
        <input type="checkbox" name="conditions-consent" id="conditions-consent" required> <label for="conditions-consent" class="required">Я согласен с <a href="javascript: showTerms();">правилами использования сервиса</a>.</label>
      </div>
      <button>Отправить</button>
    </form>
    <iframe src="term-conditions.php" id="term-conditions"></iframe>

    </main>
  </body>
  <script>
    let formFields = document.querySelectorAll ('form > input:not(#confirm-password)');

    for (i = 0; i < formFields.length; i++) {

      formFields[i].addEventListener('input', function() {
        if(this.checkValidity() || !this.value) {
          this.style.color = "rgb(51, 51, 51)";
        } else {
          this.style.color = "rgb(223, 32, 32)";
        }
      });

      formFields[i].addEventListener('focus', function() {
        let description = this.nextElementSibling;
        description.style.color = "rgb(51, 51, 51)";
      });

      formFields[i].addEventListener('blur', function() {
        let description = this.nextElementSibling;
        description.style.color = "rgb(153, 153, 153)";
      });
    }

    let pass = document.getElementById('password');
    let confirmPass = document.getElementById('confirm-password');
    let genericColor = "rgb(153, 153, 153)";

    function checkPass() {

      let description = confirmPass.nextElementSibling;

      if (confirmPass.value === pass.value) {
        confirmPass.style.color = genericColor;
        description.style.color = genericColor;
        description.textContent = "Пароли совпадают.";
      } else if (confirmPass.value) {
        confirmPass.style.color = "rgb(223, 32, 32)";
        description.style.color = "rgb(223, 32, 32)";
        description.textContent = "Пароли не совпадают!";
      } else {
        description.textContent = "Пароли не совпадают!";
      }
    }

    confirmPass.addEventListener('input', checkPass);
    pass.addEventListener('input', checkPass);

    confirmPass.addEventListener('focus', function() {
      let description = this.nextElementSibling;
      genericColor = "rgb(51, 51, 51)";
      if (description.style.color !== "rgb(223, 32, 32)"){
        description.style.color = "rgb(51, 51, 51)";
      }
    });

    confirmPass.addEventListener('blur', function() {
      let description = this.nextElementSibling;
      genericColor = "rgb(153, 153, 153)";
      if (description.style.color !== "rgb(223, 32, 32)"){
        description.style.color = "rgb(153, 153, 153)";
      }
    });

    function showTerms() {
      let frame = document.getElementById('term-conditions');
      frame.style.display = "block";
      frame.scrollIntoView({behavior: 'smooth'});
      console.log(frame);
    }

    let registerForm = document.querySelector('#register-form');

    registerForm.addEventListener('submit', function(event) {
      event.preventDefault();
      if (confirmPass.value !== pass.value) {
        alert("Пароли не совпадают!");
        return false;
      } else {
        this.submit();
      }
    });



// вынести всё во внешний файл


// вынесение стилей в отдельный файл

  </script>
</html>
