@extends('layouts.app')

@section('title') {{ __('pages.menu.user-manager') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.menu.user-manager')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.admin')</li>
          <li class="breadcrumb-item active">@lang('pages.menu.user-manager')</li>
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
      @if($core->hasPermission('can_create_user') == 1)
      <div class="d-block mb-3 text-right">
        <a href="{{ url('admin/user/create') }}" class="btn btn-success btn-lg">
          <i class="fas fa-plus-circle mr-2"></i> <span>@lang('words.create-user')</span>
        </a>
      </div>
      @endif
      <div class="card card-dark card-outline">
        <div class="card-header">
          <h5 class="card-title">@lang('words.user-list')</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table text-center mb-0">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>@lang('words.username')</th>
                  <th>@lang('words.email')</th>
                  <th>@lang('words.role')</th>
                  <th>@lang('words.timezone')</th>
                  <th>@lang('words.permission.pl')</th>
                  <th>@lang('words.ip-address')</th>
                  <th>@lang('words.registered-at')</th>
                  <th>@lang('words.action-s')</th>
                </tr>
              </thead>
              <tbody>
                @if($user_list->total() > 0)
                  @foreach($user_list as $key => $item)
                  <tr id="user-item-{{ $item->uuid }}">
                    <th>{{ $key += 1 }}</th>
                    <td>
                      <img src="{{ $core->avatar_url($item->uuid) }}" class="avatar-img-ul" draggable="false"/>
                      <span>
                        <span>{{ $item->username }}</span>
                        @if($item->is_active == 0)
                        <i class="fas fa-exclamation-triangle text-danger ml-2" data-toggle="tooltip" data-placement="bottom" title="{{ __('sentences.user-deactivated') }}"></i>
                        @endif
                      </span>
                    </td>
                    <td>{{ $item->email }}</td>
                    <td>
                      <span class="role {{ $core->role($item->role)->css_class }}">{{ $core->role($item->role)->display_name }}</span>
                    </td>
                    <td>{{ $item->timezone }}</td>
                    <td>
                      <button type="button" class="btn btn-info btn-sm" id="btn_{{ $item->uuid }}_show_perms_modal">
                        <i class="fas fa-eye mr-2"></i> <span>@lang('words.view')</span>
                      </button>
                    </td>
                    <td>
                      @if(!empty($item->ip_address))
                      <a href="{{ $core->setting('ip_query_provider').$item->ip_address }}" target="_blank">{{ $item->ip_address }}</a>
                      @else
                      <span>N/A</span>
                      @endif
                    </td>
                    <td>{{ $core->dt_format($item->created_at) }}</td>
                    <td>
                      @if($core->hasPermissions(['can_edit_user', 'can_remove_user'], true))
                      <div class="btn-group btn-group-sm" role="group">
                        @if($core->hasPermission('can_edit_user') == 1)
                        <a href="{{ url('admin/user/edit/'.$item->uuid) }}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="{{ __('words.edit') }}"><i class="fas fa-pen"></i></a>
                        @endif
                        @if($core->hasPermission('can_remove_user') == 1)
                        <button type="button" class="btn btn-danger" id="btn-remove-user-{{ $item->uuid }}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="{{ __('words.remove') }}"><i class="fas fa-trash"></i></button>
                        @endif
                      </div>
                      @else
                      <span>&mdash;</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                @else
                <tr>
                  <td colspan="9">
                    <span class="text-bold text-muted">@lang('sentences.no-users')</span>
                  </td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      @if($user_list->total() > 10)
        <div class="d-flex justify-content-center">
          {{ $user_list->links() }}
        </div>
      @endif
    </div>
  </div>
  <!-- /.row -->
</div>
<!-- /.container-fluid -->
<!-- Modals -->
@foreach($user_list as $item)
<!-- Permissions Modal -->
<div class="modal fade" id="{{ $item->uuid }}-permissions-modal" tabindex="-1" aria-labelledby="permissions-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="permissions-modalLabel">@lang('words.permission.pl')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <i class="far fa-times-circle fa-xs text-danger"></i>
          </span>
        </button>
      </div>
      <div class="modal-body">
        @php
        $this->raw_permission_list = array_merge(json_decode($core->role($item->role)->permissions), json_decode($item->permissions));
        $this->permission_list = [];

        foreach($this->raw_permission_list as $sitem)
        {
          if(!empty($sitem))
          {
            $this->permission_list[] = $sitem;
          }
        }
        @endphp
        @if(!empty($this->permission_list[0]))
        <ul class="m-0 p-0" style="list-style: none; display: block;">
          @foreach($this->permission_list as $sitem)
          <li style="display: inline-block; margin-right: 7.5px;">
            <span class="badge badge-n-pink">{{ $sitem }}</span>
          </li>
          @endforeach
        </ul>
        @else
        <span class="bg-n-pink d-block text-center p-3 rounded">@lang('words.no-permissions')</span>
        @endif
      </div>
    </div>
  </div>
</div>
<!-- Permissions Modal -->
@endforeach
<!-- Modals -->
@endsection

@section('bottom-scripts')
@foreach($user_list as $item)
<script>
$(function(){
  // Permissions Modal
  $('#btn_{{ $item->uuid }}_show_perms_modal').click(function() {
    $('#{{ $item->uuid }}-permissions-modal').modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  });

  @if($core->hasPermission('can_remove_user') == 1)
  // Delete User
  $('#btn-remove-user-{{ $item->uuid }}').click(function() {
    var _token = $('[name="_token"]').val();

    $.ajax({
      url: "{{ url('admin/user/remove/'.$item->uuid) }}",
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
            onOpening: function() {
              $('#user-item-{{ $item->uuid }}').remove();
            }
          });
        }
      }
    });
  });
  @endif
});
</script>
@endforeach
@endsection
