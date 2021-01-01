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
