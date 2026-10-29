<?php
/**
 * VAM flight operations workspace.
 * Navigation remains connected to the original CakePHP controllers and pages.
 */
$cakeDescription = __d('cake_dev', 'Virtual Airlines Manager');
$adminId = isset($_SESSION['id']) ? $_SESSION['id'] : '';
$adminAccess = !empty($_SESSION['access_administration_panel']) && $_SESSION['access_administration_panel'] == 1;
$isAdmin = $adminId !== '' && $adminAccess;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php echo $this->Html->charset(); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo h($cakeDescription . ': ' . $title_for_layout); ?></title>
  <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css(array('bootstrap.min', 'bootstrap-theme.min'));
    echo $this->Html->script(array('jquery.min', 'docs.min', 'bootstrap.min', 'vam-admin-2026'));
    echo $this->Html->script('ckeditor/ckeditor.js');
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->Html->css('vam-admin-2026');
    echo $this->fetch('script');
    if (!$isAdmin) {
      echo '<meta http-equiv="refresh" content="0;url=https://virtualairlinesmanager.net/">';
    }
  ?>
</head>
<body class="vam-admin-shell">
<?php if (!$isAdmin): ?>
  <main class="vam-admin-redirect">
    <p><?php echo h(__('You are being redirected to the airline home page.')); ?></p>
    <a href="https://virtualairlinesmanager.net/"><?php echo h(__('Continue')); ?></a>
  </main>
<?php else: ?>
<?php
$adminModules = array(
  'AIRLINE' => array(
    'icon' => 'plane',
    'modules' => array(
      array('label' => ADM_PARA, 'controller' => 'VaParameters', 'icon' => 'settings'),
      array('label' => ADM_HUB_MGR, 'controller' => 'Hubs', 'icon' => 'hub'),
      array('label' => ADM_FLEET_TYPE_MGR, 'controller' => 'Fleettypes', 'icon' => 'fleet'),
      array('label' => ADM_FLEET_MGR, 'controller' => 'Fleets', 'icon' => 'plane'),
      array('label' => ADM_ROUTE_MGR, 'controller' => 'Routes', 'icon' => 'route'),
      array('label' => ADM_ROUTE_FLEET_MGR, 'controller' => 'FleettypesRoutes', 'icon' => 'assignment'),
      array('label' => ADM_AIRPORTS_MGR, 'controller' => 'Airports', 'icon' => 'airport')
    )
  ),
  'CREW' => array(
    'icon' => 'users',
    'modules' => array(
      array('label' => ADM_PILOT_MGR, 'controller' => 'Gvausers', 'icon' => 'pilot'),
      array('label' => ADM_PILOT_RANK_MGR, 'controller' => 'Ranks', 'icon' => 'rank'),
      array('label' => ADM_USER_TYPE_MGR, 'controller' => 'UserTypes', 'icon' => 'users'),
      array('label' => ADM_STAFF_MGR, 'controller' => 'Staffs', 'icon' => 'staff'),
      array('label' => ADM_AWARDS_MGR, 'controller' => 'Awards', 'icon' => 'award'),
      array('label' => ADM_AWARDS_PILOR_MGR, 'controller' => 'AwardPilots', 'icon' => 'assignment'),
      array('label' => ADM_COURSES_MGR, 'controller' => 'Courses', 'icon' => 'course'),
      array('label' => ADM_TRAININGS_MGR, 'controller' => 'Gvausers', 'icon' => 'training')
    )
  ),
  'FLIGHT DESK' => array(
    'icon' => 'route',
    'modules' => array(
      array('label' => ADM_JUMP_MGR, 'href' => '../../vam/index_vam_op.php?page=validate_jumps', 'target' => '_blank', 'icon' => 'jump'),
      array('label' => ADM_FLIGHT_MGR, 'href' => '../../vam/index_vam_op.php?page=validate_flights', 'target' => '_blank', 'icon' => 'check'),
      array('label' => ADM_EVENT_MGR, 'controller' => 'Events', 'icon' => 'calendar'),
      array('label' => ADM_NOTAM_MGR, 'controller' => 'Notams', 'icon' => 'notam'),
      array('label' => ADM_TOUR_MODULE_MGR, 'controller' => 'Tours', 'icon' => 'tour'),
      array('label' => ADM_SIMACARS_FLIGHTS_MGR, 'controller' => 'Vampireps', 'icon' => 'radio'),
      array('label' => ADM_MANUAL_FLIGTS_MGR, 'controller' => 'Pireps', 'icon' => 'report')
    )
  ),
  'SYSTEM' => array(
    'icon' => 'settings',
    'modules' => array(
      array('label' => ADM_EMAIL_MGR, 'controller' => 'ConfigEmails', 'icon' => 'mail'),
      array('label' => ADM_LANGUAGES_MGR, 'controller' => 'Languages', 'icon' => 'language'),
      array('label' => ADM_ACARS_MGR, 'controller' => 'VamacarsParameters', 'icon' => 'radio'),
      array('label' => ADM_FINANTIAL_MODULE_MGR, 'controller' => 'FinancialParameters', 'icon' => 'finance'),
      array('label' => ADM_WEB_MGR, 'controller' => 'WebConfigurations', 'icon' => 'web')
    )
  )
);
$currentController = isset($this->params['controller']) ? strtolower($this->params['controller']) : '';
$currentTitle = isset($title_for_layout) && $title_for_layout !== '' ? $title_for_layout : __('Administration');
$activeGroup = '';
foreach ($adminModules as $groupName => $group) {
  foreach ($group['modules'] as $module) {
    if (isset($module['controller']) && strtolower($module['controller']) === $currentController) {
      $activeGroup = $groupName;
      break 2;
    }
  }
}
?>
<svg class="vam-icon-definitions" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true">
  <symbol id="vam-icon-plane" viewBox="0 0 24 24"><path d="M3 12.5 21 5l-6.5 15-3.2-6.3L3 12.5Z"/><path d="m11.3 13.7 5.1-5.1"/></symbol>
  <symbol id="vam-icon-settings" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3.2"/><path d="m19.4 13.5 1.1.9-1.4 2.4-1.4-.5a7.9 7.9 0 0 1-1.6.9l-.3 1.5h-2.8l-.3-1.5a7.9 7.9 0 0 1-1.6-.9l-1.4.5-1.4-2.4 1.1-.9a7.2 7.2 0 0 1 0-1.9l-1.1-.9 1.4-2.4 1.4.5a7.9 7.9 0 0 1 1.6-.9l.3-1.5h2.8l.3 1.5a7.9 7.9 0 0 1 1.6.9l1.4-.5 1.4 2.4-1.1.9a7.2 7.2 0 0 1 0 1.9Z"/></symbol>
  <symbol id="vam-icon-hub" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="3"/><path d="M12 3.5v5m8.5 3.5h-5m-3.5 8.5v-5m-8.5-3.5h5"/></symbol>
  <symbol id="vam-icon-fleet" viewBox="0 0 24 24"><path d="M4 18h16M6 18l1.5-7h9L18 18M9 11l2-5h2l2 5"/><path d="M8 14h8M10 18v2m4-2v2"/></symbol>
  <symbol id="vam-icon-route" viewBox="0 0 24 24"><circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><path d="M8 18h3a3 3 0 0 0 3-3v-1a3 3 0 0 1 3-3h1m-3-5-3 3 3 3"/></symbol>
  <symbol id="vam-icon-assignment" viewBox="0 0 24 24"><path d="M8 5h8v15H5V8h3zM8 5V3h8v2M8 12h5m-5 3h7"/><path d="m15.5 16 2 2 4-5"/></symbol>
  <symbol id="vam-icon-airport" viewBox="0 0 24 24"><path d="M3 20h18M5 20V9l7-5 7 5v11M9 20v-6h6v6M8 10h.01M12 10h.01M16 10h.01"/></symbol>
  <symbol id="vam-icon-pilot" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3"/><path d="M5 20a7 7 0 0 1 14 0M8 13h8l-1.2 3h-5.6L8 13Z"/></symbol>
  <symbol id="vam-icon-rank" viewBox="0 0 24 24"><path d="m12 3 2.5 5.2 5.7.8-4.1 4 .9 5.7-5-2.7-5 2.7.9-5.7-4.1-4 5.7-.8L12 3Z"/></symbol>
  <symbol id="vam-icon-users" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0M16 5.5a3 3 0 0 1 0 5.8m1.5 3.2a5 5 0 0 1 3 4.5"/></symbol>
  <symbol id="vam-icon-staff" viewBox="0 0 24 24"><path d="M4 20v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></symbol>
  <symbol id="vam-icon-award" viewBox="0 0 24 24"><circle cx="12" cy="9" r="6"/><path d="m8 14-1 7 5-2 5 2-1-7m-4-9 1.2 2.4 2.7.4-2 1.9.5 2.7-2.4-1.3-2.4 1.3.5-2.7-2-1.9 2.7-.4L12 5Z"/></symbol>
  <symbol id="vam-icon-course" viewBox="0 0 24 24"><path d="m3 8 9-4 9 4-9 4-9-4Z"/><path d="M7 10v5c3 2 7 2 10 0v-5M21 8v6"/></symbol>
  <symbol id="vam-icon-training" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8m-4-4v4m-5-9 3-3 2 2 4-4m-2 0h2v2"/></symbol>
  <symbol id="vam-icon-jump" viewBox="0 0 24 24"><path d="M12 3v12m-5-7 5-5 5 5M6 15l6 6 6-6M4 13v6m16-6v6"/></symbol>
  <symbol id="vam-icon-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></symbol>
  <symbol id="vam-icon-calendar" viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="16" rx="2"/><path d="M7.5 3v4M16.5 3v4M3.5 10h17M8 14h2m4 0h2m-8 3h2"/></symbol>
  <symbol id="vam-icon-notam" viewBox="0 0 24 24"><path d="M12 3 2.8 20h18.4L12 3Z"/><path d="M12 9v5m0 3h.01"/></symbol>
  <symbol id="vam-icon-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></symbol>
  <symbol id="vam-icon-language" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></symbol>
  <symbol id="vam-icon-radio" viewBox="0 0 24 24"><rect x="4" y="7" width="16" height="13" rx="2"/><path d="m7 7 10-4M8 11h5m-5 4h8m2-4h.01"/></symbol>
  <symbol id="vam-icon-finance" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18M7 14h4m3 0h3"/></symbol>
  <symbol id="vam-icon-tour" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 15 2-5 6-2-2 6-5 2-1-1Z"/></symbol>
  <symbol id="vam-icon-web" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 8h18M7 6h.01M10 6h.01m-3 6h10m-10 3h7"/></symbol>
  <symbol id="vam-icon-report" viewBox="0 0 24 24"><path d="M6 3h9l4 4v14H6zM14 3v5h5M9 13h7m-7 3h7"/></symbol>
</svg>

<div class="vam-admin-app">
  <header class="vam-flight-header">
    <a class="vam-admin-brand" href="<?php echo h($this->Html->url(array('controller' => 'Gvausers', 'action' => 'index'))); ?>">
      <span class="vam-brand-mark"><svg viewBox="0 0 24 24" aria-hidden="true"><use href="#vam-icon-plane" xlink:href="#vam-icon-plane"></use></svg></span>
      <span class="vam-brand-copy"><strong>PPT<span> / </span>VIRTUAL</strong><small>FLIGHT OPERATIONS</small></span>
    </a>
    <span class="vam-header-divider" aria-hidden="true"></span>
    <nav class="vam-flight-nav" aria-label="Administration modules">
      <?php foreach ($adminModules as $groupName => $group): ?>
        <?php
          $groupActive = $groupName === $activeGroup;
          $hasActiveModule = false;
          foreach ($group['modules'] as $candidate) {
            if (isset($candidate['controller']) && strtolower($candidate['controller']) === $currentController) {
              $hasActiveModule = true;
              break;
            }
          }
        ?>
        <details class="vam-nav-menu<?php echo $groupActive ? ' is-current' : ''; ?>" data-vam-nav-group<?php echo $groupActive ? ' open' : ''; ?>>
          <summary>
            <svg viewBox="0 0 24 24" aria-hidden="true"><use href="#vam-icon-<?php echo h($group['icon']); ?>" xlink:href="#vam-icon-<?php echo h($group['icon']); ?>"></use></svg>
            <span><?php echo h(__($groupName)); ?></span>
            <?php if ($hasActiveModule): ?><i class="vam-nav-current-mark" aria-hidden="true"></i><?php endif; ?>
            <svg class="vam-nav-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
          </summary>
          <div class="vam-nav-popover">
            <p class="vam-nav-popover-label"><?php echo h(__($groupName)); ?> · <?php echo count($group['modules']); ?> MODULES</p>
            <div class="vam-nav-grid">
              <?php foreach ($group['modules'] as $module): ?>
                <?php
                  $moduleUrl = isset($module['href']) ? $module['href'] : $this->Html->url(array('controller' => $module['controller'], 'action' => 'index'));
                  $active = isset($module['controller']) && strtolower($module['controller']) === $currentController;
                  $target = isset($module['target']) ? ' target="' . h($module['target']) . '" rel="noopener noreferrer"' : '';
                  $iconId = 'vam-icon-' . $module['icon'];
                ?>
                <a class="vam-module-link<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo h($moduleUrl); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?><?php echo $target; ?> data-vam-module>
                  <svg viewBox="0 0 24 24" aria-hidden="true"><use href="#<?php echo h($iconId); ?>" xlink:href="#<?php echo h($iconId); ?>"></use></svg>
                  <span><?php echo h($module['label']); ?></span>
                  <?php if ($active): ?><span class="vam-module-current-label"><?php echo h(__('CURRENT')); ?></span><?php endif; ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </details>
      <?php endforeach; ?>
    </nav>
    <div class="vam-header-actions">
      <label class="vam-module-search">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.2"/><path d="m15.4 15.4 4.2 4.2"/></svg>
        <input type="search" id="vam-module-search" placeholder="Find a module" autocomplete="off" aria-label="Search administration modules">
        <kbd>/</kbd>
      </label>
      <a class="vam-pilot-link" href="../../vam/index_vam_op.php?page=pilot_options" aria-label="Return to pilot area">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 5h5v5M19 5l-8 8"/><path d="M17 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h5"/></svg>
        <span><?php echo h(__('Pilot area')); ?></span>
      </a>
    </div>
  </header>

  <main class="vam-admin-content" role="main">
    <section class="vam-page-intro">
      <div class="vam-intro-copy">
        <p class="vam-eyebrow"><span class="vam-live-mark"></span><?php echo h(__('PPT VIRTUAL · OPERATIONS CONTROL')); ?></p>
        <h1><?php echo h($currentTitle); ?></h1>
        <p class="vam-intro-caption"><?php echo h(__('Flight operations workspace')); ?> <span>/</span> <?php echo h(__($activeGroup ? $activeGroup : 'CONTROL CENTER')); ?></p>
      </div>
      <div class="vam-route-visual" aria-hidden="true">
        <span class="vam-route-node vam-route-origin">PPT</span>
        <svg viewBox="0 0 360 106" preserveAspectRatio="none"><path class="vam-route-line" d="M12 74 C82 74 81 22 160 22 S230 85 348 42"/><circle cx="160" cy="22" r="3"/><circle cx="348" cy="42" r="5"/></svg>
        <span class="vam-route-node vam-route-destination">OPS</span>
        <span class="vam-route-caption">CONTROL · 2.6</span>
      </div>
    </section>

    <?php echo $this->Session->flash(); ?>
    <section class="vam-page-panel" aria-label="<?php echo h($currentTitle); ?>">
      <?php echo $this->fetch('content'); ?>
    </section>
    <footer class="vam-admin-footer"><span>PPT Virtual <i>·</i> Flight operations</span><span><?php echo date('Y'); ?> <i>·</i> VAM 2.6</span></footer>
  </main>
</div>
<?php endif; ?>
</body>
</html>
