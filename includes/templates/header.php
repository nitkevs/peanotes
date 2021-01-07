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

$page_header = <<<HTML
    <header>
      <div id="logo"><a href="./">Peanotes</div>
      <nav id="header-navigation">
HTML;

if (empty($user->id)) {
$page_header .= <<<HTML
        <a href="logout.php">Войти</a>
        <a href="registration-form.php">Регистрация</a>
HTML;
}

// if ($user->id === 1){}

if ($user->id >= 1) {
$page_header .= <<<HTML
        <a href="/">Мои заметки</a>
        <a href="note-edit.php">Новая заметка</a>
        <div id="header-user-menu"><a href="javascript: openMenu(this);">{$user->name}</a>
          <ul class="user-menu">
          <li><a href="view_profile.php">Профиль</a></li>
          <li><a href="user_settings.php">Настройки</a></li>
          <li><a href="logout.php">Выйти</a></li>
          </ul>
        </div>
HTML;
}

$page_header .= <<<HTML
        <a href="help.php">Справка</a>
      </nav>
    </header>
HTML;
