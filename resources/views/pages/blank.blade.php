@extends('layouts.app')

@section('title') {{ __('words.blank') }} @endsection

@section('meta-description') {{ $core->setting('site_meta_description') }} @endsection
@section('meta-image') {{ $core->asset_url($core->setting('site_meta_image')) }} @endsection

@section('page-breadcrumb')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@lang('words.blank')</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">@lang('words.panel')</li>
          <li class="breadcrumb-item active">@lang('words.blank')</li>
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
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">
            Some quick example text to build on the card title and make up the bulk of the card's
            content.
          </p>
          <a href="javascript:;" class="card-link">Card link</a>
          <a href="javascript:;" class="card-link">Another link</a>
        </div>
      </div>
      <div class="card card-n-pink card-outline">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">
            Some quick example text to build on the card title and make up the bulk of the card's
            content.
          </p>
          <a href="javascript:;" class="card-link">Card link</a>
          <a href="javascript:;" class="card-link">Another link</a>
        </div>
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col-md-6 -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <h5 class="m-0">Featured</h5>
        </div>
        <div class="card-body">
          <h6 class="card-title">Special title treatment</h6>
          <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
          <a href="javascript:;" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
      <div class="card card-dark card-outline">
        <div class="card-header">
          <h5 class="m-0">Featured</h5>
        </div>
        <div class="card-body">
          <h6 class="card-title">Special title treatment</h6>
          <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
          <a href="javascript:;" class="btn btn-dark">Go somewhere</a>
        </div>
      </div>
    </div>
    <!-- /.col-md-6 -->
  </div>
  <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection
