  /*
   * Обработка введённых пользователем данных и создание капчи для формы регистрации.
   *
   */

    // Вешаем события input, focus и blur на поля формы.

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

    function showTerms() {
      let frame = document.getElementById('term-conditions');
      frame.style.display = "block";
      frame.scrollIntoView({behavior: 'smooth'});
      console.log(frame);
    }

    let registerForm = document.querySelector('#register-form');

    registerForm.addEventListener('submit', function(event) {
      event.preventDefault();
      if (confirmPass.value !== pass.value) {
        alert("Пароли не совпадают!");
        return false;
      } else {
        this.submit();
      }
    });
