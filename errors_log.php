<?php

/*
* /errors_log.php
*
* Страница просмотра лога ошибок запросов к БД
* Сам лог находится в файле /db_errors.log
* (генерируется автоматически при возникновении ошибки).
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";

if (!session_id()) session_start();

if (!isset($_SESSION['user_id'])) {
  require_once './includes/set_session.php';
}

$user = new User();
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/templates/header.php";
$favicon = "/images/icons/favicon.ico";
$title = "Лог ошибок операций с БД";
$log = @file_get_contents("db_errors.log");

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $favicon ?>">
  </head>
  <body>
<?= $page_header ?>
    <main>
    <h1><?= $title ?>:</h1>
    <p>
    <?php
    if (!empty($log)) {
      echo "<pre>\n{$log}\n</pre>";
    } else {
      echo "Нет сообщений об ошибках.";
    }
    ?>
    </p>
    </main>
  </body>
  <script src="js/header.js"></script>
</html>
