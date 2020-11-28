<?php

  $db_host = 'localhost';
  $db_user = 'admin';
  $db_password = 'cdtnbr';
  $db_name = 'notes';

  $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

  mysqli_query($db_connection, "SET NAMES 'utf8'");

  // Создаём таблицу notes, если она ещё не существует
  $query = "CREATE TABLE IF NOT EXISTS notes (id int(6) primary key auto_increment, note_title varchar(128), note_content longtext, note_creation_timestamp varchar(16))";
  $result = mysqli_query($db_connection, $query);

  // Если страница загружена из note-edit.php методом POST,
  // записать данные из полей ввода в переменные.
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $note_title = $_POST['note-title'];
    $note_content = $_POST['note-content'];
    $note_creation_timestamp = $_POST['note-creation-timestamp'];
    $note_is_canceled = $_POST['cancel'];

    //  Если заголовк пуст - взять первые 70 символов контента
    if ($note_title == '') {
      $note_title = mb_substr($note_content, 0, 70)."...";
    }

  // Преобразуем специальные символы
    $note_content = htmlspecialchars($note_content, ENT_QUOTES);
    $note_title = htmlspecialchars($note_title, ENT_QUOTES);

  //  Записываем в $note_exists заметки, note_creation_timestamp которых
  //  совпадает с note_creation_timesеamp текущей заметки
    $query = "SELECT * FROM notes WHERE note_creation_timestamp='$note_creation_timestamp'";
    $result = mysqli_query($db_connection, $query);
    for ($note_exists = []; $row = mysqli_fetch_assoc($result); $note_exists[] = $row);

  //  Если заметка ещё не существует, записать её в БД
    if (!$note_exists & !$note_is_canceled) {


    $query = "INSERT INTO notes SET note_title='{$note_title}', note_content='{$note_content}', note_creation_timestamp='$note_creation_timestamp'";
    $result = mysqli_query($db_connection, $query);
    }
  }

    $query = "SELECT * FROM notes WHERE id > 0";
    $result = mysqli_query($db_connection, $query);
    for ($notes = []; $row = mysqli_fetch_assoc($result); $notes[] = $row);

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
<?php
            if (!$notes) {
              echo "Здесь ещё ничего ещё нет.<br><a href=\"note-edit.php\">Добавить заметку</a>";
            } else {
?>
          <ul>
<?php

  foreach ($notes as $note) {
    // ограничить длинну тизера заметки до 200 символов
    $note_teaser = mb_substr($note['note_content'], 0, 200);
    // заменить переводы строки тегами <br>
    // $note_teaser = str_replace(array("\r\n", "\r", "\n"), '<br>', $note_teaser);
    $note_teaser = nl2br($note_teaser);

    echo <<<"NOTES"
            <li>
              <p class="note-title" title="{$note['note_title']}">{$note['note_title']}</p>
              <p class="note-content">{$note_teaser}</p>
            </li>
NOTES;
            }
          }

?>
          </ul>
        </div>
        <div id="note-content">
          Содержимое
        </div>
      </div>
    </main>
  </body>
</html>
