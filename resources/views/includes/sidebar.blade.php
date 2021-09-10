<aside class="main-sidebar sidebar-dark-n-pink elevation-4 sidebar-no-expand">
  <!-- Brand Logo -->
  <a href="{{ url('panel/home') }}" class="brand-link">
  <img src="{{ $core->asset_url($core->setting('site_logo')) }}" alt="{{ $core->setting('site_name') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
  <span class="brand-text font-weight-light ml-2">{{ $core->setting('site_name') }}</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ $core->avatar_url() }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="{{ url('panel/user/account') }}" class="d-block">{{ $user->username }}</a>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Home -->
        <li class="nav-item">
          <a href="{{ $page != "home" ? url('panel/home') : 'javascript:;' }}" class="nav-link{{ $page != "home" ? '' : ' active' }}">
            <i class="nav-icon fas fa-home"></i>
            <p>@lang('pages.menu.home')</p>
          </a>
        </li>
        <!-- Home -->
        <!-- Reports -->
        <li class="nav-item mb-3">
          <a href="{{ $page != "report-list" ? url('panel/report/list') : 'javascript:;' }}" class="nav-link{{ $page != "report-list" ? '' : ' active' }}">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>@lang('pages.menu.reports')</p>
          </a>
        </li>
        <!-- Reports -->
        @if($core->hasPermissions(['can_see_users', 'can_see_roles', 'can_update_site_settings'], true))
        <!-- Admin Panel (Features) -->
        <li class="nav-item has-treeview">
          <a href="javascript:;" class="nav-link">
            <i class="nav-icon fas fa-life-ring"></i>
            <p>
              @lang('pages.menu.admin-panel')
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if($core->hasPermission('can_update_site_settings'))
            <li class="nav-item">
              <a href="{{ $page != "admin-site-settings" ? url('admin/site-settings') : 'javascript:;' }}" class="nav-link{{ $page != "admin-site-settings" ? '' : ' active' }}">
                <i class="nav-icon fas fa-cogs"></i>
                <p>@lang('pages.menu.site-settings')</p>
              </a>
            </li>
            @endif
            @if($core->hasPermission('can_see_users'))
            <li class="nav-item">
              <a href="{{ $page != "admin-user-list" ? url('admin/user/list') : 'javascript:;' }}" class="nav-link{{ $page != "admin-user-list" ? '' : ' active' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>@lang('pages.menu.user-manager')</p>
              </a>
            </li>
            @endif
            @if($core->hasPermission('can_see_roles'))
            <li class="nav-item">
              <a href="{{ $page != "admin-role-list" ? url('admin/role/list') : 'javascript:;' }}" class="nav-link{{ $page != "admin-role-list" ? '' : ' active' }}">
                <i class="nav-icon fas fa-user-tag"></i>
                <p>@lang('pages.menu.role-manager')</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <!-- Admin Panel (Features) -->
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>