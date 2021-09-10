@extends('layouts.app')

@section('title') {{ __('pages.menu.home') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@php
$this->registered_users = number_format(App\User::count(), 0, '.', ' ');
$this->solved_reports = number_format(App\Report::where([['open', '=', 0], ['resolving', '=', 0]])->count(), 0, '.', ' ');
$this->unsolved_reports = number_format(App\Report::where([['open', '=', 1], ['resolving', '=', 0]])->orWhere([['open', '=', 0], ['resolving', '=', 1]])->count(), 0, '.', ' ');
$this->total_reports = number_format(App\Report::count(), 0, '.', ' ');
@endphp

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.menu.home')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.panel')</li>
          <li class="breadcrumb-item active">@lang('pages.menu.home')</li>
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
  <!-- Index Head Info Boxes -->
  <div class="row">
    <!-- Registered Users -->
    <div class="col-md-3">
      <div class="small-box bg-n-pink">
        <div class="inner">
          <h3>{{ $this->registered_users }}</h3>
          <p>@lang('words.registered-users')</p>
        </div>
        <div class="icon">
          <i class="fas fa-users"></i>
        </div>
      </div>
    </div>
    <!-- Registered Users -->
    <!-- Solved Reports -->
    <div class="col-md-3">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $this->solved_reports }}</h3>
          <p>@lang('words.solved-reports')</p>
        </div>
        <div class="icon">
          <i class="fas fa-check-circle"></i>
        </div>
      </div>
    </div>
    <!-- Solved Reports -->
    <!-- Unsolved Reports -->
    <div class="col-md-3">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{ $this->unsolved_reports }}</h3>
          <p>@lang('words.unsolved-reports')</p>
        </div>
        <div class="icon">
          <i class="fas fa-times-circle"></i>
        </div>
      </div>
    </div>
    <!-- Unsolved Reports -->
    <!-- Total Reports -->
    <div class="col-md-3">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $this->total_reports }}</h3>
          <p>@lang('words.total-reports')</p>
        </div>
        <div class="icon">
          <i class="fas fa-list-ol"></i>
        </div>
      </div>
    </div>
    <!-- Total Reports -->
  </div>
  <!-- Index Head Info Boxes -->
  <!-- Auth Logs -->
  <div class="row">
    <div class="col-md-12">
      <!-- Auth Logs Card -->
      <div class="card card-outline card-warning" auth-logs-card>
        <div class="card-header">
          <h5 class="card-title">@lang('words.auth-logs')</h5>
          <!-- Card Tools -->
          <div class="card-tools">
            @if(empty(Request::query('show_all_auth_logs')) && Request::query('show_all_auth_logs') != "true")
            <!-- Show All -->
            <a href="{{ url()->current() }}?show_all_auth_logs=true" class="btn btn-tool"><i class="fas fa-eye" data-toggle="tooltip" data-placement="left" title="@lang('words.show-all')"></i></a>
            <!-- Show All -->
            @endif
            <!-- Clear All -->
            <button type="button" class="btn btn-tool" btn-clear-all-logs><i class="fas fa-trash" data-toggle="tooltip" data-placement="left" title="@lang('words.clear-all')"></i></button>
            <!-- Clear All -->
            <!-- Collapse Button -->
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <!-- Collapse Button -->
          </div>
          <!-- Card Tools -->
        </div>
        <div class="card-body p-0">
          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-striped mb-0 text-center">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">@lang('words.id')</th>
                  <th scope="col">@lang('words.ip-address')</th>
                  <th scope="col">@lang('words.info')</th>
                  <th scope="col">@lang('words.action')</th>
                  <th scope="col">@lang('words.created-at')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($auth_logs as $item)
                @php
                $this->log_payload = !empty($item->payload) ? json_decode($item->payload) : ['info' => ['country' => 'United States of America']];

                $this->country_info = json_decode(file_get_contents('https://restcountries.eu/rest/v2/name/'.$this->log_payload->info->country));
                @endphp
                <tr>
                  <td>
                    <code class="code-alt">{{ $item->id }}</code>
                  </td>
                  <td>
                    <a href="{{ $core->setting('ip_query_provider').$item->ip_address }}" target="_blank">{{ $item->ip_address }}</a>
                  </td>
                  <td>
                    <img src="{{ $this->country_info[0]->flag }}" draggable="false" style="width: 25px; height: 14px;" />
                    <a href="{{ $core->setting('location_query_provider').$this->log_payload->info->lat.','.$this->log_payload->info->lon }}" id="location-tt-{{ $item->id }}" target="_blank">{{ $this->country_info[0]->nativeName }}, {{ $this->log_payload->info->city }}</a>

                    <!-- Tooltip Script -->
                    <script>
                    $(function() {
                      $('#location-tt-{{ $item->id }}').tooltip({
                        html: true,
                        placement: 'bottom',
                        trigger: 'hover',
                        title: 'Lat: {{ $this->log_payload->info->lat }}<br/>Lon: {{ $this->log_payload->info->lon }}'
                      });
                    });
                    </script>
                    <!-- Tooltip Script -->
                  </td>
                  <td>
                    @switch($item->action)
                      @case('login')
                        <span class="badge badge-info">@lang('words.login')</span>
                        @break
                      @case('login')
                        <span class="badge badge-success">@lang('words.register')</span>
                        @break
                      @default
                        <span class="badge badge-secondary">@lang('words.unknown')</span>
                    @endswitch
                  </td>
                  <td>
                    <span id="date-tt-{{ $item->id }}">{{ $core->dt_format($item->created_at) }}</span>

                    <!-- Tooltip Script -->
                    <script>
                    $(function() {
                      $('#date-tt-{{ $item->id }}').tooltip({
                        html: true,
                        placement: 'bottom',
                        trigger: 'hover',
                        title: '{{ Carbon::createFromTimestamp($item->created_at)->setTimezone($core->setting("site_timezone"))->diffForHumans() }}'
                      });
                    });
                    </script>
                    <!-- Tooltip Script -->
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- Table -->
        </div>
      </div>
      <!-- Auth Logs Card -->
    </div>
  </div>
  <!-- Auth Logs -->
</div>
@endsection

@section('bottom-scripts')
<script>
$(function() {
  // Auth Logs
  $('[btn-clear-all-logs]').click(function() {
    var _token = $('[name="_token"]').val();
    var _method = "DELETE";

    var user = "{{ Auth::user()->uuid }}";
    
    $.ajax({
      url: "{{ url('panel/user/account/logs/clear') }}",
      method: _method,
      dataType: "JSON",
      data: {
        _token: _token,
        _method: _method,
        user: user
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
              $('[auth-logs-card] .card-body .table > tbody').empty();
            }
          });
        }
      }
    });
  });
});
</script>
@endsection