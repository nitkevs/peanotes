  /*
   * /js/user-settings.js
   *
   * Обработка введённых пользователем данных.
   *
   */

let userSettingsForm = document.querySelector('#user-settings-form');

userSettingsForm.addEventListener('submit', function(event) {
  event.preventDefault();
  if (confirmPass.value !== pass.value) {
    alert("Пароли не совпадают!");
    return false;
  } else {
    this.submit();
  }
});

let userOldEmail = document.getElementById('email');
let userOldEmailValue = userOldEmail.value;

pass.addEventListener('input', function() {
  let oldPassLabel = document.getElementById('old-password-label');
  let oldPass = document.getElementById('old-password');
  if (pass.value !== '' || userOldEmail.value !== userOldEmailValue) {
    oldPassLabel.classList.add('required');
    oldPass.setAttribute('required', 'required');
  } else {
    oldPassLabel.classList.remove('required');
    oldPass.removeAttribute('required', 'required');
  }

  let confirmPassLabel = document.getElementById('confirm-password-label');
  if (pass.value !== '') {
    confirmPassLabel.classList.add('required');
    confirmPass.setAttribute('required', 'required');
  } else {
    confirmPassLabel.classList.remove('required');
    confirmPass.removeAttribute('required', 'required');
  }
});
