let menu = document.getElementById('user-menu');
if (menu) {
  menu.container = document.getElementById('header-user-menu');
  menu.state = 'closed';
  menu.toggle = function() {
    if (menu.state === 'closed') {
      menu.container.style.transitionDuration = '0s';
      menu.container.style.transitionDelay = '0s';
      menu.container.style.background = '#657e8b';
      menu.style.height = '94px';
      menu.state = 'opened';
    } else {
      menu.container.style.transitionDuration = '0.15s';
      menu.container.style.transitionDelay = '0.1s';
      menu.container.style.background = 'transparent';
      menu.style.height = '0px';
      menu.state = 'closed';
    }
  }
}

