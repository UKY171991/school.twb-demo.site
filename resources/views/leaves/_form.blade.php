@csrf
@if(isset($leave))
    @method('PUT')
@endif

<div class="form-group">
    <label>Student (optional)</label>
    <select name="student_id" id="student_id" class="form-control">
        <option value="">-- Select student --</option>
        @foreach($students as $s)
            <option value="{{ $s->id }}" {{ (isset($leave) && $leave->student_id == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Grade (optional)</label>
    <select name="grade_id" id="grade_id" class="form-control">
        <option value="">-- Select grade --</option>
        @foreach($grades as $g)
            <option value="{{ $g->id }}" {{ (isset($leave) && $leave->grade_id == $g->id) ? 'selected' : '' }}>{{ $g->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', optional($leave->start_date)->format('Y-m-d') ?? '') }}" required>
    </div>
    <div class="form-group col-md-6">
        <label>End Date (optional)</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', optional($leave->end_date)->format('Y-m-d') ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label>Type</label>
    <select name="type" id="type" class="form-control">
        <option value="student" {{ (isset($leave) && $leave->type=='student') ? 'selected' : '' }}>Student Leave</option>
        <option value="holiday" {{ (isset($leave) && $leave->type=='holiday') ? 'selected' : '' }}>Holiday</option>
    </select>
</div>

<div class="form-group">
    <label>Reason / Description</label>
    <textarea name="reason" id="reason" class="form-control" rows="3">{{ old('reason', $leave->reason ?? '') }}</textarea>
    </div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ isset($leave) ? 'Update' : 'Create' }}</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
</div>
