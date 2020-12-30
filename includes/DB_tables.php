 <?php

  /* Создаём таблицу pn_notes, если она ещё не существует */

$query = "CREATE TABLE IF NOT EXISTS `pn_notes` (`id` int(8) PRIMARY KEY AUTO_INCREMENT, `title` text, `content` text, `timestamp` varchar(10) NOT NULL UNIQUE, `last_modified` varchar(10))";

$result = mysqli_query($db_connection, $query);


$query = "CREATE TABLE IF NOT EXISTS `pn_users` (`id` int(8) PRIMARY KEY AUTO_INCREMENT, `name` varchar(32), `login` varchar(32), `pass` char(32), `salt` char(32), `group` tinyint(1), `ban_expires` varchar(10), `ban_severity` tinyint(1))";

$result = mysqli_query($db_connection, $query) or die(mysqli_error($db_connection)."<p>".$query);


$query = "CREATE TABLE IF NOT EXISTS `pn_sessions` (`user_id` int(8), `hash` char(32) PRIMARY KEY, `user_agent` char(32), `expires` varchar(10))" or die(mysqli_error($db_connection)."<p>".$query);

$result = mysqli_query($db_connection, $query);
