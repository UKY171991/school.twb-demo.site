@extends('layouts.adminlte')

@section('title', 'Media Gallery')
@section('page-title', 'Media Gallery')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Media Gallery</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-images mr-2"></i>Media Gallery</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload mr-1"></i> Upload Media
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="position-relative">
                            <img src="{{ asset('adminlte/img/photo1.png') }}" alt="Photo" class="img-fluid">
                            <div class="ribbon-wrapper ribbon-sm">
                                <div class="ribbon bg-success">New</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <img src="{{ asset('adminlte/img/photo2.png') }}" alt="Photo" class="img-fluid">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{ asset('adminlte/img/photo3.jpg') }}" alt="Photo" class="img-fluid">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{ asset('adminlte/img/photo4.jpg') }}" alt="Photo" class="img-fluid">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{ asset('adminlte/img/photo1.png') }}" alt="Photo" class="img-fluid">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{ asset('adminlte/img/photo2.png') }}" alt="Photo" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

