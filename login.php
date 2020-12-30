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
      margin-bottom: 12vh;
      padding: 10px 85px;
      border: 1px solid #abbdc4;
      background: #c3cfd5;
      border-radius: 5px;
    }
}

    </style>
  </head>
  <body>
    <main  class="login-form-container">
    <div class="login-form-container">
      <h1 class="centered"><?= $title ?></h1>
      <form action="includes/auth.php" method="post" id="login-form">
      <?php
        if ($error_message) {
          echo "<div id=\"auth-errors\">{$error_message}</div>";
        }
      ?>
        <label for="login">Логин:</label>
        <input type="text" id="login" name="login" maxlength="24" required value="<?= $login ?>">

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <button>Войти</button>
      </form>
    </div>
    </main>
  </body>
</html>
