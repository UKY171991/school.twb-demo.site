@extends('adminlte::page')

@section('title', 'Edit Teacher')

@section('content_header')
    <h1>Edit Teacher</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Teacher Information</h3>
        </div>
        <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="Enter teacher name" value="{{ old('name', $teacher->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" placeholder="Enter email" value="{{ old('email', $teacher->email) }}" required>
                            @error('email')
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
                                   id="phone" placeholder="Enter phone number" value="{{ old('phone', $teacher->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" value="{{ old('date_of_birth', $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '') }}">
                            @error('date_of_birth')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_joining">Date of Joining</label>
                            <input type="date" name="date_of_joining" class="form-control @error('date_of_joining') is-invalid @enderror" 
                                   id="date_of_joining" value="{{ old('date_of_joining', $teacher->date_of_joining ? $teacher->date_of_joining->format('Y-m-d') : '') }}">
                            @error('date_of_joining')
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
                                      placeholder="Enter address">{{ old('address', $teacher->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="image-upload-section">
                            <label for="image" class="form-label">Teacher Photo</label>
                            
                            @if($teacher->image)
                                <div class="current-image-section">
                                    <span class="current-image-label">Current Photo:</span>
                                    <img src="{{ $teacher->getImageUrl($teacher->image) }}" alt="Current Photo" 
                                         class="image-preview">
                                    <div class="mt-2">
                                        <a href="{{ route('teachers.remove-image', $teacher->id) }}" 
                                           class="btn btn-sm btn-danger remove-image-btn"
                                           onclick="return confirm('Are you sure you want to remove this image?')">
                                            <i class="fas fa-trash"></i> Remove Image
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="image-upload-input">
                                <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                                <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" 
                                       id="image" accept="image/*">
                                @error('image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <div class="image-upload-help">
                                    Upload new teacher photo (JPG, PNG, GIF - Max 2MB)
                                    @if($teacher->image) - This will replace the current image @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('teachers.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/image-upload.css') }}">
@stop

@section('js')
@stop
