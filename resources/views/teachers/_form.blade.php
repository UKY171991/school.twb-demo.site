@csrf
<input type="hidden" name="current_school_id" value="{{ request()->get('current_school_id') }}">
<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       id="name" placeholder="Enter teacher name" value="{{ old('name', $teacher->name ?? '') }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" placeholder="Enter email" value="{{ old('email', $teacher->email ?? '') }}" required>
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
                       id="phone" placeholder="Enter phone number" value="{{ old('phone', $teacher->phone ?? '') }}">
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
                    <option value="male" {{ old('gender', $teacher->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $teacher->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $teacher->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
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
                       id="date_of_birth" value="{{ old('date_of_birth', isset($teacher) && $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '') }}">
                @error('date_of_birth')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="date_of_joining">Date of Joining</label>
                <input type="date" name="date_of_joining" class="form-control @error('date_of_joining') is-invalid @enderror" 
                       id="date_of_joining" value="{{ old('date_of_joining', isset($teacher) && $teacher->date_of_joining ? $teacher->date_of_joining->format('Y-m-d') : '') }}">
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
                          placeholder="Enter address">{{ old('address', $teacher->address ?? '') }}</textarea>
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" 
                       id="designation" placeholder="e.g. Senior Math Teacher" value="{{ old('designation', $teacher->designation ?? '') }}">
                @error('designation')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
            <div class="image-upload-section">
                <label for="image" class="form-label">Teacher Photo</label>
                
                @if(isset($teacher) && $teacher->image)
                    <div class="current-image-section">
                        <span class="current-image-label">Current Photo:</span>
                        <img src="{{ $teacher->image_url }}" alt="Current Photo" 
                             class="image-preview"
                             onerror="this.style.border='2px solid red'; this.alt='Image failed to load';">
                        <div class="mt-2">
                            <a href="{{ route('teachers.remove-image', $teacher->id) }}" 
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
                        Upload new teacher photo (JPG, PNG, GIF - Max 2MB)
                        @if(isset($teacher) && $teacher->image) - This will replace the current image @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="image-upload-section">
                <label for="signature" class="form-label">Teacher Signature</label>
                
                @if(isset($teacher) && $teacher->signature)
                    <div class="current-image-section">
                        <span class="current-image-label">Current Signature:</span>
                        <img src="{{ $teacher->signature_url }}" alt="Current Signature" 
                             class="image-preview"
                             style="max-height: 80px; width: auto;"
                             onerror="this.style.border='2px solid red'; this.alt='Image failed to load';">
                        <div class="mt-2">
                            <a href="{{ route('teachers.remove-signature', $teacher->id) }}" 
                               class="btn btn-sm btn-danger remove-image-btn"
                               onclick="return confirm('Are you sure you want to remove this signature?')">
                                <i class="fas fa-trash"></i> Remove Signature
                            </a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <small>No signature uploaded yet.</small>
                    </div>
                @endif
                
                <div class="image-upload-input">
                    <i class="fas fa-file-signature fa-2x text-muted mb-2"></i>
                    <input type="file" name="signature" class="form-control-file @error('signature') is-invalid @enderror" 
                           id="signature" accept="image/*">
                    @error('signature')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <div class="image-upload-help">
                        Upload signature scan (JPG, PNG - Max 2MB)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">@isset($teacher) Update @else Create @endisset</button>
    <a href="{{ route('teachers.index') }}" class="btn btn-default">Cancel</a>
</div>
