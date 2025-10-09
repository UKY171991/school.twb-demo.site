@extends('layouts.adminlte')

@section('title', 'Language Settings')
@section('page-title', 'Language Settings')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Language</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-language mr-2"></i>Available Languages</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addLanguageModal">
                        <i class="fas fa-plus mr-1"></i> Add Language
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Language</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Default</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>English</strong></td>
                            <td>en</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td><span class="badge badge-primary">Default</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

