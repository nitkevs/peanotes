<?php

/*
*
* /help.php
*
* Страница справочной информации.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$page_content = nl2br(file_get_contents("README.txt"));

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";

if (!session_id()) session_start();

@$user = new User();
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/templates/header.php";
$favicon = "/images/icons/favicon.ico";
$favicon = "/images/icons/favicon.ico";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $favicon ?>">
    <title>Справка</title>
  </head>
  <body>
<?= $page_header ?>
  <?php  ?>
  <main>
  <p>
  <a href="./">На главную</a>
  </p>
  <?= $page_content ?>
  <p>
  <a href="./">На главную</a>
  </p>
  </main>
  </body>
</html>
