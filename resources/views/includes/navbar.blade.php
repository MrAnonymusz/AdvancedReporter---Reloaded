<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="javascript:;" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="javascript:;" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="far fa-user-circle mr-2"></i> {{ $user->username }}
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <p class="dropdown-header text-center">
          <span class="role{{ $core->role()->css_class != "" ?  ' '.$core->role()->css_class : '' }}">{{ $core->role()->display_name }}</span>
        </p>
        <a href="{{ $page != "user-account-settings" ? url('panel/user/account') : 'javascript:;' }}" class="dropdown-item{{ $page != "user-account-settings" ? '' : ' active' }}">
          <i class="fas fa-user-cog mr-2"></i> @lang('pages.menu.account-settings')
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ url('auth/logout/'.$user->uuid) }}" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> @lang('pages.menu.logout')
        </a>
      </div>
    </li>
    <!-- User Dropdown -->
    <!-- Notifications Dropdown Menu -->
    <!-- <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="javascript:;">
      <i class="far fa-bell"></i>
      <span class="badge badge-danger navbar-badge">15</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">15 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="javascript:;" class="dropdown-item">
        <i class="fas fa-envelope mr-2"></i> 4 new messages
        <span class="float-right text-muted text-sm">3 mins</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="javascript:;" class="dropdown-item">
        <i class="fas fa-users mr-2"></i> 8 friend requests
        <span class="float-right text-muted text-sm">12 hours</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="javascript:;" class="dropdown-item">
        <i class="fas fa-file mr-2"></i> 3 new reports
        <span class="float-right text-muted text-sm">2 days</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="javascript:;" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li> -->
    <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="javascript:;" role="button"><i
        class="fas fa-th-large"></i></a>
    </li>
  </ul>
</nav>