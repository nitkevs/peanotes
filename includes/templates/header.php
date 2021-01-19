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
        <a href="/login.php">Войти</a>
        <a href="/registration-form.php">Регистрация</a>
HTML;
}

if ($user->group >= 1) {
$page_header .= <<<HTML
        <a href="/">Мои заметки</a>
        <a href="/note-edit.php">Новая заметка</a>
        <div id="header-user-menu"><a href="javascript: menu.toggle(event);">{$user->name}<img src="/images/dropdown.png"></a>
          <ul id="user-menu">
          <li><a href="/view_profile.php">Профиль</a></li>
          <li><a href="/user_settings.php">Настройки</a></li>
          <li><a href="/scripts/logout.php">Выйти</a></li>
          </ul>
        </div>
HTML;
}

$page_header .= <<<HTML
        <a href="help.php">Справка</a>
      </nav>
    </header>
HTML;
