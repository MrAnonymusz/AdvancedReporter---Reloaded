@extends('layouts.app')

@section('title') {{ __('words.create-role') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

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
        <h1 class="m-0 text-dark">@lang('words.create-role')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.admin')</li>
          <li class="breadcrumb-item"><a href="{{ url('admin/role/list') }}">@lang('pages.menu.role-manager')</a></li>
          <li class="breadcrumb-item active">@lang('words.create-role')</li>
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
          <h5 class="m-0">@lang('words.create-role')</h5>
        </div>
        <div class="card-body">
          <form action="{{ url('admin/role/create') }}" method="post" id="create-role-form" autocomplete="off">
            <div class="row">
              <!-- Display Name -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.name')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-tag"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" name="display-name">
                  </div>
                  <div id="error_display_name"></div>
                </div>
              </div>
              <!-- Display Name -->
              <!-- Special ID -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper" style="margin-top: 5.5px;">@lang('words.special-id')</p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-hashtag"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" name="special-id" readonly>
                  </div>
                  <div id="error_special_id"></div>
                </div>
              </div>
              <!-- Special ID -->
              <!-- CSS Class -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.css-class')</span> <span class="fh-extra text-info">@lang('words.optional')</span>
                  </p>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-paint-brush"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" name="css-class">
                  </div>
                  <div id="error_css_name"></div>
                </div>
              </div>
              <!-- CSS Class -->
              <!-- Permissions -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.permission.pl')</span> <span class="fh-extra text-info">@lang('words.optional')</span>
                  </p>
                  <select class="custom-select" name="permissions" multiple>
                    @foreach(App\Permission::all() as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                  </select>
                  <div id="error_permissions"></div>
                </div>
              </div>
              <!-- Permissions -->
              <!-- Enabled -->
              <div class="col-md-12">
                <div class="form-group">
                  <div class="custom-control text-center custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="enabled-checkbox" checked>
                    <label class="custom-control-label" for="enabled-checkbox">@lang('sentences.should-enabled')</label>
                  </div>
                  <div id="error_enabled"></div>
                </div>
              </div>
              <!-- Enabled -->
            </div>
            <div class="d-block text-center">
              <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-plus-circle mr-2"></i> <span>@lang('words.create-role')</span>
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
  // Permissions Select
  $('[name="permissions"]').select2({
    theme: 'bootstrap4'
  });

  // Special ID
  $('[name="display-name"]').keyup(function() {
    var formatted_value = $(this).val().replace(/-|'|"| /, '_').toLowerCase();

    $('[name="special-id"]').val(formatted_value);
  });

  // Form
  $('#create-role-form').submit(function(e) {
    e.preventDefault();

    var _token = $('[name="_token"]').val();

    var display_name = $('[name="display-name"]').val();
    var special_id = $('[name="special-id"]').val();
    var css_class = $('[name="css-class"]').val();
    var permissions = $('[name="permissions"]').val();
    var enabled;

    if($('#enabled-checkbox').is(':checked'))
    {
      enabled = 1;
    }
    else
    {
      enabled = 0;
    }

    $.ajax({
      url: "{{ url('admin/role/create') }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token,
        display_name: display_name,
        special_id: special_id,
        css_class: css_class,
        permissions: permissions,
        enabled: enabled
      },
      success: (data) => {
        if(data.error == 1)
        {
          if(data.error_display_name != "")
          {
            $('[name="display-name"]').addClass('is-invalid');

            $('#error_display_name').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_display_name
            });
          }
          else
          {
            $('[name="display-name"]').removeClass('is-invalid');

            $('#error_display_name').empty();
          }

          if(data.error_special_id != "")
          {
            $('[name="special-id"]').addClass('is-invalid');

            $('#error_special_id').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_special_id
            });
          }
          else
          {
            $('[name="special-id"]').removeClass('is-invalid');

            $('#error_special_id').empty();
          }

          if(data.error_css_class != "")
          {
            $('[name="css-class"]').addClass('is-invalid');

            $('#error_css_class').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_css_class
            });
          }
          else
          {
            $('[name="css-class"]').removeClass('is-invalid');

            $('#error_css_class').empty();
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

          if(data.error_enabled != "")
          {
            $('#error_enabled').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_enabled
            });
          }
          else
          {
            $('#error_enabled').empty();
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
              $('[name="display-name"]').val('');
              $('[name="display-name"]').removeClass('is-invalid');
              $('#error_display_name').empty();

              $('[name="sepcial-id"]').val('');
              $('[name="sepcial-id"]').removeClass('is-invalid');
              $('#error_special_id').empty();

              $('[name="css-class"]').val('');
              $('[name="css-class"]').removeClass('is-invalid');
              $('#error_css_class').empty();

              $('#error_permissions').empty();

              $('#error_enabled').empty();
            },
            onClosed: function() {
              window.location.href = "{{ url('admin/role/list') }}";
            }
          });
        }
      }
    });
  });
});
</script>
@endsection