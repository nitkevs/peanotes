<?php

/*
*
* /includes/global_functions.php
*
* Функции, которые используются в нескольких скриптах
*
*/

function get_hash($arg1, $arg2, $arg3) {

  $hash_1 = md5($arg1);
  $hash_2 = sha1($arg2);
  $hash_3 = md5($arg3);

  $hash_4 = md5(substr($hash_1, 0, 16).substr($hash_3, 16, 32).substr($hash_2, 20, 40));
  $hash_5 = md5(substr($hash_2, 0, 20).substr($hash_1, 16, 32).substr($hash_3, 0, 16));

  return substr(sha1($hash_5.$hash_4.$arg2), 8, 40);
}

function set_session($user_id) {

  global $user_agent_hash;
  global $db_connection;

  if (!session_id())  session_start();

  $random_hash = get_hash(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX));
  $coockie_hash = get_hash($random_hash, $user_id, $user_agent_hash);
  $session_expires = time() + (60*60*24*30);
  $new_session_hash = get_hash($coockie_hash, $user_agent_hash, HASH_KEY);

  setcookie('session', $coockie_hash, $session_expires, "/"); // Создать куку на 30 дней

  $query = "INSERT INTO `pn_sessions` SET `user_id` = '{$user_id}', `hash` = '{$new_session_hash}', `user_agent` = '{$user_agent_hash}', `expires` = '{$session_expires}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query); // Создать сессию
}

function remove_session($session_hash) {
  global $db_connection;

  setcookie('session', '', time());

  $query = "DELETE FROM `pn_sessions` WHERE `hash` = '{$session_hash}'";
  $result = mysqli_query($db_connection, $query) or die (mysqli_error($db_connection).$query);
  session_destroy();
  session_unset();
}

// Функция проверяет соответствие введённых пользователем данных,
// регулярному выражению. Возвращает сами эти данные, или null,
// в случае не соответствия.
function validate_input_data($pattern, $data) {
  preg_match($pattern, $data, $match);
  return $match[0]?? null;
}