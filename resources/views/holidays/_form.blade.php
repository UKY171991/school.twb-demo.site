@csrf
@if(isset($holiday))
    @method('PUT')
@endif

<div class="form-group">
    <label>Title</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $holiday->title ?? '') }}" required>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', optional($holiday->start_date)->format('Y-m-d') ?? '') }}" required>
    </div>
    <div class="form-group col-md-6">
        <label>End Date (optional)</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', optional($holiday->end_date)->format('Y-m-d') ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $holiday->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ isset($holiday) ? 'Update' : 'Create' }}</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
</div>
