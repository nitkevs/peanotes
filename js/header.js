let menu = document.getElementById('user-menu');
menu.toggle = function() {
  if (menu.state !== "opened") {
    menu.style.height = "94px";
    menu.state = "opened";
  } else {
    menu.style.height = "0px";
    menu.state = "closed";
  }
}
