<?php

  /*
   * /includes/DB_connection.php предоставляет подключение
   * к базе данных в переменной $db_connection.
   *
  */

  require_once './includes/DB_connection.php';

  session_start();

  // Читаем БД, извлекам все заметки и записываем в массив $notes
  $query = "SELECT * FROM `pn_notes` ORDER BY `id` DESC";
  $result = mysqli_query($db_connection, $query);
  for ($notes = []; $row = mysqli_fetch_assoc($result); $notes[] = $row);

function show_errors() {

  echo "<div id=\"db-errors\">{$_SESSION['my_err_mess']}<br>{$_SESSION['error_message']}<br><a href=\"errors_log.php\">Просмотреть лог ошибок</a></div>";

  /* Записать ошибки в лог файл */

  $file_content = file_get_contents("db_errors.txt");

  if ($file_content) {
    $message_separator = "\n\n----------------------------------\n\n";
  }

  $error_message = date("Y-m-d H:i:s", time())."\n\n{$_SESSION['my_err_mess']}\n\n{$_SESSION['error_message']}\n\n{$_SESSION['query']}".$message_separator.$file_content;

  $file = fopen("db_errors.txt", 'w');

  fwrite($file, $error_message);

  fclose($file);

  /* Удалить значения переменных сессии, чтобы после перезагрузки страницы сообщение не выводилось */

  unset($_SESSION['error_message']);
  unset($_SESSION['my_err_mess']);
  unset($_SESSION['query']);

}

  $new_errors = ($_SESSION['error_message'] || $_SESSION['my_err_mess'] || $_SESSION['query']);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Заметки</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <?php if ($new_errors) show_errors(); ?>
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
              // Если заметок в БД нет, вывести надпись.
              echo "Здесь ещё ничего нет.<br><a href=\"note-edit.php\">Добавить заметку</a>";
            } else {
?>
          <ul>
<?php
  foreach ($notes as $a_note) {
    // Добавить тег <br> к переводам строк, чтобы они были отображены на странице.
    $a_note['html_content'] = nl2br($a_note['content']);
    $a_note['date'] = date("Y-m-d H:i:s", $a_note['timestamp']);
    if (isset($a_note['last_modified'])) {
      $a_note['last_modified'] = date("Y-m-d H:i:s", $a_note['last_modified']);
    }

    // Выводим все тизеры на экран
    echo <<<"NOTES"
        <li onclick="showNoteContent(this)" onmouseover="showEditLinks(this);" onmouseout="hideEditLinks(this);" data-date="{$a_note['date']}" data-last-modified="{$a_note['last_modified']}">
          <div class="note-edit-buttons">
            <form action="./note-edit.php" method="post">
              <button title="Редактировать" name="edit-note" value="1" formaction="./note-edit.php" onclick="event.stopPropagation();">
                <img src="images/icons/edit.png" alt="Редактировать">
              </button>
              <button title="Удалить" name="delete-note" value="1" formaction="./write-note.php" onclick="event.stopPropagation();">
                <img src="images/icons/delete.png" alt="Удалить">
              </button>
              <input type="hidden" name="note-id" value="{$a_note['id']}">
              <input type="hidden" name="note-title" value="{$a_note['title']}">
              <input type="hidden" name="note-content" value="{$a_note['content']}">
            </form>
          </div>
          <p class="note-title" title="{$a_note['title']}">{$a_note['title']}</p>
          <p class="note-teaser">{$a_note['html_content']}</p>
        </li>
NOTES;
            }
          echo "</ul>";
          }

?>
        </div>
        <div id="note-content">
        <?php

          if ($notes) {
            echo "Кликните любую заметку, чтобы увидеть её содержимое.";
          } else {
            echo "Добавьте заметки, чтобы просматривать их в этой области.";
          }

        ?>
        </div>
      </div>
    </main>
  </body>
  <script>
    let oldActive;

    // функция выводит выбранную заметку на экран
    function showNoteContent(activeNote) {
      //  в переменную output записываем блок note-content, куда будет выведена заметка
      let output = document.getElementById('note-content');
      let noteTitle = activeNote.querySelector('.note-title').innerHTML;
      let noteContent = activeNote.querySelector('.note-teaser').innerHTML;
      let noteDate = "Создано:&nbsp;" + activeNote.dataset.date;
      let noteLastModified = "";
      if (activeNote.dataset.lastModified) {
       noteLastModified = "Последнее изменение:&nbsp;" + activeNote.dataset.lastModified;
       }
      console.log (noteDate + " " + noteLastModified);
      // Если в переменной oldActive есть какой-то блок,
      if (oldActive) {
        // удалить его из класса active
        oldActive.classList.remove('active');
      }
      // а выбранному блоку присвоить класс active
      activeNote.classList.add('active');
      // записать выбранный активный блок в переменную oldActive
      oldActive = activeNote;

      output.innerHTML = "<h2>" + noteTitle + "</h2>\n<div id=\"note-dates\">\n<div id=\"created\">" + noteDate + "</div>\n<div id=\"last-modified\">" + noteLastModified + "</div>\n</div><div>" + noteContent + "</div>";
    }

    function showEditLinks(note) {
      let noteEditButtons = note.querySelector('.note-edit-buttons');
      noteEditButtons.style.display = "block";
    }

    function hideEditLinks(note) {
      let noteEditButtons = note.querySelector('.note-edit-buttons');
      noteEditButtons.style.display = "none";
    }

  </script>
</html>
