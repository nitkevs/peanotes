<?php

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $note_title = $_POST['note-title'];
    $note_content = $_POST['note-content'];
    $note_id = $_POST['note-id'];
  }

  $title = ($note_title !== NULL) ? "Редактировать заметку «{$note_title}»" : "Добавление новой заметки";

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
    <form action="./" method="post" id="note-edit-form" onsubmit="return formSubmit(); ">
      <label for="note-title">Заголовок:</label><br>
      <input type="text" size="45" maxlength="100" id="note-title" name="note-title" value="<?= $note_title ?>"><br>
      <label for="note-content">Текст заметки:</label><br>
      <textarea cols="60" rows="15" id="note-content" name="note-content"><?= $note_content ?></textarea><br>
      <input type="hidden" id="note-creation-timestamp" name="note-creation-timestamp" value="">
      <button type="submit">Сохранить</button> <button form="cansel-form">Отмена</button>
    </form>
    <form action="./" method="post" id="cansel-form">
      <input type="hidden" name="cancel" value="true">
    </form>
    </main>
  </body>
  <script>
    let noteTitle = document.getElementById('note-title');
    let noteContent = document.getElementById('note-content');
    let noteCreationTimestamp = document.getElementById('note-creation-timestamp');
    function formSubmit() {
      if (!(noteTitle.value || noteContent.value)) {
        alert ('Пустая заметка не может быть сохранена.\nЗаполните хотя бы одно поле!');
        return false;
      } else {
        noteCreationTimestamp.value = Date.now();
      }
    }
  </script>
</html>
