<?php

  /*  Параметры БД, создание подключения  */

  $db_host = 'localhost';
  $db_user = 'admin';
  $db_password = 'cdtnbr';
  $db_name = 'notes';

  $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

  mysqli_query($db_connection, "SET NAMES 'utf8'");

  /*  Работа с таблицей  */

  // Создаём таблицу notes, если она ещё не существует
  $query = "CREATE TABLE IF NOT EXISTS notes (id int(6) primary key auto_increment, note_title varchar(128), note_content longtext, note_creation_timestamp varchar(16))";
  $result = mysqli_query($db_connection, $query);

  /*  Обработка входных данных  */

  // Если страница загружена из note-edit.php методом POST,
  // записать данные из полей ввода в переменные.
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $note_title = $_POST['note-title'];
    $note_content = $_POST['note-content'];
    $note_creation_timestamp = $_POST['note-creation-timestamp'];
    $note_is_canceled = $_POST['cancel'];
    $to_delete_note = (bool)$_POST['delete-note'];
    $edit_note = $_POST['edit-note'];

  //  Если заголовк пуст - взять первые 70 символов контента
    if ($note_title == '') {
      $note_title = mb_substr($note_content, 0, 70)."...";
    }

  //  Удаляем переводы строк в заголовке заметки
    $note_title = str_replace(array("\r\n", "\r", "\n"), ' ', $note_title);

  //  Преобразуем специальные символы
    $note_content = htmlspecialchars($note_content, ENT_QUOTES);
    $note_title = htmlspecialchars($note_title, ENT_QUOTES);

  //  Записываем в $note_exists заметки, note_creation_timestamp которых
  //  совпадает с note_creation_timesеamp текущей заметки
    $query = "SELECT * FROM notes WHERE note_creation_timestamp='$note_creation_timestamp'";
    $result = mysqli_query($db_connection, $query);
    for ($note_exists = []; $row = mysqli_fetch_assoc($result); $note_exists[] = $row);

  //  Если заметка ещё не существует, записать её в БД
    if (!$note_exists && !$note_is_canceled && !$to_delete_note) {
    $query = "INSERT INTO notes SET note_title='{$note_title}', note_content='{$note_content}', note_creation_timestamp='$note_creation_timestamp'";
    $result = mysqli_query($db_connection, $query);
    }

  //  Удаляем заметку, если пользователь запросил это действие
    if ($to_delete_note) {
      $query = "DELETE FROM notes WHERE `note_creation_timestamp` = {$note_creation_timestamp}";
      mysqli_query($db_connection, $query) or die('Ошибка удаления');
    }

  // Если переданная старнице заметка редактировалась, перезаписать её заголовок и текст в БД.
    if ($edit_note) {
      $query = "UPDATE `notes` SET `note_title` = '{$note_title}', `note_content` = '{$note_content}' WHERE `note_creation_timestamp` = {$note_creation_timestamp}";
      mysqli_query($db_connection, $query) or die('Ошибка записи'.mysqli_error($db_connection).$note_title."<br>".$note_content);
    }
  }

  // Читаем БД, извлекам все заметки и записываем в массив $notes
  $query = "SELECT * FROM notes WHERE id > 0";
  $result = mysqli_query($db_connection, $query);
  for ($notes = []; $row = mysqli_fetch_assoc($result); $notes[] = $row);
  // переворачиваем массив, чтобы заметки отображались в порядке убывания даты создания
  $notes = array_reverse($notes);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Заметки</title>
    <link rel="stylesheet" type="text/css" href="style.css">

    <script>
<?php
  // записываем заголовки, id и текст заметок в три строки с форматом массива
  foreach ($notes as $note) {
    $notesId .= "'".$note['id']."', ";
    // функция str_replace(array("\r\n", "\r", "\n"), '<br>', $string) заменяет
    // все переводы строк тегом <br>. Без этого не получится корректных массивов js.
    $notesTitle .= "'".str_replace(array("\r\n", "\r", "\n"), '<br>', $note['note_title'])."', ";
    $notesContent .= "'".str_replace(array("\r\n", "\r", "\n"), '<br>', $note['note_content'])."', ";
  }

// передаём строки в качестве значений для массивов javascript.
echo "let notesId = [{$notesId}];\n";
echo "let notesTitle = [{$notesTitle}];\n";
echo "let notesContent = [{$notesContent}];\n";
?>
    </script>
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
              echo "Здесь ещё ничего нет.<br><a href=\"note-edit.php\">Добавить заметку</a>";
            } else {
?>
          <ul>
<?php
  $index = 0;
  foreach ($notes as $note) {
    // ограничить длинну тизера заметки до 200 символов
    $note_teaser = mb_substr($note['note_content'], 0, 200);
    // заменить переводы строки тегами <br>
    $note_teaser = nl2br($note_teaser);
    echo <<<"NOTES"
            <li onclick="noteOutput(this, {$index})" onmouseover="showEditLinks(this);" onmouseout="hideEditLinks(this);">
              <div class="note-edit-buttons">
                <form action="" method="post">
                  <button title="Редактировать" name="edit-note" value="1" formaction="./note-edit.php">
                    <img src="icons/edit.png" alt="Редактировать">
                  </button>
                  <button title="Удалить" name="delete-note" value="1" formaction="">
                    <img src="icons/delete.png" alt="Удалить">
                  </button>
                  <input type="hidden" name="note-creation-timestamp" value="{$note['note_creation_timestamp']}">
                </form>
              </div>
              <p class="note-title" title="{$note['note_title']}">{$note['note_title']}</p>
              <p class="note-teaser">{$note_teaser}</p>
            </li>
NOTES;
    $index++;
            }
          }

?>
          </ul>
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
    function noteOutput(activeNote, index) {
      //  в переменную output записываем блок note-content, куда будет выведена заметка
      let output = document.getElementById('note-content');
      // Если в переменной oldActive есть какой-то блок,
      if (oldActive) {
        // удалить его из класса active
        oldActive.classList.remove('active');
        }
      // а выбранному блоку присвоить класс active
      activeNote.classList.add('active');
      // записать выбранный активный блок в переменную oldActive
      oldActive = activeNote;
      output.innerHTML = "<h2>" + notesTitle[index] + "</h2><p>" + notesContent[index] + "</p>";
    }

    function showEditLinks(note) {
      let noteEditButtons = note.children[0];
      noteEditButtons.style.display = "block";
    }

    function hideEditLinks(note) {
      let noteEditButtons = note.children[0];
      noteEditButtons.style.display = "none";
    }

  </script>
</html>
