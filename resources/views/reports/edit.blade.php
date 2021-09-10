@extends('layouts.app')

@section('title') {{ __('pages.reports.edit.title') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.reports.edit.title')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.panel')</li>
          <li class="breadcrumb-item"><a href="{{ url('panel/report/list') }}">@lang('pages.reports.list.title')</a></li>
          <li class="breadcrumb-item active">@lang('pages.reports.edit.title')</li>
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
<div class="container-fluid">
  <div class="row">
    <!-- Edit Report -->
    <div class="col-md-12">
      <div class="card card-n-pink card-outline">
        <div class="card-body">
          <form action="{{ url('panel/report/edit/'.$report->id) }}" id="edit-report-form" method="post">
            <div class="row">
              <!-- Input - Reported -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.reported')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <input type="text" class="form-control" name="reported" value="{{ $report->reported }}">
                  <div id="error_reported"></div>
                </div>
              </div>
              <!-- Input - Reported -->
              <!-- Input - Reporter -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.reporter')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <input type="text" class="form-control" name="reporter" value="{{ $report->reporter }}">
                  <div id="error_reporter"></div>
                </div>
              </div>
              <!-- Input - Reporter -->
              <!-- Input - World -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.world')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <input type="text" class="form-control" name="world" value="{{ $report->world }}">
                  <div id="error_world"></div>
                </div>
              </div>
              <!-- Input - World -->
              <!-- Input - Coordinates (X, Y, Z) -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.coordinate.pl')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">X:</span>
                        </div>
                        <input type="text" class="form-control" name="x" value="{{ round($report->x) }}">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Y:</span>
                        </div>
                        <input type="text" class="form-control" name="y" value="{{ round($report->y) }}">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Z:</span>
                        </div>
                        <input type="text" class="form-control" name="z" value="{{ round($report->z) }}">
                      </div>
                    </div>
                  </div>
                  <div id="error_coordinates"></div>
                </div>
              </div>
              <!-- Input - Coordinates (X, Y, Z) -->
              <!-- Input - Section -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.section')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <input type="text" class="form-control" name="section" value="{{ $report->section }}">
                  <div id="error_section"></div>
                </div>
              </div>
              <!-- Input - Section -->
              <!-- Input - Sub Section -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.sub-section')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <input type="text" class="form-control" name="sub-section" value="{{ $report->subSection }}">
                  <div id="error_sub_section"></div>
                </div>
              </div>
              <!-- Input - Sub Section -->
              <!-- Input - Resolving -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.resolving')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <select class="custom-select" name="resolving">
                    <option value="false" {{ $report->resolving == 0 ? 'selected' : '' }}>@lang('words.no')</option>
                    <option value="true" {{ $report->resolving == 1 ? 'selected' : '' }}>@lang('words.yes')</option>
                  </select>
                  <div id="error_resolving"></div>
                </div>
              </div>
              <!-- Input - Resolving -->
              <!-- Input - Open -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('words.open')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <select class="custom-select" name="open">
                    <option value="false" {{ $report->open == 0 ? 'selected' : '' }}>@lang('words.no')</option>
                    <option value="true" {{ $report->open == 1 ? 'selected' : '' }}>@lang('words.yes')</option>
                  </select>
                  <div id="error_open"></div>
                </div>
              </div>
              <!-- Input - Open -->
              <!-- Input - Ticket Manager -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.ticket-manager')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <input type="text" class="form-control" name="ticket-manager" value="{{ $report->ticketManager }}">
                  <div id="error_ticket_manager"></div>
                </div>
              </div>
              <!-- Input - Ticket Manager -->
              <!-- Input - Server Name -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.server-name')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <input type="text" class="form-control" name="server-name" value="{{ $report->serverName }}">
                  <div id="error_server_name"></div>
                </div>
              </div>
              <!-- Input - Server Name -->
              <!-- Input - Reason -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.reason')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <textarea class="form-control" name="reason" style="height: 90px; resize: none;" maxlength="200">{{ $report->reason }}</textarea>
                  <p class="word-counter mt-2" id="word-counter-01">
                    <span id="w-current">{{ strlen($report->reason) }}</span>/<span id="w-total">200</span>
                  </p>
                  <div id="error_reason"></div>
                </div>
              </div>
              <!-- Input - Reason -->
              <!-- Input - How Resolved -->
              <div class="col-md-6">
                <div class="form-group">
                  <p class="form-helper">
                    <span>@lang('pages.reports.general.how-resolved')</span> <span class="fh-extra text-danger">@lang('words.required')</span>
                  </p>
                  <textarea class="form-control" name="how-resolved" style="height: 90px; resize: none;" maxlength="200">{{ $report->howResolved }}</textarea>
                  <p class="word-counter mt-2" id="word-counter-02">
                    <span id="w-current">{{ strlen($report->howResolved) }}</span>/<span id="w-total">200</span>
                  </p>
                  <div id="error_how_resolved"></div>
                </div>
              </div>
              <!-- Input - How Resolved -->
            </div>
            <div class="d-block text-right mt-3">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-save mr-2"></i> @lang('words.save')
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Edit Report -->
  </div>
  <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection

@section('bottom-scripts')
<script>
$(function() {
  // Edit Report Form
  $('#edit-report-form').submit(function(e) {
    e.preventDefault();

    var _token = $('[name="_token"]').val();

    var reported = $('[name="reported"]').val();
    var reporter = $('[name="reporter"]').val();
    var world = $('[name="world"]').val();
    var x = $('[name="x"]').val();
    var y = $('[name="y"]').val();
    var z = $('[name="z"]').val();
    var section = $('[name="section"]').val();
    var sub_section = $('[name="sub-section"]').val();
    var resolving = $('[name="resolving"]').val();
    var open = $('[name="open"]').val();
    var ticket_manager = $('[name="ticket-manager"]').val();
    var server_name = $('[name="server-name"]').val();
    var reason = $('[name="reason"]').val();
    var how_resolved = $('[name="how-resolved"]').val();

    $.ajax({
      url: "{{ url('panel/report/edit/'.$report->id) }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token,
        reported: reported,
        reporter: reporter,
        world: world,
        x: x,
        y: y,
        z: z,
        section: section,
        sub_section: sub_section,
        resolving: resolving,
        open: open,
        ticket_manager: ticket_manager,
        server_name: server_name,
        reason: reason,
        how_resolved: how_resolved
      },
      success: (data) => {
        if(data.error == 1)
        {
          // Reported
          if(data.error_reported != "")
          {
            $('[name="reported"]').addClass('error');

            $('#error_reported').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_reported
            });
          }
          else
          {
            $('[name="reported"]').removeClass('error');

            $('#error_reported').empty();
          }

          // Reporter
          if(data.error_reporter != "")
          {
            $('[name="reporter"]').addClass('error');

            $('#error_reporter').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_reporter
            });
          }
          else
          {
            $('[name="reporter"]').removeClass('error');

            $('#error_reporter').empty();
          }

          // World
          if(data.error_world != "")
          {
            $('[name="world"]').addClass('error');

            $('#error_world').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_world
            });
          }
          else
          {
            $('[name="world"]').removeClass('error');

            $('#error_world').empty();
          }

          // Coordinates
          if(data.error_coordinates != "")
          {
            $('#error_coordinates').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_coordinates
            });
          }
          else
          {
            $('#error_coordinates').empty();
          }

          // Section
          if(data.error_section != "")
          {
            $('[name="section"]').addClass('error');

            $('#error_section').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_section
            });
          }
          else
          {
            $('[name="section"]').removeClass('error');

            $('#error_section').empty();
          }

          // Sub-Section
          if(data.error_sub_section != "")
          {
            $('[name="sub-section"]').addClass('error');

            $('#error_sub_section').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_sub_section
            });
          }
          else
          {
            $('[name="sub-section"]').removeClass('error');

            $('#error_sub_section').empty();
          }

          // Resolving
          if(data.error_resolving != "")
          {
            $('[name="resolving"]').addClass('error');

            $('#error_resolving').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_resolving
            });
          }
          else
          {
            $('[name="resolving"]').removeClass('error');

            $('#error_resolving').empty();
          }

          // Open
          if(data.error_open != "")
          {
            $('[name="open"]').addClass('error');

            $('#error_open').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_open
            });
          }
          else
          {
            $('[name="open"]').removeClass('error');

            $('#error_open').empty();
          }

          // Ticket Manager
          if(data.error_ticket_manager != "")
          {
            $('[name="ticket-manager"]').addClass('error');

            $('#error_ticket_manager').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_ticket_manager
            });
          }
          else
          {
            $('#ticket_manager').removeClass('error');

            $('#error_ticket_manager').empty();
          }

          // Server Name
          if(data.error_server_name != "")
          {
            $('[name="server-name"]').addClass('error');

            $('#error_server_name').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_server_name
            });
          }
          else
          {
            $('[name="server-name"]').removeClass('error');

            $('#error_server_name').empty();
          }

          // Reason
          if(data.error_reason != "")
          {
            $('[name="reason"]').addClass('error');

            $('#error_reason').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_reason
            });
          }
          else
          {
            $('[name="reason"]').removeClass('error');

            $('#error_reason').empty();
          }

          // How Resolved
          if(data.error_how_resolved != "")
          {
            $('[name="how-resolved"]').addClass('error');

            $('#error_how_resolved').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-3",
              text: data.error_how_resolved
            });
          }
          else
          {
            $('[name="how-resolved"]').removeClass('error');

            $('#error_how_resolved').empty();
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
              $('[name="reported"]').removeClass('error');
              $("#error_reported").empty();

              $('[name="reporter"]').removeClass('error');
              $("#error_reporter").empty();

              $('[name="world"]').removeClass('error');
              $('#error_world').empty();

              $('#error_coordinates').empty();

              $('[name="section"]').removeClass('error');
              $('#error_section').empty();

              $('[name="section"]').removeClass('error');
              $('#error_section').empty();

              $('[name="sub-section"]').removeClass('error');
              $('#error_sub_section').empty();

              $('[name="resolving"]').removeClass('error');
              $('#error_resolving').empty();

              $('[name="open"]').removeClass('error');
              $('#error_open').empty();

              $('[name="ticket-manager"]').removeClass('error');
              $('#error_ticket_manager').empty();

              $('[name="server-name"]').removeClass('error');
              $('#error_server_name').empty();

              $('[name="reason"]').removeClass('error');
              $('#error_reason').empty();

              $('[name="how-resolved"]').removeClass('error');
              $('#error_how_resolved').empty();
            },
            onClosed: function() {
              window.location.href = "{{ url('panel/report/list') }}";
            }
          });
        }
      }
    });
  });
  
  // Word Counter(s)
  $('#word-counter-01').wordCounter({
    textarea: '[name="reason"]'
  });

  $('#word-counter-02').wordCounter({
    textarea: '[name="how-resolved"]'
  });
});
</script>
@endsection