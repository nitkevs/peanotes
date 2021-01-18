<?php

/*
*
* /includes/classes/User.php
*
* Класс User предоставляет аттрибуты
* для хранения данных о пользователе.
*
*/

class User {
  public $id;
  public $name;
  public $login;
  public $pass;
  public $salt;
  public $group;
  public $email;
  public $ban_expires;
  public $ban_severity;

  public function __construct() {
    $this->id = $_SESSION['user_id'];
    $this->name = $_SESSION['user_name'];
    $this->login = $_SESSION['user_login'];
    $this->group = $_SESSION['user_group'];
    $this->email = $_SESSION['user_email'];
    $this->pass = $_SESSION['user_pass'];
    $this->salt = $_SESSION['user_salt'];
  }
}
