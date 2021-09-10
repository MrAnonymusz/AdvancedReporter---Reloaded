@extends('layouts.auth')

@section('title') {{ __('words.register') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@php
$this->terms_and_conditions = Storage::disk('private')->get('terms-and-conditions.html');
$this->privacy_of_policy = Storage::disk('private')->get('privacy-of-policy.html');
@endphp

@section('body')
<div class="register-box">
  <div class="register-logo">
    <a href="{{ url('') }}">{{ $core->setting('site_name') }}</a>
  </div>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">@lang('pages.auth.register.title')</p>
      <form action="{{ url('auth/register') }}" method="post" id="register-form" autocomplete="off">
        <!-- Username -->
        <div class="form-group">
          <div class="ui ui-theme-n-pink left corner labeled input d-flex" id="username-input">
            <input type="text" name="username" placeholder="@lang('words.username')">
            <div class="ui left corner label">
              <i class="fas fa-asterisk icon"></i>
            </div>
          </div>
          <div id="error_username"></div>
        </div>
        <!-- Username -->
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
          <div class="ui ui-theme-n-pink left corner labeled input action d-flex" id="password-input">
            <input type="password" name="password" placeholder="@lang('words.password')">
            <div class="ui left corner label">
              <i class="fas fa-asterisk icon"></i>
            </div>
          </div>
          <div id="error_password"></div>
        </div>
        <!-- Password -->
        <!-- Terms and Conditions -->
        <div class="icheck-n-pink">
          <input type="checkbox" id="checkbox-terms-and-conditions" name="terms" value="agree">
          <label for="checkbox-terms-and-conditions">
            <span id="terms-and-conditions-text"></span>@lang('pages.auth.register.i-accept-the', ['attribute'=> '<span class="text-n-pink" id="toggle-terms-and-conditions-modal">'.__('words.terms-and-conditions').'</span>'])
          </label>
        </div>
        <div id="error_terms_and_conditions"></div>
        <!-- Terms and Conditions -->
        <!-- Privacy of Policy -->
        <div class="icheck-n-pink">
          <input type="checkbox" id="checkbox-privacy-of-policy" name="terms" value="agree">
          <label for="checkbox-privacy-of-policy">
            <span id="privacy-of-policy-text">@lang('pages.auth.register.i-accept-the', ['attribute' => '<span class="text-n-pink" id="toggle-privacy-of-policy-modal">'.__('words.privacy-of-policy').'</span>'])</span>
          </label>
        </div>
        <div id="error_privacy_of_policy"></div>
        <!-- Privacy of Policy -->
        <button type="submit" class="btn btn-success btn-lg btn-block mt-4">
          <i class="fas fa-user-plus mr-2"></i> <span>@lang('pages.auth.register.create-account')</span>
        </button>
      </form>
      <hr>
      <div class="d-block text-center">
        <a href="{{ url('auth/login') }}" class="d-inline-block">
          <i class="fas fa-angle-double-right mr-2"></i> <span>@lang('pages.auth.register.back-to-login')</span>
        </a>
      </div>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
<!-- Modals -->
<!-- Terms and Conditions -->
<div class="modal fade" id="terms-and-conditions-modal" tabindex="-1" aria-labelledby="terms-and-conditions-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="terms-and-conditions-modalLabel">@lang('words.terms-and-conditions')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <div class="d-block" style="max-height: 300px; overflow-y: auto; margin-right: -1rem;">
          {!! $this->terms_and_conditions !!}
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Terms and Conditions -->
<!-- Privacy of Policy -->
<div class="modal fade" id="privacy-of-policy-modal" tabindex="-1" aria-labelledby="privacy-of-policy-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="privacy-of-policy-modalLabel">@lang('words.privacy-of-policy')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <div class="d-block" style="max-height: 300px; overflow-y: auto; margin-right: -1rem;">
          {!! $this->privacy_of_policy !!}
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Terms And Conditions -->
<!-- Modals -->
@endsection

@section('bottom-scripts')
<script>
$(function() {
  // Form
  $('#register-form').submit(function(e) {
    e.preventDefault();

    var _token = $('[name="_token"]').val();

    var username = $('[name="username"]').val();
    var email = $('[name="email"]').val();
    var password = $('[name="password"]').val();
    var terms_and_conditions;
    var privacy_of_policy;

    if($('#checkbox-terms-and-conditions').is(':checked'))
    {
      terms_and_conditions = 1;
    }
    else
    {
      terms_and_conditions = 0;
    }

    if($('#checkbox-privacy-of-policy').is(':checked'))
    {
      privacy_of_policy = 1;
    }
    else
    {
      privacy_of_policy = 0;
    }

    $.ajax({
      url: "{{ url('auth/register') }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token,
        username: username,
        email: email,
        password: password,
        terms_and_conditions: terms_and_conditions,
        privacy_of_policy: privacy_of_policy
      },
      success: (data) => {
        if(data.error == 1)
        {
          if(data.error_username != "")
          {
            $('#username-input').addClass('error');

            $('#error_username').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_username
            });
          }
          else
          {
            $('#username-input').removeClass('error');

            $('#error_username').empty();
          }

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

          if(data.error_terms_and_conditions != "")
          {
            $('#terms-and-conditions-text').addClass('text-danger');

            $('#error_terms_and_conditions').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_terms_and_conditions
            });
          }
          else
          {
            $('#terms-and-conditions-text').removeClass('text-danger');

            $('#error_terms_and_conditions').empty();
          }

          if(data.error_privacy_of_policy != "")
          {
            $('#privacy-of-policy-text').addClass('text-danger');

            $('#error_privacy_of_policy').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_privacy_of_policy
            });
          }
          else
          {
            $('#privacy-of-policy-text').removeClass('text-danger');

            $('#error_privacy_of_policy').empty();
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
              $('#username-input').removeClass('error');
              $("#error_username").empty();

              $('[name="email"]').val('');
              $('#email-input').removeClass('error');
              $("#error_email").empty();

              $('[name="password"]').val('');
              $('#password-input"]').removeClass('error');
              $("#error_password").empty();

              $("#error_terms_and_conditions").empty();

              $("#error_privacy_of_policy").empty();
            },
            onClosed: function() {
              window.location.href = "{{ url('panel/home') }}";
            }
          });
        }
      }
    });
  });

  // Modals
  $('#toggle-terms-and-conditions-modal').click(function(e) {
    e.preventDefault();

    $('#terms-and-conditions-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });

  $('#toggle-privacy-of-policy-modal').click(function(e) {
    e.preventDefault();

    $('#privacy-of-policy-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });
});
</script>
@endsection