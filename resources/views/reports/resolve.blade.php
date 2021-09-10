@extends('layouts.app')

@section('title') {{ __('pages.reports.resolve.title') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.reports.resolve.title')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.panel')</li>
          <li class="breadcrumb-item"><a href="{{ url('panel/report/list') }}">@lang('pages.reports.list.title')</a></li>
          <li class="breadcrumb-item active">@lang('pages.reports.resolve.title')</li>
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
    <div class="col-md-12">
      <!-- Report Card -->
      <div class="card card-n-pink report-list-card">
        <div class="card-header">
          <h5 class="card-title">@lang('words.info')</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0 text-center">
              <thead>
                <tr>
                  <th>@lang('words.id')</th>
                  <th>@lang('pages.reports.general.reported')</th>
                  <th>@lang('pages.reports.general.reporter')</th>
                  <th>@lang('pages.reports.general.reason')</th>
                  <th>@lang('pages.reports.general.world')</th>
                  <th>@lang('pages.reports.general.section')</th>
                  <th>@lang('pages.reports.general.sub-section')</th>
                  <th>@lang('pages.reports.general.status')</th>
                  <th>@lang('pages.reports.general.server-name')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th>{{ $report->id }}</th>
                  <td>{{ $report->reported }}</td>
                  <td>{{ $report->reporter }}</td>
                  <td>
                    <button type="button" class="btn btn-info btn-xs" id="report-toggle-reason-modal">
                      <i class="fas fa-eye mr-1"></i> @lang('words.view')
                    </button>
                  </td>
                  <td>
                    <span class="rl-coordinates-highlight" id="report-coordinates-popover">{{ $report->world }}</span>
                  </td>
                  <td>{{ $report->section }}</td>
                  <td>{{ $report->subSection }}</td>
                  <td>
                    @if($report->resolving == 0 && $report->open == 1)
                    <span class="badge badge-success">@lang('words.open')</span>
                    @elseif($report->resolving == 1 && $report->open == 0)
                    <span class="badge badge-warning">@lang('words.resolving')</span>
                    @elseif($report->resolving == 0 && $report->open == 0)
                    <span class="badge badge-danger">@lang('words.closed')</span>
                    @endif
                  </td>
                  <td>{{ $report->serverName }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Report Card -->
      <!-- Resolve Report Card -->
      <div class="col-md-12">
        <div class="card card-success">
          <div class="card-header">
            <h5 class="card-title">@lang('pages.reports.resolve.title')</h5>
          </div>
          <div class="card-body">
            <form action="{{ url('panel/report/resolve/'.$report->id) }}" method="post" id="resolve-form" autocomplete="off">
              <div class="form-group mb-0">
                <textarea class="form-control" name="how-resolved-input" placeholder="{{ __('pages.reports.general.how-resolved') }}" maxlength="200" style="height: 90px; resize: none;"></textarea>
                <p class="word-counter mt-2" id="word-counter">
                  <span id="w-current">0</span>/<span id="w-total">200</span>
                </p>
                <div id="error_how_resolved_input"></div>
              </div>
              <div class="d-block text-right">
                <button type="submit" class="btn btn-success mt-3">
                  <i class="fas fa-check-circle mr-2"></i> @lang('words.submit')
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Resolve Report Card -->
    </div>
  </div>
</div>
<!-- Modals -->
<!-- Reason Modal -->
<div class="modal fade" id="report-reason-modal" tabindex="-1" aria-labelledby="report-reason-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="report-reason-modalLabel">@lang('pages.reports.general.reason')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <textarea class="form-control" style="height: 100px; resize: none;" readonly>{{ $report->reason }}</textarea>
      </div>
    </div>
  </div>
</div>
<!-- Reason Modal -->
<!-- Modals -->
@endsection

@section('bottom-scripts')
<!-- Resolve Form & Word Counter -->
<script>
$(function() {
  // Resolve Form
  $('#resolve-form').submit(function(e) {
    e.preventDefault();

    var _token = $('[name="_token"]').val();

    var how_resolved = $('[name="how-resolved-input"]').val();

    $.ajax({
      url: "{{ url('panel/report/resolve/'.$report->id) }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token,
        how_resolved: how_resolved
      },
      success: (data) => {
        if(data.error == 1)
        {
          if(data.error_how_resolved != "")
          {
            $('[name="how-resolved-input"]').addClass('is-invaid');

            $('#error_how_resolved_input').generateAlert({
              icon: "fas fa-exclamation-circle",
              css_class: "custom-alert-danger mt-2 mb-0",
              text: data.error_how_resolved
            });
          }
          else
          {
            $('[name="how-resolved-input"]').removeClass('is-invaid');

            $('#error_how_resolved_input').empty();
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
              $('[name="how-resolved-input"]').val('');
              $('[name="how-resolved-input"]').removeClass('is-invalid');
              $('#error_how_resolved_input').empty();
            },
            onClosed: function() {
              window.location.href = "{{ url('panel/report/list') }}";
            }
          });
        }
      }
    });
  });

  // Word Counter
  $('#word-counter').wordCounter({
    textarea: '[name="how-resolved-input"]'
  });
});
</script>
<!-- Resolve Form -->
<!-- Reason Modal & Coordinates Popover -->
<script>
$(function() {
  // Reason Modal
  $('#report-toggle-reason-modal').click(function() {
    $('#report-reason-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });

  // Coordinates Popover
  $('#report-coordinates-popover').popover({
    html: true,
    placement: 'right',
    trigger: 'click',
    title: '{{ __("words.coordinate.pl") }}',
    content: 'X: <u>{{ $report->x }}</u><br/>Y: <u>{{ $report->y }}</u><br/>Z: <u>{{ $report->z }}</u>'
  });
});
</script>
<!-- Reason Modal & Coordinates Popover -->
@if(!empty($report->answer))
<!-- "How Resolved" Modal -->
<script>
$(function() {
  $('#report-toggle-how-resolved-modal').click(function() {
    $('#report-how-resolved-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });
});
</script>
<!-- "How Resolved" Modal -->
@endif
@endsection