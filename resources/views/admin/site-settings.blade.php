@extends('layouts.app')

@section('title') {{ __('pages.menu.site-settings') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('page-stylesheets')
<link rel="stylesheet" href="{{ $core->asset_url('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
@endsection

@section('head-scripts')
<script src="{{ $core->asset_url('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
@endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.menu.site-settings')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.admin')</li>
          <li class="breadcrumb-item active">@lang('pages.menu.site-settings')</li>
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
    <!-- Site Settings Container -->
    <div class="col-md-12">
      <div class="card card-n-pink card-outline">
        <div class="card-body">
          <div class="row">
            @foreach(App\Setting::all() as $item)
            <div class="col-md-6">
              <div class="card card-info card-outline">
                <div class="card-header">
                  <h5 class="card-title">{{ $item->title }}</h5>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <p class="card-text mb-1" style="font-size: 14px; cursor: default;">
                    <span style="color: #e83e8c;">{{ $item->special_id }}</span>
                    @if(!empty($item->updated_at))
                    <span class="ml-2 mr-2">|</span> <span data-toggle="tooltip" data-placement="bottom" title="{{ __('words.updated-at') }}">{{ $core->dt_format($item->updated_at) }}</span>
                    @endif
                  </p>
                  {!! !empty($item->description) ? "<p class=\"card-text text-muted mb-0\">$item->description</p>" : '' !!}
                  @switch($item->type)
                    @case('text/small')
                      <div class="form-group mt-3 mb-0">
                        <input type="text" class="form-control" name="{{ $item->special_id }}" value="{!! $item->value !!}">
                      </div>
                      @break
                    @case('text/large')
                      <div class="form-group mt-3 mb-0">
                        <textarea name="{{ $item->special_id }}" class="form-control" style="height: 100px; resize: none;">{!! $item->value !!}</textarea>
                      </div>
                      @break
                    @case('list')
                      @php
                      $item_value = "";

                      if(!empty($item->value))
                      {
                        foreach(json_decode($item->value) as $value)
                        {
                          $item_value .= $value;

                          if($value != "")
                          {
                            $item_value .= ",";
                          }
                        }
                      }
                      @endphp
                      <div class="form-group mt-3 mb-0">
                        <input type="text" class="form-control" name="{{ $item->special_id }}" value="{!! $item_value !!}">
                      </div>
                      @break
                    @case('boolean')
                      <div class="form-group mt-3 mb-0">
                        <select name="{{ $item->special_id }}" class="custom-select">
                          <option value="1"{{ $item->value == 1 ? ' selected' : '' }}>@lang('words.true')</option>
                          <option value="0"{{ $item->value == 0 ? ' selected' : '' }}>@lang('words.false')</option>
                        </select>
                      </div>
                      @break
                    @default
                    <div class="form-group mt-3 mb-0">
                      <input type="text" class="form-control" name="{{ $item->special_id }}" value="{!! $item->value !!}">
                    </div>
                  @endswitch
                </div>
              </div>
            </div>
            @endforeach
            <div class="col-md-12">
              <div class="d-block text-center">
                <button type="button" class="btn btn-success btn-lg" id="btn-save-site-settings">
                  <i class="fas fa-save mr-2"></i> <span>@lang('words.save')</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Site Settings Container -->
  </div>
</div>
<!-- /.container-fluid -->
@endsection

@section('bottom-scripts')
<script>
$(function() {
  // Tags Input
  @foreach(App\Setting::all() as $item)
    @if($item->type == "list")
    $('[name="{{ $item->special_id }}"]').tagsinput();
    @endif
  @endforeach

  // Save Form
  var _token = $('[name="_token"]').val();

  $('#btn-save-site-settings').click(function() {
    $.ajax({
      url: "{{ url('admin/site-settings/update') }}",
      method: "PUT",
      dataType: "JSON",
      data: {
        _token: _token,
        _method: "PUT",
        @foreach(App\Setting::all() as $item)
        {!! $item->special_id !!}: $('[name="{{ $item->special_id }}"]').val(),
        @endforeach
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
            transitionOut: 'fadeOutRight'
          });
        }
      }
    });
  });
});
</script>
@endsection
