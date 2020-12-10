<?php

  /*
   * /includes/DB_connection.php предоставляет подключение
   * к базе данных в переменной $db_connection.
   *
   *
  */

  require_once './includes/DB_connection.php';
  require_once './includes/DB_tables.php';
  require_once './includes/classes/Note.php';

  $note = new Note();

  /*  Обработка входных данных  */

  // Если страница загружена методом POST,
  // записать данные из полей ввода в переменные.
  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $note->id = $_POST['note-id'];
    $note->title = $_POST['note-title'];
    $note->content = $_POST['note-content'];
    $note->is_edited = $_POST['edit-note'];
    $note->for_delition = (bool)$_POST['delete-note'];

   //  Если заголовк пуст - взять первые 70 символов контента
    if ($note->title == '') {
      $note->title = $note->generate_title();
    }

    //  Удаляем переводы строк в заголовке заметки и преобразуем специальные символы
    $note->format();

    if ($note->is_edited) {

      $query = "UPDATE `pn_notes` SET `note_title` = '{$note->title}', `note_content` = '{$note->content}' WHERE `id` = {$note->id}";
      mysqli_query($db_connection, $query) or die('Ошибка записи'.mysqli_error($db_connection).$note->title."<br>".$note->content);

    } else if ($note->for_delition) {

  //  Удаляем заметку, если пользователь запросил это действие
      $query = "DELETE FROM `pn_notes` WHERE `id` = {$note->id}";
      mysqli_query($db_connection, $query) or die('Ошибка удаления');

    } else {

      $note->timestamp = time();

      $query = "INSERT INTO `pn_notes` SET `note_title` = '{$note->title}', `note_content` = '{$note->content}', `note_creation_timestamp` = '{$note->timestamp}'";
      $result = mysqli_query($db_connection, $query);

    }

}

header("Location: ./");
    /*  Конец обработки входных данных  */
