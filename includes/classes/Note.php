<?php

  /*
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


  public function generate_title() {
    //  Если заголовок пуст - взять первые 57 символов контента
    $this->title = trim($this->content);
    $this->title = mb_substr($this->title, 0, 37)."...";
    return $this->title;
  }

  public function format() {
    //  Удаляем переводы строк в заголовке заметки
    $this->title = str_replace(array("\r\n", "\r", "\n"), ' ', $this->title);

  //  Преобразуем специальные символы
    $this->content = htmlspecialchars($this->content, ENT_QUOTES);
    $this->title = htmlspecialchars($this->title, ENT_QUOTES);
  }
}
