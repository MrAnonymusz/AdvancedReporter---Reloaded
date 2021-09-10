@extends('layouts.app')

@section('title') {{ __('words.create-user') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@php
$timezone_list = json_decode(Storage::disk('private')->get('timezones.json'));
@endphp

@section('page-stylesheets')
<link rel="stylesheet" href="{{ $core->asset_url('plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ $core->asset_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.css') }}">
@endsection

@section('head-scripts')
<script src="{{ $core->asset_url('plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('words.create-user')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.admin')</li>
          <li class="breadcrumb-item"><a href="{{ url('admin/user/list') }}">@lang('pages.menu.user-manager')</a></li>
          <li class="breadcrumb-item active">@lang('words.create-user')</li>
        </ol>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@endsection

@section('body')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-success card-outline">
        <div class="card-header">
          <h5 class="card-title">@lang('words.create-user')</h5>
        </div>
        <div class="card-body">
          <form action="{{ url('admin/user/create') }}" method="post" id="create-user-form" autocomplete="off">
            <div class="row">
              <!-- Username -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.username')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" name="username">
                  </div>
                  <div id="error_username"></div>
                </div>
              </div>
              <!-- Username -->
              <!-- Email -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.email')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" name="email">
                  </div>
                  <div id="error_email"></div>
                </div>
              </div>
              <!-- Email -->
              <!-- Password -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.password')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" name="password">
                  </div>
                  <div id="error_password"></div>
                </div>
              </div>
              <!-- Password -->
              <!-- Role -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.role')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-tag"></i>
                      </span>
                    </div>
                    <select name="role" class="custom-select">
                      @foreach(App\Role::where('enabled', 1)->orderBy('created_at', 'desc')->get() as $item)
                      <option value="{{ $item->special_id }}"{{ $item->default == 1 ? ' selected' : '' }}>{{ $item->display_name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div id="error_role"></div>
                </div>
              </div>
              <!-- Role -->
              <!-- Timezone -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.timezone')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-clock"></i>
                      </span>
                    </div>
                    <select name="timezone" class="custom-select">
                      @foreach($timezone_list as $item)
                      <option value="{{ $item }}"{{ $item == $core->setting('site_timezone') ? ' selected' : '' }}>{{ $item }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div id="error_timezone"></div>
                </div>
              </div>
              <!-- Timezone -->
              <!-- Permissions -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.permission.pl')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <select name="permissions" class="custom-select" multiple>
                    @foreach(App\Permission::all() as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                  </select>
                  <div id="error_permissions"></div>
                </div>
              </div>
              <!-- Permissions -->
              <!-- User Password -->
              <div class="col-md-12">
                <hr>
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.your-password')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" name="user-password">
                  </div>
                </div>
              </div>
              <!-- User Password -->
            </div>
            <div class="d-block text-center">
              <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-plus-circle mr-2"></i> <span>@lang('words.create-user')</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection

@section('bottom-scripts')
<script>
$(function() {
  // Permissions Select2
  $('[name="permissions"]').select2({
    theme: 'bootstrap4'
  });

  // Create User Form
  $('#create-user-form').submit(function(e) {
    e.preventDefault();

    var _token = $('[name="_token"]').val();

    var username = $('[name="username"]').val();
    var email = $('[name="email"]').val();
    var password = $('[name="password"]').val();
    var role = $('[name="role"]').val();
    var timezone = $('[name="timezone"]').val();
    var permissions = $('[name="permissions"]').val();

    var user_password = $('[name="user-password"]').val();

    $.ajax({
      url: "{{ url('admin/user/create') }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token,
        username: username,
        email: email,
        password: password,
        role: role,
        timezone: timezone,
        permissions: permissions,
        user_password: user_password
      },
      success: (data) => {
        if(data.error == 1)
        {
          if(data.error_username != "")
          {
            $('[name="username"]').addClass('is-invalid');

            $('#error_username').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_username
            });
          }
          else
          {
            $('[name="username"]').removeClass('is-invalid');

            $('#error_username').empty();
          }

          if(data.error_email != "")
          {
            $('[name="email"]').addClass('is-invalid');

            $('#error_email').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_email
            });
          }
          else
          {
            $('[name="email"]').removeClass('is-invalid');

            $('#error_email').empty();
          }
          
          if(data.error_password != "")
          {
            $('[name="password"]').addClass('is-invalid');

            $('#error_password').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_password
            });
          }
          else
          {
            $('[name="password"]').removeClass('is-invalid');

            $('#error_password').empty();
          }
          
          if(data.error_role != "")
          {
            $('[name="role"]').addClass('is-invalid');

            $('#error_role').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_role
            });
          }
          else
          {
            $('[name="role"]').removeClass('is-invalid');

            $('#error_role').empty();
          }
          
          if(data.error_timezone != "")
          {
            $('[name="timezone"]').addClass('is-invalid');

            $('#error_timezone').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_timezone
            });
          }
          else
          {
            $('[name="timezone"]').removeClass('is-invalid');

            $('#error_timezone').empty();
          }
          
          if(data.error_permissions != "")
          {
            $('#error_permissions').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_permissions
            });
          }
          else
          {
            $('#error_permissions').empty();
          }
        }
        else if(data.error == 2)
        {
          iziToast.show({
            class: 'bg-danger',
            iconColor: '#fff',
            titleColor: '#fff',
            messageColor: '#fff',
            icon: 'fas fa-exclamation-circle',
            title: '{{ __("sentences.something-went-wrong") }}',
            message: data.message,
            layout: 2,
            position: 'topRight',
            displayMode: 2,
            timeout: 2500,
            maxWidth: 350,
            transitionIn: 'bounceInLeft',
            transitionOut: 'fadeOutRight'
          });
        }
        else if(data.error == 0)
        {
          iziToast.show({
            class: 'bg-success',
            iconColor: '#fff',
            titleColor: '#fff',
            messageColor: '#fff',
            icon: 'fas fa-check-circle',
            title: '{{ __("words.success") }}!',
            message: data.message,
            layout: 2,
            position: 'topRight',
            displayMode: 2,
            timeout: 2500,
            maxWidth: 350,
            transitionIn: 'bounceInLeft',
            transitionOut: 'fadeOutRight',
            onOpening: function() {
              $('[name="username"]').val('');
              $('[name="username"]').removeClass('is-invalid');
              $("#error_username").empty();

              $('[name="email"]').val('');
              $('[name="email"]').removeClass('is-invalid');
              $("#error_email").empty();

              $('[name="password"]').val('');
              $('[name="password"]').removeClass('is-invalid');
              $("#error_password").empty();

              $('[name="role"]').removeClass('is-invalid');
              $("#error_role").empty();
              
              $('[name="timezone"]').removeClass('is-invalid');
              $("#error_timezone").empty();
              
              $("#error_permissions").empty();

              $('[name="user-password"]').val('');
            },
            onClosed: function() {
              window.location.href = "{{ url('admin/user/list') }}";
            }
          });
        }
      }
    });
  });
});
</script>
@endsection