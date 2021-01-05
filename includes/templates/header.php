<?php

/*
*
* /includes/blocks/header.php
*
* Содержимое блока <header> страниц сайта.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$page_header = <<<CODE
    <header>
      <div id="logo"><a href="./">Peanotes</div>
      <nav id="header-navigation">
CODE;

if (empty($user->id)) {
$page_header .= <<<CODE
        <a href="logout.php">Войти</a>
        <a href="registration-form.php">Регистрация</a>
CODE;
}

// if ($user->id === 1){}

if ($user->id >= 1) {
$page_header .= <<<CODE
        <style>
          #header-user-menu {
            display: inline-block;

          }

          .user-menu {
    position: absolute;
    display: block;
    height: 0px;
    overflow: hidden;
    transition-duration: 0.2s;
    background: #5f7c8a;
    top: 52px;
    padding: 0px 15px 0px 10px;
    margin: 0;
    list-style-type: none;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
          }
        </style>
        <a href="/">Мои заметки</a>
        <a href="note-edit.php">Новая заметка</a>
        <div id="header-user-menu"><a href="javascript: openMenu(this);">{$user->name} ({$user->id})</a>
          <ul class="user-menu">
          <li><a href="profile.php">Профиль</a></li>
          <li><a href="settings.php">Настройки</a></li>
          <li><a href="logout.php">Выйти</a></li>
          </ul>
        </div>
CODE;
}

$page_header .= <<<CODE
        <a href="help.php">Справка</a>
      </nav>
    </header>

    <script>
      let menuOpened = false;
      let headerUserMenu = document.getElementById('header-user-menu');
      let headerUserMenuOpener = headerUserMenu.querySelector('a');
      let headerUserMenuContent = headerUserMenu.querySelector('ul');
      function openMenu(menu) {

        if (!menuOpened) {
          headerUserMenuContent.style.height = "94px";
          menuOpened = true;
        } else {
          headerUserMenuContent.style.height = "0px";
          menuOpened = false;
        }
      }


    </script>
CODE;
