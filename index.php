<?php

  $db_host = 'localhost';
  $db_user = 'admin';
  $db_password = 'cdtnbr';
  $db_name = 'notes';

  $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

  mysqli_query($db_connection, "SET NAMES 'utf8'");

  // Создаём таблицу notes, если она ещё не существует
  $query = "CREATE TABLE IF NOT EXISTS notes (id int(6) primary key auto_increment, note_title varchar(128), note_content longtext, note_creation_timesamp varchar(16))";
  $result = mysqli_query($db_connection, $query);

  // Если страница загружена из note-edit.php методом POST,
  // записать данные из полей ввода в переменные.
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $note_title = $_POST['note-title'];
    $note_content = $_POST['note-content'];
    $note_creation_timesamp = $_POST['note-creation-timestamp'];

  //  Если заголовк пуст - взять первые 70 символов контента
    if ($note_title == '') {
      $note_title = mb_substr($note_content, 0, 70)."...";
    }

  // Преобразуем специальные символы
    $note_content = htmlspecialchars($note_content, ENT_QUOTES);
    $note_title = htmlspecialchars($note_title, ENT_QUOTES);

  //  Записать результат в базу данных, если заметка ещё не существует
    $query = "SELECT * FROM notes WHERE note_creation_timesamp='$note_creation_timesamp'";
    $result = mysqli_query($db_connection, $query);

  // Если в БД существует хоть одна заметка с текущим значением timestamp
  // (используемое тут как идентификатор для определения уникальности),
  // $note_is_unique приравнять к false, что означает её неуникальность
  // и как следствие отказаться от вставки её в БД.
    $note_is_unique = true;
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) {
      if ($row['note_creation_timesamp'] == $note_creation_timesamp) {
          $note_is_unique = false;
        }
      }

    if ($note_is_unique) {
      $query = "INSERT INTO notes SET note_title='{$note_title}', note_content='{$note_content}', note_creation_timesamp='$note_creation_timesamp'";
      $result = mysqli_query($db_connection, $query);
    }
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
      <h1><a href="./">Заметки</h1>
      <nav id="header-navigation">
        <a href="note-edit.php">Добавить заметку</a>
        <a href="help.php">Справка</a>
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
