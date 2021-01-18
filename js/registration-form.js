  /*
   * /js/registration-form.js
   *
   * Обработка введённых пользователем данных и создание капчи для формы регистрации.
   *
   */

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
