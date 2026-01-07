@extends('layouts.app')

@section('title', 'User Details')

@section('content_header')
    <h1><i class="fas fa-user"></i> User Details</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $user_to_show->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.edit', $user_to_show) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">Name</th>
                                    <td>{{ $user_to_show->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user_to_show->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">Role</th>
                                    <td>
                                        @if($user_to_show->role == 'master')
                                            <span class="badge badge-danger">Master</span>
                                        @elseif($user_to_show->role == 'admin')
                                            <span class="badge badge-warning">Admin</span>
                                        @else
                                            <span class="badge badge-info">User</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $user_to_show->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
