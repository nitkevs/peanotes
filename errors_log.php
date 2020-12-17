<?php

$title = "Лог ошибок операций с БД";

$log = file_get_contents("db_errors.txt");

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <main>
    <h1><?= $title ?>:</h1>
    <?php
    if ($log) {
      echo "<pre>\n{$log}\n</pre>";
    } else {
      echo "Нет сообщений об ошибках.";
    }

    ?>
    </main>
  </body>

</html>
