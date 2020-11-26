<?php

  $db_host = 'localhost';
  $db_user = 'admin';
  $db_password = 'cdtnbr';
  $db_name = 'notes';

  $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("Ошибка соединения с базой данных" . mysqli_error($db_connection));

  mysqli_query($db_connection, "SET NAMES 'utf8'");

  // Создаём таблицу notes, если она ещё не существует
  $query = "CREATE TABLE IF NOT EXISTS notes (id int(6) primary key auto_increment, note_title varchar(100), note_content longtext, note_titles varchar(100))";
  $result = mysqli_query($db_connection, $query) or die ("Ошибка " . mysqli_error($db_connection));

  // Если страница загружена из note-edit.php методом POST,
  // записать данные из полей ввода в переменные.
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $note_title = $_POST['note-title'];
    $note_content = $_POST['note-content'];

  //  Если заголовк пуст - взять первые 70 символов контента
    if ($note_title == '') {
      $note_title = mb_substr($note_content, 0, 70)."...";
    }

  // Преобразуем специальные символы
    $note_content = htmlspecialchars($note_content, ENT_QUOTES);
    $note_title = htmlspecialchars($note_title, ENT_QUOTES);

  //  Записать результат в базу данных
    $query = "INSERT INTO notes SET note_title='{$note_title}', note_content='{$note_content}'";
    $result = mysqli_query($db_connection, $query) or die ("Ошибка записи в базу данных" . mysqli_error($db_connection));
  }

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Заметки</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <header>
      <h1>Заметки</h1>
      <nav id="header-navigation">
        <a href="">Добавить заметку</a>
        <a href="">Справка</a>
      </nav>
    </header>
    <main>
      <div id="notes">
        <div id="notes-list">
          Здесь ещё ничего ещё нет.<br><a href="note-edit.php">Добавить заметку</a>
        </div>
        <div id="note-content">
          Содержимое
        </div>
      </div>
    </main>
  </body>
</html>
