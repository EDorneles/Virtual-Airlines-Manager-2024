(function () {
  'use strict';

  function closeMenus(menu) {
    menu.querySelectorAll(':scope > li.treeview').forEach(function (item) {
      item.classList.add('vam-menu-dismissed');
      item.classList.remove('menu-open');
      var anchor = item.querySelector(':scope > a');
      if (anchor) {
        anchor.setAttribute('aria-expanded', 'false');
      }
    });
  }

  document.querySelectorAll('.sidebar-menu').forEach(function (menu) {
    menu.addEventListener('click', function (event) {
      var toggle = event.target.closest('.sidebar-menu > li.treeview > a');
      if (toggle && menu.contains(toggle)) {
        var item = toggle.parentElement;
        item.classList.remove('vam-menu-dismissed');
        window.setTimeout(function () {
          toggle.setAttribute('aria-expanded', item.classList.contains('menu-open') ? 'true' : 'false');
        }, 0);
        return;
      }

      if (event.target.closest('.treeview-menu a')) {
        closeMenus(menu);
      }
    });
  });

  document.addEventListener('click', function (event) {
    if (event.target.closest('.sidebar-menu')) {
      return;
    }
    document.querySelectorAll('.sidebar-menu').forEach(closeMenus);
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      document.querySelectorAll('.sidebar-menu').forEach(closeMenus);
    }
  });
}());
