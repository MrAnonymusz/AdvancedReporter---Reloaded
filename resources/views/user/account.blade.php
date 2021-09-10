@extends('layouts.app')

@section('title') {{ __('pages.menu.account-settings') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@php
$this->mojang_api = file_get_contents('https://api.mojang.com/users/profiles/minecraft/'.$user->username);

if(!empty($this->mojang_api) && !preg_match('/error+/', $this->mojang_api))
{
  $this->mc_uuid = json_decode($this->mojang_api)->id;
}
else
{  
  $this->mc_uuid = 'ec561538f3fd461daff5086b22154bce';
}

$this->uuac_crafatar_img_url = 'https://crafatar.com/avatars/'.$this->mc_uuid;
@endphp

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.menu.account-settings')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.panel')</li>
          <li class="breadcrumb-item active">@lang('pages.menu.account-settings')</li>
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
<div class="container account-settings-page">
  <div class="row">
    <!-- Avatar Box -->
    <div class="col-md-3">
      <div class="card card-n-pink card-outline">
        <div class="card-body">
          @if($core->hasPermission('can_update_avatar'))
          <div class="avatar-container avatar-container-n-pink">
            <img src="{{ $core->avatar_url() }}" class="image" id="avatar-image-tag" draggable="false"/>
            <a href="javascript:;" class="avatar-update-link">
              <div class="link-content">
                <i class="fas fa-image icon"></i>
                <span class="text">@lang('pages.account-settings.update-avatar')</span>
              </div>
            </a>
          </div>
          @else
          <img src="{{ $core->avatar_url() }}" class="avatar-img" draggable="false"/>
          @endif
        </div>
      </div>
    </div>
    <!-- Avatar Box -->
    <!-- Update User Account Settings -->
    <div class="col-md-9">
      <div class="card card-n-pink card-outline">
        <div class="card-header">
          <h5 class="m-0">@lang('pages.account-settings.update-user-account')</h5>
        </div>
        <div class="card-body">
          @if(!empty($user->updated_at))
          <div class="alert alert-secondary">
            <i class="fas fa-info-circle mr-2"></i> <b>@lang('words.updated-at'):</b> {{ $core->dt_format($user->updated_at) }}
          </div>
          @endif
          <form action="{{ url('panel/user/account/update') }}" id="update-user-account-form" method="post" autocomplete="off">
            <div class="form-group">
              <p class="form-helper">
                <span>@lang('words.username')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
              </p>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text p-0" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<i class='fas fa-cube fa-xs mr-1'></i> {{ __('words.crafatar-avatar') }}">
                    <img src="{!! $this->uuac_crafatar_img_url !!}" class="uuac-crafatar" draggable="false"/>
                  </span>
                </div>
                <input type="text" class="form-control" name="username" value="{{ $user->username }}">
              </div>
              <div id="error_username"></div>
            </div>
            <div class="form-group">
              <p class="form-helper">
                <span>@lang('words.email')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
              </p>
              <input type="text" class="form-control" name="email" value="{{ $user->email }}">
              <div id="error_email"></div>
            </div>
            <div class="form-group">
              <p class="form-helper">
                <span>@lang('words.password')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
              </p>
              <input type="password" class="form-control" name="password">
              <div id="error_password"></div>
            </div>
            <div class="form-group">
              <p class="form-helper">
                <span>@lang('words.timezone')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
              </p>
              <select class="custom-select" name="timezone">
                @php
                $this->timezone_list = json_decode(Storage::disk('private')->get('timezones.json'));

                foreach($this->timezone_list as $item)
                {
                  if($user->timezone == $item)
                  {
                    $item_selected = " selected";
                  }
                  else
                  {
                    $item_selected = "";
                  }

                  echo "<option value=\"$item\"$item_selected>$item</option>";
                }
                @endphp
              </select>
              <div id="error_timezone"></div>
            </div>
            <button type="submit" class="btn btn-success btn-block">
              <i class="fas fa-save mr-2"></i> <span>@lang('words.save')</span>
            </button>
          </form>
        </div>
      </div>
    </div>
    <!-- Update User Account Settings -->
  </div>
</div>
<!-- /.container-fluid -->
<!-- Modals -->
<!-- Avatar Update Modal -->
<div class="modal fade" id="update-avatar-modal" tabindex="-1" aria-labelledby="update-avatar-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="update-avatar-modalLabel">@lang('pages.account-settings.update-avatar')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Avatar Upload -->
        <div class="custom-control custom-radio" id="avatar-upload-cc">
          <input type="radio" id="avatar-upload-radio" name="customRadio" class="custom-control-input" checked>
          <label class="custom-control-label" for="avatar-upload-radio">@lang('words.upload-avatar')</label>
        </div>
        <div class="avatar-upload-box">
          <!-- File Upload Info -->
          <div class="alert alert-info mt-2">
            <ul class="pl-3 mb-0">
              <li>
                <u>@lang('pages.account-settings.max-file-size'):</u> {{ floor($core->setting('allowed_avatar_size') / 1024) }} MB
              </li>
              <li>
                <u>@lang('pages.account-settings.allowed-avatar-types'):</u>
                @php
                $this->allowed_avatar_file_types = json_decode($core->setting('allowed_avatar_types'));

                $this->aft_template = "";

                for($x = 0; $x < count($this->allowed_avatar_file_types); $x++)
                {
                  $this->aft_template .= $this->allowed_avatar_file_types[$x];

                  if($this->allowed_avatar_file_types[$x] != end($this->allowed_avatar_file_types))
                  {
                    $this->aft_template .= ", ";
                  }
                }

                echo $this->aft_template;
                @endphp
              </li>
            </ul>
          </div>
          <!-- File Upload Info -->
          <form action="{{ url('avatar/update') }}" method="post" id="update-avatar-form" autocomplete="off">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="avatar-file-upload">
              <label class="custom-file-label" for="avatar-file-upload">@lang('words.choose-file')</label>
            </div>
            <div id="error_avatar_file"></div>
          </form>
        </div>
        <!-- Avatar Upload -->
        <!-- Crafatar Avatar -->
        <div class="custom-control custom-radio mt-3" id="crafatar-avatar-cc">
          <input type="radio" id="crafatar-avatar-radio" name="customRadio" class="custom-control-input">
          <label class="custom-control-label" for="crafatar-avatar-radio">@lang('words.crafatar')</label>
        </div>
        <div class="crafatar-avatar-box box-hidden" style="display: none;">
          <div class="input-group mt-2">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@lang('words.username')</span>
            </div>
            <input type="text" class="form-control" value="{{ $user->username }}" readonly>
          </div>
          <div id="error_crafatar"></div>
        </div>
        <!-- Crafatar Avatar -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btn-remove-avatar">
          <i class="fas fa-trash-alt mr-1"></i> <span>@lang('words.remove')</span>
        </button>
        <button type="button" class="btn btn-success" id="btn-submit-avatar-file">
          <i class="fas fa-save mr-1"></i> <span>@lang('words.save')</span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Avatar Update Modal -->
<!-- Account Update Password Confirm Modal -->
<div class="modal fade" id="update-account-pw-confirm-modal" tabindex="-1" aria-labelledby="update-account-pw-confirm-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="update-account-pw-confirm-modalLabel">@lang('words.confirm-password')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <p class="form-helper">
          <span>@lang('words.password')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
        </p>
        <input type="password" class="form-control" name="update-account-password-confirm-input">
        <div id="error_update_account_password_confirm"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-n-pink btn-block" id="btn-confirm-password">
          <i class="fas fa-check-circle fa-sm mr-1"></i> <span>@lang('words.confirm')</span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Account Update Password Confirm Modal -->
<!-- Modals -->
@endsection

@section('bottom-scripts')
<script>
$(function() {
  // Update Avatar Modal
  $('.avatar-update-link').click(function(e) {
    e.preventDefault();

    $('#update-avatar-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });

  // Avatar Radio Boxes
  $('#avatar-upload-cc').click(function() {
    if(!$('.crafatar-avatar-box').hasClass('box-hidden'))
    {
      $('.crafatar-avatar-box').slideUp();

      $('.crafatar-avatar-box').addClass('box-hidden');

      $('.avatar-upload-box').removeClass('box-hidden');

      $('.avatar-upload-box').slideDown();
    }
  });

  $('#crafatar-avatar-cc').click(function() {
    if(!$('.avatar-upload-box').hasClass('box-hidden'))
    {
      $('.avatar-upload-box').slideUp();

      $('.avatar-upload-box').addClass('box-hidden');

      $('.crafatar-avatar-box').removeClass('box-hidden');

      $('.crafatar-avatar-box').slideDown();
    }
  });

  // Update (Upload) Avatar Form
  $('#update-avatar-form').submit(function(e) {
    e.preventDefault();
  });

  $('#btn-submit-avatar-file').click(function() {
    let formData = new FormData();

    if($('#avatar-upload-radio').is(':checked'))
    {
      formData.append('admin', 0);
      formData.append('avatar_file', $('#avatar-file-upload')[0].files[0]);

      $.ajax({
        type: "post",
        url: "{{ url('avatar/upload/'.$user->uuid) }}",
        processData: false,
        contentType: false,
        data: formData,
        headers: {
          'X-CSRF-TOKEN': $('[name="_token"]').val()
        },
        success: function(data) {
          if(data.error == 1)
          {
            if(data.error_avatar_file != "")
            {
              $('#avatar-file-upload').addClass('is-invalid');

              $('#error_avatar_file').generateAlert({
                icon: "fas fa-exclamation-circle",
                css_class: "custom-alert-danger mt-2 mb-3",
                text: data.error_avatar_file
              });
            }
            else
            {
              $('#avatar-file-upload').removeClass('is-invalid');

              $('#error_avatar_file').empty();
            }
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
                $('#avatar-file-upload').removeClass('text-danger');
                $("#error_avatar_file").empty();

                var avatar_url = "{{ $core->avatar_url() }}?version=" + Date.now();

                $('.sidebar > .user-panel > .image > img').attr('src', avatar_url);
                $('.avatar-container > #avatar-image-tag').attr('src', avatar_url);

                $('#update-avatar-modal').modal('hide');
              }
            });
          }
        }
      });
    }
    else if($('#crafatar-avatar-radio').is(':checked'))
    {
      var _token = $('[name="_token"]').val();

      $.ajax({
        type: "PUT",
        url: "{{ url('avatar/update/'.$user->uuid) }}",
        data: {
          _method: "PUT",
          _token: _token,
          type: "crafatar",
          admin: 0
        },
        success: (data) => {
          if(data.error == 1)
          {
            if(data.error_message != "")
            {
              $('#error_crafatar').generateAlert({
                icon: "fas fa-exclamation-circle",
                css_class: "custom-alert-danger mt-2 mb-3",
                text: data.error_message
              });
            }
            else
            {
              $('#error_crafatar').empty();
            }
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
                $("#error_crafatar").empty();

                var avatar_url = "{{ $core->avatar_url() }}?version=" + Date.now();

                $('.sidebar > .user-panel > .image > img').attr('src', avatar_url);
                $('.avatar-container > #avatar-image-tag').attr('src', avatar_url);

                $('#update-avatar-modal').modal('hide');
              }
            });
          }
        }
      });
    }
  });

  // Remove Avatar
  $('#btn-remove-avatar').click(function() {
    var _token = $('[name="_token"]').val();

    $.ajax({
      url: "{{ url('avatar/remove/'.$user->uuid) }}",
      method: "DELETE",
      dataType: "JSON",
      data: {
        _token: _token,
        _method: "DELETE",
        admin: 0
      },
      success: (data) => {
        if(data.error == 2)
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
              var avatar_url = "{{ $core->avatar_url() }}?version=" + Date.now();

              $('.sidebar > .user-panel > .image > img').attr('src', avatar_url);
              $('.avatar-container > #avatar-image-tag').attr('src', avatar_url);

              $('#update-avatar-modal').modal('hide');
            }
          });
        }
      }
    });
  });

  // Update User Account Settings Form
  $('#update-user-account-form').submit(function(e) {
    e.preventDefault();

    // Update Account Password Confirm Modal
    $('#update-account-pw-confirm-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });

  // Confirm Password & Update User Account Settings Form
  $('#btn-confirm-password').click(function() {
    var _token = $('[name="_token"]').val();
    var password = $('[name="update-account-password-confirm-input"]').val();

    $.ajax({
      url: "{{ url('verify-password') }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token,
        password: password
      },
      success: (pw_data) => {
        if(pw_data.error == 1)
        {
          if(data.error_message != "")
          {
            $('#error_update_account_password_confirm').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_message
            });
          }
          else
          {
            $('#error_update_account_password_confirm').empty();
          }
        }
        else if(pw_data.error == 0)
        {
          $('#update-account-pw-confirm-modal').modal('hide');
          $('[name="update-account-password-confirm-input"]').val('');
          $('#error_update_account_password_confirm').empty();

          var _token = $('[name="_token"]').val();

          var username = $('[name="username"]').val();
          var email = $('[name="email"]').val();
          var password = $('[name="password"]').val();
          var timezone = $('[name="timezone"]').val();

          $.ajax({
            url: "{{ url('panel/user/account/update') }}",
            method: "PUT",
            dataType: "JSON",
            data: {
              _token: _token,
              method : "PUT",
              username: username,
              email: email,
              password: password,
              timezone: timezone
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
                  onOpening: () => {
                    $('[name="username"]').removeClass('is-invalid');
                    $('#error_username').empty();

                    $('[name="email"]').removeClass('is-invalid');
                    $('#error_email').empty();
                    
                    $('[name="password"]').removeClass('is-invalid');
                    $('#error_password').empty();
                    
                    $('[name="timezone"]').removeClass('is-invalid');
                    $('#error_timezone').empty();
                  },
                  onClosed: () => {
                    window.location.href = "";
                  }
                });
              }
            }
          });
        }
      }
    });
  });
});
</script>
@endsection
