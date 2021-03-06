<?php

/*
* /scripts/logout.php
*
* Скрипт реализует выход пользователя из аккаунта.
*
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!session_id()) {
  session_start();
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/DB_connection.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/key.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/global_functions.php";

$user_agent_hash = md5($_SERVER['HTTP_USER_AGENT']);
$session_hash = get_hash($_COOKIE['session'], $user_agent_hash, HASH_KEY);

remove_session($session_hash);

header ("Location: /login.php");
exit;
