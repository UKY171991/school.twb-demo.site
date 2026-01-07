@extends('layouts.app')

@section('title', 'Edit User')

@section('content_header')
    <h1><i class="fas fa-user-edit"></i> Edit User</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User Information</h3>
        </div>
        <form action="{{ route('users.update', $user_to_edit) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="Enter user name" value="{{ old('name', $user_to_edit->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" placeholder="Enter email address" value="{{ old('email', $user_to_edit->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" placeholder="Enter new password (optional)">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" 
                                   id="password_confirmation" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Role <span class="text-danger">*</span></label>
                            @if($user_to_edit->role === 'master')
                                <div class="alert alert-info">
                                    <i class="fas fa-crown"></i> 
                                    <strong>Current Role: Master</strong><br>
                                    <small class="text-muted">Master user roles cannot be changed through the interface for security reasons.</small>
                                </div>
                                <input type="hidden" name="role" value="master" />
                            @else
                                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="">Select Role</option>
                                    @if(Auth::user()->role == 'master')
                                        <option value="admin" {{ old('role', $user_to_edit->role) == 'admin' ? 'selected' : '' }}>
                                            <i class="fas fa-user-shield"></i> Admin
                                        </option>
                                    @endif
                                    <option value="user" {{ old('role', $user_to_edit->role) == 'user' ? 'selected' : '' }}>
                                        <i class="fas fa-user"></i> User
                                    </option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    @if(Auth::user()->role == 'master')
                                        Master users can modify Admin and User roles.
                                    @else
                                        You can only assign User roles.
                                    @endif
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
@stop
