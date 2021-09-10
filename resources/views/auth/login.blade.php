@extends('layouts.auth')

@section('title') {{ __('words.login') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('body')
<div class="login-box">
  <div class="login-logo">
    <a href="{{ url('') }}">{{ $core->setting('site_name') }}</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">@lang('pages.auth.login.title')</p>
      <form action="{{ url('auth/login') }}" id="login-form" method="post" autocomplete="off">
        <!-- Email -->
        <div class="form-group">
          <div class="ui ui-theme-n-pink left corner labeled input d-flex" id="email-input">
            <input type="text" name="email" placeholder="@lang('words.email-address')">
            <div class="ui left corner label">
              <i class="fas fa-asterisk icon"></i>
            </div>
          </div>
          <div id="error_email"></div>
        </div>
        <!-- Email -->
        <!-- Password -->
        <div class="form-group">
          <div class="ui ui-theme-n-pink left corner labeled input d-flex" id="password-input">
            <input type="password" name="password" placeholder="@lang('words.password')">
            <div class="ui left corner label">
              <i class="fas fa-asterisk icon"></i>
            </div>
          </div>
          <div id="error_password"></div>
        </div>
        <!-- Password -->
        @if($core->setting('enable_password_reset') == 1)
        <!-- Checkbox & Password Reminder -->
        <div class="row">
          <div class="col-md-6">
            <div class="icheck-n-pink">
              <input type="checkbox" id="remember-me">
              <label for="remember-me" class="remember-me-text">
              @lang('pages.auth.login.remember-me')
              </label>
            </div>
            <div id="error_remember_me"></div>
          </div>
          <div class="col-md-6">
            <div class="auth-login-pw-reminder-container">
              <a href="{{ url('auth/password-reminder') }}">
                @lang('words.forgot-password')
              </a>
            </div>
          </div>
        </div>
        <!-- Checkbox & Password Reminder -->
        @else
        <!-- Checkbox -->
        <div class="icheck-n-pink">
          <input type="checkbox" id="remember-me">
          <label for="remember-me" class="remember-me-text">
          @lang('pages.auth.login.remember-me')
          </label>
        </div>
        <div id="error_remember_me"></div>
        <!-- Checkbox --> 
        @endif
        <button type="submit" class="btn btn-n-pink btn-block btn-lg mt-3" style="text-transform: uppercase;">
          <i class="fas fa-sign-in-alt fa-sm mr-2"></i> @lang('pages.auth.login.sign-in')
        </button>
      </form>
      <!-- Helper Buttons -->
      @if($core->setting('enable_registration') == 1)
      <hr>
      <a href="{{ url('auth/register') }}" class="btn btn-success btn-block">
        <i class="fas fa-user-plus fa-sm mr-2"></i> @lang('words.register')
      </a>
      @endif
      <!-- Helper Buttons -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
@endsection

@section('bottom-scripts')
<script>
$(function() {
  $('#login-form').submit(function(e) {
    e.preventDefault();

    var _token = $('[name="_token"]').val();

    var email = $('[name="email"]').val();
    var password = $('[name="password"]').val();
    var remember_me;
    
    if($('#remember-me').is(':checked'))
    {
      remember_me = 1;
    }
    else
    {
      remember_me = 0;
    }

    $.ajax({
      url: "{{ url('auth/login') }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token,
        email: email,
        password: password,
        remember_me: remember_me
      },
      success: (data) => {
        if(data.error == 1)
        {
          if(data.error_email != "")
          {
            $('#email-input').addClass('error');

            $('#error_email').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_email
            });
          }
          else
          {
            $('#email-input').removeClass('error');

            $('#error_email').empty();
          }

          if(data.error_password != "")
          {
            $('#password-input').addClass('error');

            $('#error_password').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_password
            });
          }
          else
          {
            $('#password-input').removeClass('error');

            $('#error_password').empty();
          }

          if(data.error_remember_me != "")
          {
            $('#remember-me-text').addClass('text-danger');

            $('#error_remember_me').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_remember_me
            });
          }
          else
          {
            $('#remember-me-text').removeClass('text-danger');

            $('#error_remember_me').empty();
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
              $('[name="email"]').val('');
              $('#email-input').removeClass('error');
              $("#error_email").empty();

              $('[name="password"]').val('');
              $('#password-input').removeClass('error');
              $("#error_password").empty();

              $('#remember-me-text').removeClass('text-danger');
              $("#error_remember_me").empty();
            },
            onClosed: function() {
              window.location.href = "{{ url('panel/home') }}";
            }
          });
        }
      }
    });
  });
});
</script>
@endsection