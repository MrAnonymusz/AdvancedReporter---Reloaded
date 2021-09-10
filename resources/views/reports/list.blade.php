@extends('layouts.app')

@section('title') {{ __('pages.reports.list.title') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.reports.list.title')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.panel')</li>
          <li class="breadcrumb-item active">@lang('pages.reports.list.title')</li>
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
      <!-- Report List Head Buttons -->
      <div class="report-list-head-buttons">
        <div class="d-inline-block">
          @if($page != 'report-list-my-reports')
          <a href="{{ url('panel/report/list/my-reports') }}" class="btn btn-info">
            <i class="fas fa-list-alt mr-2"></i> @lang('words.my-reports')
          </a>
          @endif
          <button type="button" class="btn btn-success" id="btn-create-spreadsheet">
            <i class="fas fa-plus mr-2"></i> @lang('words.create-spreadsheet')
          </button>
        </div>
        @if($core->hasPermission('can_create_report'))
        <a href="{{ url('panel/report/create') }}" class="btn btn-success">
          <i class="fas fa-plus-circle mr-2"></i> @lang('pages.reports.general.create-report')
        </a>
        @endif
      </div>
      <!-- Report List Head Buttons -->
      <!-- Report List Card -->
      <div class="card card-n-pink report-list-card">
        <div class="card-header">
          <h5 class="card-title">@lang('pages.reports.list.title')</h5>
          <!-- Card Tools -->
          <div class="card-tools">
            <button type="button" class="btn btn-tool text-white">
              <span style="text-decoration: underline;">@lang('words.total'):</span> <span>{{ number_format($reports->total(), 0, '.', ' ') }}</span>
            </button>
          </div>
          <!-- Card Tools -->
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
                  <th>@lang('pages.reports.general.ticket-manager')</th>
                  <th>@lang('pages.reports.general.how-resolved')</th>
                  <th>@lang('pages.reports.general.server-name')</th>
                  <th>@lang('words.action')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reports as $item)
                @php
                if($item->resolving == 1 && $item->ticketManager == $user->username)
                {
                  $report_cell_class = 'class="table-warning"';
                }
                else if($item->resolving == 0 && $item->ticketManager != "none")
                {
                  $report_cell_class = 'class="table-success"';
                }
                else
                {
                  $report_cell_class = '';
                }
                @endphp
                <tr {!! $report_cell_class !!}>
                  <th>{{ $item->id }}</th>
                  <td>{{ $item->reported }}</td>
                  <td>{{ $item->reporter }}</td>
                  <td>
                    <button type="button" class="btn btn-info btn-xs" id="{{ $item->id }}-toggle-reason-modal">
                      <i class="fas fa-eye mr-1"></i> @lang('words.view')
                    </button>
                  </td>
                  <td>
                    <span class="rl-coordinates-highlight" id="{{ $item->id }}-coordinates-popover">{{ $item->world }}</span>
                  </td>
                  <td>{{ $item->section }}</td>
                  <td>{{ $item->subSection }}</td>
                  <td>
                    @if($item->resolving == 0 && $item->open == 1)
                    <span class="badge badge-success">@lang('words.open')</span>
                    @elseif($item->resolving == 1 && $item->open == 0)
                    <span class="badge badge-warning">@lang('words.resolving')</span>
                    @elseif($item->resolving == 0 && $item->open == 0)
                    <span class="badge badge-danger">@lang('words.resolved')</span>
                    @endif
                  </td>
                  <td>{{ $item->ticketManager }}</td>
                  <td>
                    @if($item->howResolved != "none")
                    <button type="button" class="btn btn-info btn-xs" id="{{ $item->id }}-toggle-how-resolved-modal">
                      <i class="fas fa-eye mr-1"></i> @lang('words.view')
                    </button>
                    @else
                    &mdash;
                    @endif
                  </td>
                  <td>{{ $item->serverName }}</td>
                  <td>
                    @if($core->hasPermissions(['can_take_report', 'can_edit_report', 'can_remove_report'], true))
                    <div class="btn-group btn-group-sm" role="group">
                      @if($core->hasPermission('can_take_report'))
                        @if($item->ticketManager == "none" && $item->resolving == 0)
                        <button type="button" class="btn btn-success" id="{{ $item->id }}-btn-take-report" title="@lang('pages.reports.general.take-report')">
                          <i class="far fa-check-circle"></i>
                        </button>
                        @elseif($item->resolving == 1 && $item->open == 0)
                        <a href="{{ url('panel/report/resolve/'.$item->id) }}" class="btn btn-success" title="@lang('words.view')">
                          <i class="fas fa-eye"></i>
                        </a>
                        @endif
                      @endif
                      @if($core->hasPermission('can_edit_report'))
                      <a href="{{ url('panel/report/edit/'.$item->id) }}" class="btn btn-info" title="@lang('words.edit')">
                        <i class="fas fa-pen"></i>
                      </a>
                      @endif
                      @if($core->hasPermission('can_remove_report'))
                      <button type="button" class="btn btn-danger" id="{{ $item->id }}-btn-remove-report" title="@lang('words.remove')">
                        <i class="fas fa-trash"></i>
                      </button>
                      @endif
                    </div>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Report List Card -->
      @if($reports->total() > 20)
      <!-- Report List Links -->
      <div class="d-flex justify-content-center">
        {{ $reports->links() }}
      </div>
      <!-- Report List Links -->
      @endif
    </div>
  </div>
</div>
<!-- Modals -->
@foreach($reports as $item)
<!-- Reason Modal ({{ $item->id }}) -->
<div class="modal fade" id="{{ $item->id }}-reason-modal" tabindex="-1" aria-labelledby="{{ $item->id }}-reason-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $item->id }}-reason-modalLabel">@lang('pages.reports.general.reason')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <textarea class="form-control" style="height: 100px; resize: none;" readonly>{{ $item->reason }}</textarea>
      </div>
    </div>
  </div>
</div>
<!-- Reason Modal ({{ $item->id }}) -->
@if($item->howResolved != "none")
<!-- "How Resolved" Modal ({{ $item->id }}) -->
<div class="modal fade" id="{{ $item->id }}-how-resolved-modal" tabindex="-1" aria-labelledby="{{ $item->id }}-how-resolved-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $item->id }}-how-resolved-modalLabel">@lang('pages.reports.general.how-resolved')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <textarea class="form-control" style="height: 100px; resize: none;" readonly>{{ $item->howResolved }}</textarea>
      </div>
    </div>
  </div>
</div>
<!-- "How Resolved" Modal ({{ $item->id }}) -->
@endif
@endforeach
<!-- Modals -->
@endsection

@section('bottom-scripts')
<script>
$(function() {
  // Collapse Sidebar
  $('body').addClass('sidebar-collapse');
});
</script>

<!-- Create Spreadsheet -->
<script>
$(function() {
  $('#btn-create-spreadsheet').click(function() {
    var _token = $('[name="_token"]').val();

    $.ajax({
      url: "{{ url('panel/report/spreadsheet/create') }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token,
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
          window.location.href = data.download_link;
        }
      }
    });
  });
});
</script>
<!-- Create Spreadsheet -->

@foreach($reports as $item)
<!-- Reason Modal & Coordinates Popover -->
<script>
$(function() {
  // Reason Modal
  $('#{{ $item->id }}-toggle-reason-modal').click(function() {
    $('#{{ $item->id }}-reason-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });

  // Coordinates Popover
  $('#{{ $item->id }}-coordinates-popover').popover({
    html: true,
    placement: 'right',
    trigger: 'click',
    title: '{{ __("words.coordinate.pl") }}',
    content: 'X: <u>{{ round($item->x) }}</u><br/>Y: <u>{{ round($item->y) }}</u><br/>Z: <u>{{ round($item->z) }}</u>'
  });
});
</script>
<!-- Reason Modal & Coordinates Popover -->
@if($item->howResolved != "none")
<!-- "How Resolved" Modal -->
<script>
$(function() {
  $('#{{ $item->id }}-toggle-how-resolved-modal').click(function() {
    $('#{{ $item->id }}-how-resolved-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });
});
</script>
<!-- "How Resolved" Modal -->
@endif
@if($core->hasPermission('can_take_report') && $item->resolving == 0 && $item->ticketManager == "none")
<!-- Take Report -->
<script>
$(function() {
  $('#{{ $item->id }}-btn-take-report').click(function() {
    var _token = $('[name="_token"]').val();

    $.ajax({
      url: "{{ url('panel/report/take/'.$item->id) }}",
      method: "POST",
      dataType: "JSON",
      data: {
        _token: _token
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
          Swal.fire({
            icon: 'success',
            showCloseButton: false,
            showCancelButton: false,
            confirmButtonColor: '#28a745',
            title: '{{ __("words.success") }}!',
            text: '{{ __("sentences.report-success-taken") }}',
            confirmButtonText: '<i class="fas fa-thumbs-up mr-2"></i> {{ __("words.okay") }}',
            onClose: () => {
              window.location.href = "{{ url('panel/report/resolve/'.$item->id) }}";
            }
          });
        }
      }
    });
  });
});
</script>
<!-- Take Report -->
@endif
@if($core->hasPermission('can_remove_report'))
<!-- Remove Report -->
<script>
$(function() {
  var _token = $('[name="_token"]').val();

  $('#{{ $item->id }}-btn-remove-report').click(function() {
    Swal.fire({
      icon: 'question',
      showCloseButton: true,
      showCancelButton: false,
      confirmButtonColor: '#dc3545',
      title: '{{ __("words.just-a-second") }}&hellip;',
      text: '{{ __("sentences.report-remove-text") }}',
      confirmButtonText: '<i class="fas fa-thumbs-up mr-2"></i> {{ __("words.remove") }}',
      preConfirm: () => {
        $.ajax({
          url: "{{ url('panel/report/remove/'.$item->id) }}",
          method: "DELETE",
          dataType: "JSON",
          data: {
            _token: _token,
            _method: "DELETE"
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
                onClosed: function() {
                  window.location.href = "";
                }
              });
            }
          }
        });
      }
    });
  });
});
</script>
<!-- Remove Report -->
@endif
@endforeach
@endsection