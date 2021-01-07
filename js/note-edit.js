let noteTitle = document.getElementById('note-title');
let noteContent = document.getElementById('note-content');
function formSubmit() {
  if (!(noteTitle.value || noteContent.value)) {
    alert ('Пустая заметка не может быть сохранена.\nЗаполните хотя бы одно поле!');
    return false;
  }
}
