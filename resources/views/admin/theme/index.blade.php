@extends('layouts.adminlte')

@section('title', 'Theme Settings')
@section('page-title', 'Theme Settings')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Theme</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-palette mr-2"></i>Customize Theme</h3>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primary_color">Primary Color</label>
                                <input type="color" name="primary_color" id="primary_color" class="form-control" value="#007bff">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="secondary_color">Secondary Color</label>
                                <input type="color" name="secondary_color" id="secondary_color" class="form-control" value="#6c757d">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sidebar_color">Sidebar Color</label>
                                <select name="sidebar_color" id="sidebar_color" class="form-control">
                                    <option value="dark">Dark</option>
                                    <option value="light">Light</option>
                                    <option value="primary">Primary</option>
                                    <option value="success">Success</option>
                                    <option value="info">Info</option>
                                    <option value="warning">Warning</option>
                                    <option value="danger">Danger</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="navbar_color">Navbar Color</label>
                                <select name="navbar_color" id="navbar_color" class="form-control">
                                    <option value="white">White</option>
                                    <option value="dark">Dark</option>
                                    <option value="primary">Primary</option>
                                    <option value="success">Success</option>
                                    <option value="info">Info</option>
                                    <option value="warning">Warning</option>
                                    <option value="danger">Danger</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="logo">School Logo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="logo" id="logo" class="custom-file-input">
                                        <label class="custom-file-label" for="logo">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                    <button type="reset" class="btn btn-default">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

