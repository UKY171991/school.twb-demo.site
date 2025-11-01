<div class="form-group">
    <label for="name">School Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $school->name ?? '') }}" required>
</div>
<div class="form-group">
    <label for="address">Address</label>
    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $school->address ?? '') }}</textarea>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $school->phone ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $school->email ?? '') }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="principal_name">Principal Name</label>
            <input type="text" class="form-control" id="principal_name" name="principal_name" value="{{ old('principal_name', $school->principal_name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="established_year">Established Year</label>
            <input type="number" class="form-control" id="established_year" name="established_year" value="{{ old('established_year', $school->established_year ?? '') }}" required>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $school->description ?? '') }}</textarea>
</div>
<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', isset($school) && $school->is_active) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active Status</label>
    </div>
</div>
