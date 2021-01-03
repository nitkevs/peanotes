<?php

/*
*
* /includes/classes/Note.php
*
* Класс Note предоставляет методы для работы с заметками
* и данные заметок
*
* $note_title заголовок заметки
* $note_content Текст заметки
* $note_creation_timestamp время создания заметки
* $to_delete_note надо ли удалить заметку
* $edit_note редактируется ли заметка или создаётся
*
*  Методы:
*
* generate_title генерирует заголовок из контента заметки
* format форматирует текст заметки и заголовка
*
*/


class Note {
  // Данные заметки
  public $id;
  public $title;
  public $content;
  public $timestamp;
  public $last_modified;
  // Свойства заметки
  public $is_edited;
  public $for_delition;

  /* Функция генерирует заголовок на основании содержимого заметки.
     По умолчанию берутся первые 57 символов и добавляется "...".
     Если среди первых 60 символов встерчается перевод строки CR или LF,
     заголовок берётся от начала заметки (после удаления начальных
     пробелов и переводов строк), до этой позиции. Троеточие
     в этом слуае не добавляется. */

  public function generate_title() {
    $this->title = trim($this->content);

    $first_lf_pos = mb_strpos($this->title, "\n") ?: mb_strlen($this->title);
    $first_cr_pos = mb_strpos($this->title, "\r") ?: mb_strlen($this->title);
    $end_pos = min($first_lf_pos, $first_cr_pos);

    if ($end_pos > 60) {
      $this->title = mb_substr($this->title, 0, 57)."...";
    } else {
      $this->title = mb_substr($this->title, 0, $end_pos);
    }
    return $this->title;
  }

  public function format() {
    $this->title = trim($this->title);
    $this->title = htmlspecialchars($this->title, ENT_QUOTES);
    $this->content = htmlspecialchars($this->content, ENT_QUOTES);
  }
}
