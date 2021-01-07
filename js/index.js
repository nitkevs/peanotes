let oldActive;

// функция выводит выбранную заметку на экран
function showNoteContent(activeNote) {
  //  в переменную output записываем блок note-content, куда будет выведена заметка
  let output = document.getElementById('note-content');
  let noteTitle = activeNote.querySelector('.note-title').innerHTML;
  let noteContent = activeNote.querySelector('.note-teaser').innerHTML;
  let noteDate = "Создано:&nbsp;" + activeNote.dataset.date;
  let noteLastModified = "";
  if (activeNote.dataset.lastModified) {
    noteLastModified = "Последнее изменение:&nbsp;" + activeNote.dataset.lastModified;
  }
  console.log (noteDate + " " + noteLastModified);
  // Если в переменной oldActive есть какой-то блок,
  if (oldActive) {
    // удалить его из класса active
    oldActive.classList.remove('active');
  }
  // а выбранному блоку присвоить класс active
  activeNote.classList.add('active');
  // записать выбранный активный блок в переменную oldActive
  oldActive = activeNote;

  output.innerHTML = "<h2>" + noteTitle + "</h2>\n<div id=\"note-dates\">\n<div id=\"created\">" + noteDate + "</div>\n<div id=\"last-modified\">" + noteLastModified + "</div>\n</div><div>" + noteContent + "</div>";
}

function showEditLinks(note) {
  let noteEditButtons = note.querySelector('.note-edit-buttons');
  noteEditButtons.style.display = "block";
}

function hideEditLinks(note) {
  let noteEditButtons = note.querySelector('.note-edit-buttons');
  noteEditButtons.style.display = "none";
}
