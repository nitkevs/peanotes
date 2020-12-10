<?php

  /*
   *  Параметры доступа к БД,
   *  создание подключения,
   *  установка кодировки передачи данных
   *
  */

  $db_host = 'localhost';
  $db_user = 'admin';
  $db_password = 'cdtnbr';
  $db_name = 'notes';

  $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

  mysqli_query($db_connection, "SET NAMES 'utf8'");
