<?php

/*
*
* /note-edit.php
*
* Форма создания и редактирования заметок.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once './includes/DB_connection.php';
require_once './includes/classes/Note.php';
$note = new Note();
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/User.php";

if (!session_id()) session_start();

if (!isset($_SESSION['user_id'])) {
  require_once './includes/set_session.php';
}

$user = new User();
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/templates/header.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $note->is_edited = $_POST['edit-note'];
  $note->id = $_POST['note-id'];
  $note->title = htmlspecialchars($_POST['note-title']);
  $note->content = htmlspecialchars($_POST['note-content']);
}

$favicon = "/images/icons/favicon.ico";
$title = ($note->is_edited === "1") ? "Редактировать заметку «{$note->title}»" : "Добавление новой заметки";

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
  <script src="js/header.js"></script>
  <script src="js/note-edit.js"></script>
</html>
