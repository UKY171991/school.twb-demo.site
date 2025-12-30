@extends('adminlte::page')

@section('title', 'Add Teacher')

@section('content_header')
    <h1><i class="fas fa-chalkboard-teacher"></i> Add New Teacher</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-plus"></i> Teacher Information</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data" id="teacherForm">
            @csrf
            <div class="card-body">
                <!-- School Selection -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="school_id" class="form-label">
                                <i class="fas fa-school"></i> School <span class="text-danger">*</span>
                            </label>
                            <select name="school_id" id="school_id" class="form-control @error('school_id') is-invalid @enderror" required>
                                <option value="">Select School</option>
                                @if(isset($schools))
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('school_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" placeholder="Enter teacher's full name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" placeholder="teacher@school.com" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" placeholder="+1234567890" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gender" class="form-label">
                                <i class="fas fa-venus-mars"></i> Gender <span class="text-danger">*</span>
                            </label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                    <i class="fas fa-mars"></i> Male
                                </option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                    <i class="fas fa-venus"></i> Female
                                </option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                                    <i class="fas fa-genderless"></i> Other
                                </option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">
                                <i class="fas fa-birthday-cake"></i> Date of Birth
                            </label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_joining" class="form-label">
                                <i class="fas fa-calendar-alt"></i> Date of Joining
                            </label>
                            <input type="date" name="date_of_joining" class="form-control @error('date_of_joining') is-invalid @enderror" 
                                   id="date_of_joining" value="{{ old('date_of_joining') ?? date('Y-m-d') }}">
                            @error('date_of_joining')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <textarea name="address" id="address" rows="1" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      placeholder="Enter complete address">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="image" class="form-label">
                                <i class="fas fa-camera"></i> Teacher Photo
                            </label>
                            <div class="custom-file-upload">
                                <div class="upload-area" id="uploadArea">
                                    <div class="upload-content">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h6>Click to upload or drag and drop</h6>
                                        <p class="text-muted small">PNG, JPG, GIF up to 2MB</p>
                                        <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" 
                                               id="image" accept="image/*" style="display: none;">
                                    </div>
                                    <div class="preview-area" id="previewArea" style="display: none;">
                                        <img id="imagePreview" src="" alt="Preview" class="img-thumbnail">
                                        <div class="preview-info">
                                            <h6 id="fileName"></h6>
                                            <button type="button" class="btn btn-sm btn-danger" id="removeImage">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Save Teacher
                </button>
                <a href="{{ route('teachers.index') }}" class="btn btn-default">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="reset" class="btn btn-warning">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
            </div>
        </form>
    </div>
@stop


