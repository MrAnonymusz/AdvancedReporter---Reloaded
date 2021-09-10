@extends('layouts.app')

@section('title') {{ __('pages.menu.role-manager') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('pages.menu.role-manager')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.admin')</li>
          <li class="breadcrumb-item active">@lang('pages.menu.role-manager')</li>
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
      <div class="d-block text-right mb-3">
        <a href="{{ url('admin/role/create') }}" class="btn btn-success btn-lg">
          <i class="fas fa-plus-circle mr-2"></i> <span>@lang('words.create-role')</span>
        </a>
      </div>
      <div class="card card-dark card-outline">
        <div class="card-header">
          <h5 class="m-0">@lang('pages.menu.role-manager')</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped text-center mb-0">
              <thead class="thead-dark">
                <tr>
                  <th>@lang('words.id')</th>
                  <th>@lang('words.name')</th>
                  <th>@lang('words.enabled')</th>
                  <th>@lang('words.default')</th>
                  <th>@lang('words.permission.pl')</th>
                  <th>@lang('words.created-by')</th>
                  <th>@lang('words.created-at')</th>
                  <th>@lang('words.action-s')</th>
                </tr>
              </thead>
              <tbody>
                @if($role_list->total() > 0)
                  @foreach($role_list as $item)
                  <tr id="{{ 'role-item-'.$item->special_id }}">
                    <td><code>{{ $item->special_id }}</code></td>
                    <td>{{ $item->display_name }}</td>
                    <td>{!! $item->enabled == 1 ? '<span class="badge badge-success">'.__('words.yes').'</span>' : '<span class="badge badge-danger">'.__('words.no').'</span>' !!}</td>
                    <td>{!! $item->default == 1 ? '<span class="badge badge-success">'.__('words.yes').'</span>' : '<span class="badge badge-danger">'.__('words.no').'</span>' !!}</td>
                    <td>
                      <button type="button" class="btn btn-info btn-sm" id="btn_{{ $item->special_id }}_show_perms_modal">
                        <i class="fas fa-eye mr-2"></i> <span>@lang('words.view')</span>
                      </button>
                    </td>
                    <td>{{ App\User::where('uuid', $item->created_by)->first()->username }}</td>
                    <td>{{ $core->dt_format($item->created_at) }}</td>
                    <td>
                      @if($core->hasPermissions(['can_edit_role', 'can_remove_role'], true))
                      <div class="btn-group btn-group-sm" role="group">
                        @if($core->hasPermission('can_edit_role') == 1)
                        <a href="{{ url('admin/role/edit/'.$item->special_id) }}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="{{ __('words.edit') }}"><i class="fas fa-pen"></i></a>
                        @endif
                        @if($core->hasPermission('can_remove_role') == 1)
                        <button type="button" class="btn btn-danger" id="btn-remove-role-{{ $item->special_id }}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="{{ __('words.remove') }}"><i class="fas fa-trash"></i></button>
                        @endif
                      </div>
                      @else
                      <span>&mdash;</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
</div>
<!-- /.container-fluid -->
<!-- Modals -->
@foreach($role_list as $item)
<!-- Permissions Modal -->
<div class="modal fade" id="{{ $item->special_id }}-permissions-modal" tabindex="-1" aria-labelledby="permissions-modalLabel" aria-hidden="true">
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
@foreach($role_list as $item)
<script>
  $(function(){
    // SweetToast
    const SweetToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    // Permissions Modal
    $('#btn_{{ $item->special_id }}_show_perms_modal').click(function() {
      $('#{{ $item->special_id }}-permissions-modal').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });
    });

    @if($core->hasPermission('can_remove_role') == 1 && $item->default == 0)
    // Remove Role
    $('#btn-remove-role-{{ $item->special_id }}').click(function() {
      var _token = $('[name="_token"]').val();
      var _method = "DELETE";

      $.ajax({
        url: "{{ url('admin/role/remove/'.$item->special_id) }}",
        method: _method,
        dataType: "JSON",
        data: {
          _token: _token,
          _method: _method
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
                $('#role-item-{{ $item->special_id }}').remove();
              }
            });
          }
        }
      });
    });
    @else
    $('#btn-remove-role-{{ $item->special_id }}').click(function() {
      SweetToast.fire({
        icon: 'error',
        title: "{{ __('sentences.role-r-def-na') }}"
      });
    });
    @endif
  });
</script>
@endforeach
@endsection