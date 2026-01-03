@extends('adminlte::page')

@section('title', 'Edit School')

@section('content_header')
    <h1>Edit School</h1>
@stop

@section('css')
    <style>
        .image-preview {
            max-width: 150px;
            max-height: 100px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            background: #f8f9fa;
        }
        .image-upload-section {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
        }
        .current-image-section {
            text-align: center;
            margin-bottom: 10px;
        }
        .current-image-label {
            display: block;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 8px;
        }
    </style>
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
                            <label for="address"><i class="fas fa-map-marker-alt mr-1"></i> Official School Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      placeholder="Enter school address" style="border-left: 4px solid #4e73df;">{{ old('address', $school->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row d-flex align-items-stretch mb-4">
                    <div class="col-md-4">
                        <div class="image-upload-section h-100">
                            <label for="logo" class="form-label">School Logo</label>
                            
                            @if($school->logo)
                                <div class="current-image-section">
                                    <span class="current-image-label">Current Logo:</span>
                                    <img src="{{ $school->logo_url }}" alt="Current Logo" class="image-preview">
                                    <div class="mt-2 text-center">
                                        <a href="{{ route('schools.remove-logo', $school->id) }}" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to remove this logo?')">
                                            <i class="fas fa-trash"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info py-2">
                                    <small><i class="fas fa-info-circle mr-1"></i> No logo uploaded yet.</small>
                                </div>
                            @endif
                            
                            <div class="image-upload-input">
                                <input type="file" name="logo" class="form-control-file @error('logo') is-invalid @enderror" 
                                       id="logo" accept="image/*">
                                @error('logo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="image-upload-section h-100">
                            <label for="principal_signature" class="form-label">Principal Signature</label>
                            
                            @if($school->principal_signature)
                                <div class="current-image-section">
                                    <span class="current-image-label">Current Signature:</span>
                                    <img src="{{ $school->principal_signature_url }}" alt="Principal Signature" class="image-preview">
                                    <div class="mt-2 text-center">
                                        <a href="{{ route('schools.remove-principal-signature', $school->id) }}" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to remove this signature?')">
                                            <i class="fas fa-trash"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info py-2">
                                    <small><i class="fas fa-info-circle mr-1"></i> No signature uploaded yet.</small>
                                </div>
                            @endif
                            
                            <div class="image-upload-input">
                                <input type="file" name="principal_signature" class="form-control-file @error('principal_signature') is-invalid @enderror" 
                                       id="principal_signature" accept="image/*">
                                @error('principal_signature')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="image-upload-section h-100">
                            <label for="exam_controller_signature" class="form-label">Exam Controller Signature</label>
                            
                            @if($school->exam_controller_signature)
                                <div class="current-image-section">
                                    <span class="current-image-label">Current Signature:</span>
                                    <img src="{{ $school->exam_controller_signature_url }}" alt="Exam Controller Signature" class="image-preview">
                                    <div class="mt-2 text-center">
                                        <a href="{{ route('schools.remove-exam-controller-signature', $school->id) }}" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to remove this signature?')">
                                            <i class="fas fa-trash"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info py-2">
                                    <small><i class="fas fa-info-circle mr-1"></i> No signature uploaded yet.</small>
                                </div>
                            @endif
                            
                            <div class="image-upload-input">
                                <input type="file" name="exam_controller_signature" class="form-control-file @error('exam_controller_signature') is-invalid @enderror" 
                                       id="exam_controller_signature" accept="image/*">
                                @error('exam_controller_signature')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
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

@section('js')
    <!-- Custom JS already included in layout -->
@stop