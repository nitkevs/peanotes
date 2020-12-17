<?php

  /*
   * includes/DB_connection.php предоставляет подключение
   * к базе данных в переменной $db_connection.
   *
   * includes/classes/Note.php предоставляет класс Note,
   * для работы с заметками.
   *
  */

  require_once './includes/DB_connection.php';
  require_once './includes/classes/Note.php';

  $note = new Note();

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $note->is_edited = $_POST['edit-note'];
    $note->id = $_POST['note-id'];
    $note->title = $_POST['note-title'];
    $note->content = $_POST['note-content'];
  }

  $title = ($note->title !== NULL) ? "Редактировать заметку «{$note->title}»" : "Добавление новой заметки";
  $root_dir = "/php/peanotes";
  $favicon = "/images/icons/favicon.ico";


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="<?= $root_dir.$favicon ?>">
  </head>
  <body>
    <main>
    <h1><?= $title ?>:</h1>
    <form action="./write-note.php" method="post" id="note-edit-form" onsubmit="return formSubmit(); ">
      <label for="note-title">Заголовок:</label><br>
      <input type="text" size="45" maxlength="60" id="note-title" name="note-title" value="<?= $note->title ?>"><br>
      <label for="note-content">Текст заметки:</label><br>
      <textarea cols="60" rows="15" id="note-content" name="note-content"><?= $note->content ?></textarea><br>

      <?php if ($note->is_edited) {
              echo "<input type=\"hidden\" id=\"note-id\" name=\"note-id\" value=\"{$note->id}\">";
            }
      ?>

      <button type="submit" id="submit" <?php if ($note->is_edited) echo "name=\"edit-note\" value=\"{$note->is_edited}\""; ?>>Сохранить</button> <button form="cansel-form">Отмена</button>
    </form>
    <form action="./" method="post" id="cansel-form">
    </form>

    </main>
  </body>
  <script>
    let noteTitle = document.getElementById('note-title');
    let noteContent = document.getElementById('note-content');
    function formSubmit() {
      if (!(noteTitle.value || noteContent.value)) {
        alert ('Пустая заметка не может быть сохранена.\nЗаполните хотя бы одно поле!');
        return false;
      }
   }
  </script>
</html>
