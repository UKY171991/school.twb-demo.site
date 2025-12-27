@extends('adminlte::page')

@section('title', 'Edit Student')

@section('content_header')
    <h1>Edit Student</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Student Information</h3>
        </div>
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="Enter student name" value="{{ old('name', $student->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" placeholder="Enter email" value="{{ old('email', $student->email) }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grade_id">Grade/Class <span class="text-danger">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control @error('grade_id') is-invalid @enderror" required>
                                <option value="">Select Grade</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id', $student->grade_id) == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} @if($grade->section) - {{ $grade->section }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('grade_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" placeholder="Enter phone number" value="{{ old('phone', $student->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
                            @error('date_of_birth')
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
                                      placeholder="Enter address">{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="image-upload-section">
                            <label for="image" class="form-label">Student Photo</label>
                            
                            @if($student->image)
                                <div class="current-image-section">
                                    <span class="current-image-label">Current Photo:</span>
                                    <p><small>Path: {{ $student->image }}</small></p>
                                    <p><small>URL: {{ $student->image_url }}</small></p>
                                    <img src="{{ $student->image_url }}" alt="Current Photo" 
                                         class="image-preview"
                                         onerror="this.style.border='2px solid red'; this.alt='Image failed to load';">
                                    <div class="mt-2">
                                        <a href="{{ route('students.remove-image', $student->id) }}" 
                                           class="btn btn-sm btn-danger remove-image-btn"
                                           onclick="return confirm('Are you sure you want to remove this image?')">
                                            <i class="fas fa-trash"></i> Remove Image
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <small>No photo uploaded yet.</small>
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
                                    Upload new student photo (JPG, PNG, GIF - Max 2MB)
                                    @if($student->image) - This will replace the current image @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('students.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/image-upload.css') }}">
@stop

@section('js')
<script src="{{ asset('js/image-upload.js') }}"></script>
@stop
