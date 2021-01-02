<?php

/*
*
* /write-note.php
*
* Записывает изменения в заметках в базу данных.
*
*/

require_once './includes/DB_connection.php';
require_once './includes/DB_tables.php';
require_once './includes/classes/Note.php';

session_start();

$note = new Note();

// Функция записывает данные об ошибке в сессию PHP.
// Затем их использует главная страница для показа этих ошибок.
function send_error_message($mess, $err_mess, $query) {
  $_SESSION['error_message'] = $err_mess;
  $_SESSION['my_err_mess'] = $mess;
  $_SESSION['query'] = $query;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $note->id = $_POST['note-id'];
  $note->title = $_POST['note-title'];
  $note->content = $_POST['note-content'];
  $note->is_edited = $_POST['edit-note'];
  $note->for_delition = (bool)$_POST['delete-note'];

  $current_timestamp = time();

  //  Если заголовк пуст - сгенерировать его из контента.
  if ($note->title == '') {
    $note->title = $note->generate_title();
  }

  //  Удаляем переводы строк в заголовке заметки и преобразуем специальные символы.
  $note->format();

  if ($note->is_edited) {

    $note->last_modified = $current_timestamp;

    $query = "UPDATE `pn_notes` SET `title` = '{$note->title}', `content` = '{$note->content}', `last_modified` = '{$note->last_modified}' WHERE `id` = {$note->id}";
    mysqli_query($db_connection, $query) or send_error_message('Ошибка записи', mysqli_error($db_connection), $query);

  } else if ($note->for_delition) { //  Удаляем заметку, если пользователь запросил это действие

    $query = "DELETE FROM `pn_notes` WHERE `id` = {$note->id}";
    mysqli_query($db_connection, $query) or send_error_message('Ошибка удаления', mysqli_error($db_connection), $query);

  } else { // Сработает, если создаётся новая заметка
    $note->timestamp = $current_timestamp;
    $query = "INSERT INTO `pn_notes` SET `title` = '{$note->title}', `content` = '{$note->content}', `timestamp` = '{$note->timestamp}'";
    $result = mysqli_query($db_connection, $query) or send_error_message('Ошибка записи', mysqli_error($db_connection), $query);
  }

}

header("Location: ./");
exit;
