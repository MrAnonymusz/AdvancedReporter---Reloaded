@php
$raw_online_users = DB::table('sessions')->whereNotNull('user_id')->orderBy('last_activity', 'desc');

$online_users = [];
$all_online_users = [];

$ou_keys = 0;

foreach($raw_online_users->get() as $item)
{
  $itime = Carbon\Carbon::now($core->setting('site_timezone'))->diffInMinutes(Carbon\Carbon::createFromTimestamp($item->last_activity)->setTimezone($core->setting('site_timezone')));

  if($itime <= 5)
  {
    $online_users[] = $item;
  }
}
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>@yield('title') | {{ $core->setting('site_name') }}</title>
    <link rel="shortcut icon" href="{{ $core->asset_url($core->setting('site_favicon')) }}">
    <!-- Meta -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta content="mranonymusz" name="author">
    <meta property="og:site_name" content="{{ $core->setting('site_name') }}">
    <meta property="og:title" content="@yield('title') | {{ $core->setting('site_name') }}">
    <meta property="og:description" content="@yield('meta-description')">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('meta-image')">
    <meta property="og:url" content="{{ url()->current() }}">
    <!-- Meta -->
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/pace-progress/pace-theme.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/iziToast/css/iziToast.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('css/core.css') }}">
    @yield('page-stylesheets')
    <!-- Stylesheets -->
    <!-- Javascript -->
    <script src="{{ $core->asset_url('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ $core->asset_url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ $core->asset_url('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ $core->asset_url('plugins/iziToast/js/iziToast.min.js') }}"></script>
    <script src="{{ $core->asset_url('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ $core->asset_url('js/plugins.js') }}"></script>
    @yield('head-scripts')
    <!-- Javascript -->
    <!--Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!--Fonts -->
  </head>
  <body class="hold-transition sidebar-mini layout-navbar-fixed layout-footer-fixed accent-n-pink">
    <div class="wrapper">
      <!-- Navbar -->
      @include('includes.navbar')
      <!-- /.navbar -->
      <!-- Main Sidebar Container -->
      @include('includes.sidebar')
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        @yield('page-breadcrumb')
        <!-- Main content -->
        <div class="content">
          @yield('body')
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
          <h5>
            <i class="fas fa-users fa-xs text-n-pink mr-2"></i> <span>@lang('words.online-users')</span>
          </h5>
          <hr style="border-top: 1px solid rgba(255, 255, 255, 0.3)">
          @if(count($online_users) > 0)
            <ul class="side-online-user-list">
            @foreach($online_users as $key => $item)
            @php
            $user_item = App\User::where('id', $item->user_id)->first();
            $ou_keys += 1;
            @endphp
              @if($key <= 5)
              <li class="list-item">
                <div class="item-avatar-box">
                  <img src="{!! $core->avatar_url($user_item->uuid) !!}" draggable="false"/>
                </div>
                <p class="item-avatar-text">{{ $user_item->username }}</p>
              </li>
              @endif
            @endforeach
            @if($ou_keys > 5)
            <li class="list-item">
              <span class="d-block text-center">@lang('sentences.more-online-users', ['attribute' => "<span class=\"text-n-pink text-bold\">".count($online_users)."</span>"])</span>
            </li>
            @endif
            </ul>
          @else
          <p class="mb-0 text-center text-bold">@lang('sentences.no-online-users')</p>
          @endif
        </div>
      </aside>
      <!-- /.control-sidebar -->
      <!-- Main Footer -->
      @include('includes.footer')
    </div>
    <!-- ./wrapper -->
    <!-- Token -->
    @csrf
    <!-- Token -->
    <!-- REQUIRED SCRIPTS -->
    <script src="{{ $core->asset_url('js/adminlte.min.js') }}"></script>
    <script>
    $(function() {
      // Tooltips & Popovers
      $('[data-toggle="tooltip"]').tooltip();
      $('[data-toggle="popover"]').popover();

      // Bootstrap File Input
      $('.custom-file-input').bootstrapFileInput();
    });
    </script>
    @yield('bottom-scripts')
    <!-- REQUIRED SCRIPTS -->
  </body>
</html>