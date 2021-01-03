<?php

/*
* /login.php
*
* Форма входа на сайт.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$title = "Вход";

$error_message = "";
$login = "";

if (isset($_GET['user-does-not-exist'])) {
  $error_message = "Пользователь {$_GET['name']} не найден. Вы можете <a href=\"/registration-form.php?name={$_GET['name']}\">зарегистрироваться</a> с таким именем.";
} else if (isset($_GET['ban-expires']) and $_GET['ban-expires'] > time()) {
  $error_message = "Неверный пароль. Попробуйте ещё раз через <span id=\"timer\"></span>.";
  $ban_timeout = $_GET['ban-expires'] - time();
} else if (isset($_GET['ban-expires']) and $_GET['ban-severity'] === "1") {
  $error_message = "Неверный пароль. Попробуйте ещё раз.";
//   $ban_timeout = 0;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
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
  <?php if (empty($error_message)): ?>
    let errorMessage = document.getElementById('error_message');
    errorMessage.style.opacity = "0"; // Делаем пустой блок вывода ошибок невидимым.
  <?php endif; ?>
  <?php if (isset($ban_timeout)): ?>// Если из адресгой строки получено значение таймаута бана

    let timer = document.getElementById('timer');
    // передать величину задержки в JS
    let timeout = <?= $ban_timeout ?>;
    // усли таймаут не окончен, заблокировать ввод и вывести сообщение с обратным отсчётом
    if (timeout > 0) {
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
  <?php endif; ?>
  </script>
</html>
