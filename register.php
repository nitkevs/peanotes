<?php

$title = "Регистрация";
$root_dir = "/php/peanotes";
$favicon = "/images/icons/favicon.ico";

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $root_dir.$favicon ?>">
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

    </style>
  </head>
  <body>
    <main>
    <h1><?= $title ?></h1>
    <p>Поля, отмеченные звёздочками обязательны для заполнения.</p>
    <form action="" method="post" id="register-form">

      <label for="name" class="required">Имя:</label>
      <input type="text" id="login" name="name" maxlength="24" required="required" pattern="[A-Za-z0-9_]{1,24}">
      <div class="description">Имя может состоять из <span class="highlight">латинских букв, цифр и знака _ и быть длинной не более 24 символов</span>.</div>

      <label for="password" class="required">Пароль:</label>
      <input type="password" id="password" name="password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}">
      <div class="description">Пароль должен содержать по крайней мере одно число, одну заглавную и строчную буквы и быть длинной не менее 10 символов</div>

      <label for="confirm-password" class="required">Повторите пароль:</label>
      <input type="password" id="confirm-password" required="required">
      <div class="description">Пароли не совпадают!</div>

      <label for="email">Адрес e-mail:</label>
      <input type="email" id="email" name="email">
      <div class="description">Это поле необязательно, но с помощью e-mail можно, в случае чего, восстановить пароль.</div>

      <div id=""></div>
      <button>Отправить</button>
    </form>

    </main>
  </body>
  <script>
    let formFields = document.querySelectorAll ('input:not(#confirm-password)');

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




  </script>
</html>
