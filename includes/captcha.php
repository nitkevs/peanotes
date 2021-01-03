<?php

/*
*
* /includes/captcha.php
*
* Скрипт осуществляет возможность добавления капчи на страницу.
*
*/

if (!session_id()) {
  session_start();
}

include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/classes/Captcha.php";

$captcha = new Captcha();
$captcha->html_content = $captcha->get_content();
$_SESSION['captcha-answer'] = $captcha->answer;
