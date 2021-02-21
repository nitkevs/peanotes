<?php

/*
* /admin/users.php
*
* Управление аккаунтами пользователй сервиса.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB_tables.php';
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";

if (!session_id()) session_start();

if (!isset($_SESSION['user_id'])) {
  require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/set_session.php";
}

$user = new User();

// Права доступа к странице
$groups = ["", "Администратор", "Модератор", "Пользователь", "Пользователь"];
if ($user->group > 1) {
  echo "Пользователи группы \"{$groups[$user->group]}\" не имеют доступа к этой странице. <a href=\"/\">Перейти на главную</a>.";
  exit;
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/templates/header.php";
$favicon = "/images/icons/favicon.ico";
$title = 'Пользователи';


// Достём из базы данных список зарегистрированных аккаунтов
$query = "SELECT `id`, `login`, `name`, `group`, `email`, `ban_severity`, `ban_expires` FROM `pn_users`";
$result = mysqli_query($db_connection, $query);

for ($user_list = []; $row = mysqli_fetch_assoc($result); $user_list[] = $row);

$query = "SELECT owner_id FROM `pn_notes`";
$result = mysqli_query($db_connection, $query);

for ($note_count = []; $row = mysqli_fetch_assoc($result);) {
  if (isset($note_count[$row['owner_id']])) {
    $note_count[$row['owner_id']]++;
  } else {
    $note_count[$row['owner_id']] = 1;
  }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="/style.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
  </head>
  <body>
  <?= $page_header ?>
    <main class="">
    <div class="">
      <h1 class="centered"><?= $title ?></h1>
      <table id="user-list">
        <thead>
          <tr>
            <th scope="col">id</th>
            <th scope="col">Имя</th>
            <th scope="col">Ник</th>
            <th scope="col">Группа</th>
            <th scope="col">E-mail</th>
            <th scope="col">Заметок</th>
            <th scope="col">Бан</th>
            <th scope="col">Действия</th>
          </tr>
        </thead>
        <tbody>
<?php
foreach ($user_list as $account) {
  $account['email'] = $account['email'] ?: "Не указан";
  $note_count[$account['id']] = $note_count[$account['id']] ?? 0;
  $ban = $account['ban_expires'] ? "{$account['ban_severity']}: {$account['ban_expires']}" : "Нет";

  if ($account['ban_expires']  > 0) {
    setlocale(LC_ALL, 'ru_RU.UTF-8');
    $ban_expires = strftime("%A, %d %B %Y года, %H:%M:%S", $account['ban_expires']);
  } else {
    $ban_expires = "";
  }

  if ($account['ban_expires'] > 0 and time() > $account['ban_expires']) {
    $ban_color = "green";
  } else{
    $ban_color = "inherit";
  }

  echo <<<HTML
          <tr>
            <td>{$account['id']}</td>
            <td>{$account['login']}</td>
            <td>{$account['name']}</td>
            <td>{$groups[$account['group']]}</td>
            <td>{$account['email']}</td>
            <td>{$note_count[$account['id']]}</td>
            <td><span title="{$ban_expires}" style="color: {$ban_color}">{$ban}</span></td>
            <td></td>
          </tr>
HTML;
}
?>
        </tbody>
      </table>
    </div>
    </main>
  </body>
  <script src="/js/header.js"></script>
</html>