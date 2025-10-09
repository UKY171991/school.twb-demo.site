@extends('layouts.adminlte')

@section('title', 'Edit School')
@section('page-title', 'Edit School')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('superadmin.schools.index') }}">Schools</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('sidebar')
<x-adminlte-superadmin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Edit School Information</h3>
            </div>
            <form action="{{ route('superadmin.schools.update', $school) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <!-- School Name -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">School Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $school->name) }}" required
                                       class="form-control @error('name') is-invalid @enderror" placeholder="Enter school name">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- School Code -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">School Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" value="{{ old('code', $school->code) }}" required
                                       class="form-control @error('code') is-invalid @enderror" placeholder="Enter school code">
                                @error('code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $school->email) }}"
                                       class="form-control @error('email') is-invalid @enderror" placeholder="Enter email">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $school->phone) }}"
                                       class="form-control @error('phone') is-invalid @enderror" placeholder="Enter phone number">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Website -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" name="website" id="website" value="{{ old('website', $school->website) }}"
                                       class="form-control @error('website') is-invalid @enderror" placeholder="Enter website URL">
                                @error('website')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" rows="3"
                                          class="form-control @error('address') is-invalid @enderror" placeholder="Enter address">{{ old('address', $school->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror" placeholder="Enter description">{{ old('description', $school->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Is Active -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $school->is_active) ? 'checked' : '' }}
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="is_active">School is Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i> Update School
                    </button>
                    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-default">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
