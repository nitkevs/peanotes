 <?php

  /* Создаём таблицу pn_notes, если она ещё не существует */

 $query = "CREATE TABLE IF NOT EXISTS `pn_notes` (id int(6) PRIMARY KEY AUTO_INCREMENT, `note_title` varchar(255), `note_content` longtext, `note_creation_timestamp` varchar(16) NOT NULL UNIQUE)";
  $result = mysqli_query($db_connection, $query);
