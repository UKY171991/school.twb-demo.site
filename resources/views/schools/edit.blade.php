@extends('adminlte::page')

@section('title', 'Edit School')

@section('content_header')
    <h1>Edit School</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">School Information</h3>
        </div>
        <form action="{{ route('schools.update', $school) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">School Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="Enter school name" value="{{ old('name', $school->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">School Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" placeholder="Enter unique school code" value="{{ old('code', $school->code) }}" required>
                            @error('code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Unique identifier for the school (e.g., ABC001)</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="principal_name">Principal Name</label>
                            <input type="text" name="principal_name" class="form-control @error('principal_name') is-invalid @enderror" 
                                   id="principal_name" placeholder="Enter principal name" value="{{ old('principal_name', $school->principal_name) }}">
                            @error('principal_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', $school->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $school->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" placeholder="Enter phone number" value="{{ old('phone', $school->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" placeholder="Enter email address" value="{{ old('email', $school->email) }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" placeholder="Enter website URL" value="{{ old('website', $school->website) }}">
                            @error('website')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      placeholder="Enter school address">{{ old('address', $school->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="logo">School Logo</label>
                            
                            @if($school->logo)
                                <div class="mb-2">
                                    <img src="{{ $school->getImageUrl($school->logo) }}" alt="Current Logo" 
                                         class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    <div class="mt-2">
                                        <a href="{{ route('schools.remove-logo', $school->id) }}" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to remove this logo?')">
                                            <i class="fas fa-trash"></i> Remove Logo
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" name="logo" class="form-control-file @error('logo') is-invalid @enderror" 
                                   id="logo" accept="image/*">
                            @error('logo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Upload new school logo (JPG, PNG, GIF - Max 2MB)
                                @if($school->logo) - This will replace the current logo @endif
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Enter school description">{{ old('description', $school->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update School
                </button>
                <a href="{{ route('schools.show', $school) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
@stop