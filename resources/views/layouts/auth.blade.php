@php
switch($page)
{
  case 'auth-login':
    $this->page_class = 'login-page';
    break;
  case 'auth-register':
    $this->page_class = 'register-page';
    break;
  default:
    $this->page_class = '';
    break;
}
@endphp

<!DOCTYPE html>
<html>
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
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/pace-progress/pace-theme.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/iziToast/css/iziToast.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/semantic-ui/input.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/semantic-ui/button.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('plugins/semantic-ui/label.css') }}">
    <link rel="stylesheet" href="{{ $core->asset_url('css/core.css') }}">
    @yield('page-stylehseets')
    <!-- Stylesheets -->
    <!-- Javascript -->
    <script src="{{ $core->asset_url('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ $core->asset_url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ $core->asset_url('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ $core->asset_url('plugins/iziToast/js/iziToast.min.js') }}"></script>
    <script src="{{ $core->asset_url('js/plugins.js') }}"></script>
    @yield('head-scripts')
    <!-- Javascript -->
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  </head>
  <body class="hold-transition {{ $this->page_class }} accent-n-pink">
    <!-- BODY -->
    @yield('body')
    <!-- BODY -->
    <!-- Token -->
    @csrf
    <!-- Token -->
    <!-- Bottom Scripts -->
    <script src="{{ $core->asset_url('js/adminlte.min.js') }}"></script>
    @yield('bottom-scripts')
    <!-- Bottom Scripts -->
  </body>
</html>