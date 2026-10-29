(function () {
  'use strict';

  var header = document.querySelector('.vam-flight-header');
  if (!header) {
    return;
  }

  var groups = Array.prototype.slice.call(header.querySelectorAll('[data-vam-nav-group]'));
  var search = document.getElementById('vam-module-search');
  var initialOpen = groups.map(function (group) {
    return group.open;
  });

  function normalize(value) {
    return String(value || '').toLocaleLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  }

  function filterModules() {
    var query = normalize(search ? search.value.trim() : '');

    groups.forEach(function (group, index) {
      var links = Array.prototype.slice.call(group.querySelectorAll('[data-vam-module]'));
      var visible = 0;

      links.forEach(function (link) {
        var matches = !query || normalize(link.textContent).indexOf(query) !== -1;
        link.hidden = !matches;
        if (matches) {
          visible += 1;
        }
      });

      group.classList.toggle('is-empty', visible === 0);
      if (query) {
        group.open = visible > 0;
      } else {
        group.open = initialOpen[index];
      }
    });
  }

  groups.forEach(function (group) {
    group.addEventListener('toggle', function () {
      if (group.open && (!search || !search.value.trim())) {
        groups.forEach(function (otherGroup) {
          if (otherGroup !== group) {
            otherGroup.open = false;
          }
        });
      }
    });
  });

  if (search) {
    search.addEventListener('input', filterModules);
    search.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        var firstVisible = header.querySelector('[data-vam-module]:not([hidden])');
        if (firstVisible) {
          firstVisible.click();
        }
      }
    });
  }

  header.addEventListener('click', function (event) {
    if (event.target.closest('[data-vam-module]')) {
      groups.forEach(function (group) {
        group.open = false;
      });
    }
  });

  document.addEventListener('click', function (event) {
    if (!header.contains(event.target)) {
      groups.forEach(function (group) {
        group.open = false;
      });
    }
  });

  document.addEventListener('keydown', function (event) {
    var target = event.target;
    var isEditing = target && (
      target.matches('input, textarea, select') ||
      target.isContentEditable
    );

    if (event.key === '/' && !isEditing && search) {
      event.preventDefault();
      search.focus();
    } else if (event.key === 'Escape') {
      groups.forEach(function (group) {
        group.open = false;
      });
      if (search && document.activeElement === search) {
        search.value = '';
        search.blur();
        filterModules();
      }
    }
  });
}());
