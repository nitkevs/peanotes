  /*
   * /js/check-input-data.js
   *
   * Обработка введённых пользователем данных.
   * Общий скрипт для страниц регистрации и настроек пользователя.
   *
   */

  let formFields = document.querySelectorAll ('form > input:not(#confirm-password)');

  for (i = 0; i < formFields.length; i++) {

    formFields[i].addEventListener('input', function() {
      if(this.checkValidity() || !this.value) {
        this.style.color = "rgb(51, 51, 51)";
      } else {
        this.style.color = "rgb(223, 32, 32)";
      }
    });

    formFields[i].addEventListener('focus', function() {
      let description = this.nextElementSibling;
      description.style.color = "rgb(51, 51, 51)";
    });

    formFields[i].addEventListener('blur', function() {
      let description = this.nextElementSibling;
      description.style.color = "rgb(153, 153, 153)";
    });
  }

  // Проверяем совпадают ли пароли

  let pass = document.getElementById('password');
  let confirmPass = document.getElementById('confirm-password');
  let genericColor = "rgb(153, 153, 153)";

  function checkPass() {

    let description = confirmPass.nextElementSibling;

    if (confirmPass.value === pass.value) {
      confirmPass.style.color = genericColor;
      description.style.color = genericColor;
      description.textContent = "Пароли совпадают.";
    } else if (confirmPass.value) {
      confirmPass.style.color = "rgb(223, 32, 32)";
      description.style.color = "rgb(223, 32, 32)";
      description.textContent = "Пароли не совпадают!";
    } else {
      description.textContent = "Пароли не совпадают!";
    }
  }

  confirmPass.addEventListener('input', checkPass);
  pass.addEventListener('input', checkPass);

  confirmPass.addEventListener('focus', function() {
    let description = this.nextElementSibling;
    genericColor = "rgb(51, 51, 51)";
    if (description.style.color !== "rgb(223, 32, 32)"){
      description.style.color = "rgb(51, 51, 51)";
    }
  });

  confirmPass.addEventListener('blur', function() {
    let description = this.nextElementSibling;
    genericColor = "rgb(153, 153, 153)";
    if (description.style.color !== "rgb(223, 32, 32)"){
      description.style.color = "rgb(153, 153, 153)";
    }
  });
