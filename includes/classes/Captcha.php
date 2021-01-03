<?php

/*
*
* /includes/classes/Captcha.php
*
* Класс Captcha предоставляет возможность использовать капчу
* на страницах сайта.
*
*/

class Captcha {

  public $arguments = array(
    1 => "Один",
    2 => "Два",
    3 => "Три",
    4 => "Четыре",
    5 => "Пять",
    6 => "Шесть",
    );

  public $answer;
  public $question;

  public function get_content() {

    $first_argument = $this->arguments[random_int(1, 6)];
    $second_argument = $this->arguments[random_int(1, 6)];

    $html_content = '<div id="captcha"><div><label for="captcha-answer" class="required">Введите ответ на контрольный вопрос, чтобы подтвердить, что вы человек. Например, на вопрос "Два плюс три" введите ответ 5 (цифрой).</label></div>'.$first_argument.' + '.$second_argument.' = <input type="text" maxlength="2" size="1" id="captcha-answer" name="captcha-answer" required></div>';

    $this->answer = array_search($first_argument, $this->arguments) + array_search($second_argument, $this->arguments);

    return $html_content;
  }
}
