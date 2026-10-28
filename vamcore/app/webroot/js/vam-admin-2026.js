(function () {
  'use strict';

  function initVamAdmin() {
    var body = document.body;
    var toggle = document.querySelector('[data-vam-sidebar-toggle]');
    var close = document.querySelector('[data-vam-sidebar-close]');
    var search = document.getElementById('vam-module-search');
    var mobileQuery = window.matchMedia('(max-width: 900px)');

    function setSidebarOpen(open) {
      body.classList.toggle('vam-sidebar-open', open);
      if (toggle) {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
      }
    }

    if (toggle) {
      toggle.addEventListener('click', function () {
        if (mobileQuery.matches) {
          setSidebarOpen(!body.classList.contains('vam-sidebar-open'));
        } else {
          var collapsed = body.classList.toggle('vam-sidebar-collapsed');
          toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
          toggle.setAttribute('aria-label', collapsed ? 'Expand navigation' : 'Collapse navigation');
        }
      });
    }

    if (close) {
      close.addEventListener('click', function () {
        setSidebarOpen(false);
      });
    }

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && mobileQuery.matches) {
        setSidebarOpen(false);
      }
      if (event.key === '/' && !event.ctrlKey && !event.metaKey && !event.altKey && search && !/INPUT|TEXTAREA|SELECT/.test(document.activeElement.tagName)) {
        event.preventDefault();
        if (!mobileQuery.matches && body.classList.contains('vam-sidebar-collapsed')) {
          body.classList.remove('vam-sidebar-collapsed');
          if (toggle) {
            toggle.setAttribute('aria-expanded', 'true');
            toggle.setAttribute('aria-label', 'Collapse navigation');
          }
        }
        search.focus();
      }
    });

    if (search) {
      search.addEventListener('input', function () {
        var query = search.value.trim().toLocaleLowerCase();
        document.querySelectorAll('[data-vam-nav-group]').forEach(function (group) {
          var visibleCount = 0;
          group.querySelectorAll('[data-vam-module]').forEach(function (item) {
            var matches = item.textContent.toLocaleLowerCase().indexOf(query) !== -1;
            item.hidden = !matches;
            if (matches) visibleCount += 1;
          });
          group.classList.toggle('is-empty', visibleCount === 0);
        });
      });
      search.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
          search.value = '';
          search.dispatchEvent(new Event('input', { bubbles: true }));
          search.blur();
        }
      });
    }

    window.addEventListener('resize', function () {
      if (!mobileQuery.matches) {
        setSidebarOpen(false);
        if (toggle) {
          var collapsed = body.classList.contains('vam-sidebar-collapsed');
          toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
          toggle.setAttribute('aria-label', collapsed ? 'Expand navigation' : 'Collapse navigation');
        }
      }
    });

    if (toggle && !mobileQuery.matches) {
      toggle.setAttribute('aria-expanded', body.classList.contains('vam-sidebar-collapsed') ? 'false' : 'true');
      toggle.setAttribute('aria-label', body.classList.contains('vam-sidebar-collapsed') ? 'Expand navigation' : 'Collapse navigation');
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initVamAdmin);
  } else {
    initVamAdmin();
  }
}());
