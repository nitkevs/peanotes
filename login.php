<?php

   /*
    * Форма регистрации пользователя.
    *
    * Переменные:
    *
    * $error_message: Сообщение об ошибке регистрации (если она есть).
    * $name: имя пользователя, если оно было введено до возникновения ошибки
    *   (например, если неправильно введена капча).
    * $email e-mail пользователя, если оно было введено до возникновения ошибки
    * $captcha->html_content: html-код блока капчи.
    *
    */

  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

//   include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";

  $title = "Вход";


  $error_message = ""; // Вынести в auth.php (???)
  $login = "";

  if (isset($_GET['user-does-not-exist'])) {
    $error_message = "Пользователь {$_GET['name']} не найден. Вы можете <a href=\"/registration-form.php?name={$_GET['name']}\">зарегистрироваться</a> с таким именем.";
  } else if (isset($_GET['ban-expires'])) {
    $error_message = "Неверный пароль. Попробуйте ещё раз через <span id=\"timer\"></span>.";
    $ban_timeout = $_GET['ban-expires'] - time();
  }


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    <style>

      #login-form {
        width: 200px;
        margin: 0 auto;
      }

      #login-form label,
      #login-form input,
      #login-form button {
        display: block;
      }

      #login-form input[type="text"],
      #login-form input[type="password"] {
        width: 200px;
        padding: 2px;
      }

      .centered {
        text-align: center;
      }

      h1.centered {
        margin: 12px 0;
      }

      #login-form label {
        margin: 2px;
        padding: 12px 0px;
      }

      #login-form button {
        margin: 25px auto;
      }


     main.login-form-container {
       margin: 0 auto;
       height: 100vh;
       display: grid;
       justify-content: center;
       align-items: center;
     }

    div.login-form-container {
      margin: 0 auto calc(50vh - 160px) auto;
      padding: 10px 85px;
      width: 372px;
      border: 1px solid #abbdc4;
      background: #c3cfd5;
      border-radius: 5px;
    }

    div#error_message {
      align-self: end;
      padding: 0px 25px;
      width: 65vw;
      height: 70px;
      border: 1px solid #999;
      background: #fdd;
      line-height: 65px;
      text-align: center;
    }

    </style>
  </head>
  <body>
    <main  class="login-form-container">
    <div id="error_message">
      <?= $error_message ?>
    </div>
    <div class="login-form-container">
      <h1 class="centered"><?= $title ?></h1>
      <form action="includes/auth.php" method="post" id="login-form">
        <label for="login">Логин:</label>
        <input type="text" id="login" name="login" maxlength="24" required value="<?= $login ?>">

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <button id="submit">Войти</button>
      </form>
    </div>
    </main>
  </body>


  <script>

  <?php if (!isset($error_message)): ?>
    let errorMessage = document.getElementById('error_message');
    errorMessage.style.opacity = "0";

  <?php endif; ?>


  <?php
  if (isset($ban_timeout)):
  ?>

    let timer = document.getElementById('timer');
    // получить задержку
    let timeout = <?= $ban_timeout ?>;
    // усли таймаут не вышел, заблокировать ввод
    if (timeout > 0) {
      //  УСТАНОВИТЬ ТАЙМЕР
      // ЗАБЛОКИРОВАТЬ ВВОД
      function clock() {
        let submit = document.getElementById('submit');
        submit.setAttribute("disabled", "disabled");
        if (timeout >= 60) {
          let minutes = Math.floor(timeout / 60);
          timer.textContent = minutes + ':';
           }

        let seconds = timeout % 60;
        if (seconds < 10 && timeout > 10) seconds = "0" + String(seconds);

        if (timeout < 60) {
          timer.textContent = seconds + " секунд";
        } else {
          timer.textContent += seconds;
        }

        if (timeout == 0) {
           clearInterval(timeCounter);
           submit.removeAttribute("disabled");
           let errorMessage = document.getElementById('error_message');
           errorMessage.style.opacity = "0";
        }
        timeout--;

      }

      let timeCounter = setInterval(clock, 1000);
    }
    // создать таймер

  <?php endif; ?>
  </script>


</html>
