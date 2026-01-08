@csrf
@if(isset($leave))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="student_id" class="form-label">Student (optional)</label>
            <select name="student_id" id="student_id" class="form-control">
                <option value="">-- Select student --</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}" {{ (isset($leave) && $leave->student_id == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="grade_id" class="form-label">Class (optional)</label>
            <select name="grade_id" id="grade_id" class="form-control">
                <option value="">-- Select class --</option>
                @foreach($grades as $g)
                    <option value="{{ $g->id }}" {{ (isset($leave) && $leave->grade_id == $g->id) ? 'selected' : '' }}>{{ $g->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', optional($leave->start_date)->format('Y-m-d') ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="end_date" class="form-label">End Date (optional)</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', optional($leave->end_date)->format('Y-m-d') ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="type" class="form-label">Leave Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-control" required>
                <option value="">-- Select type --</option>
                <option value="student" {{ (isset($leave) && $leave->type=='student') ? 'selected' : '' }}>Student Leave</option>
                <option value="holiday" {{ (isset($leave) && $leave->type=='holiday') ? 'selected' : '' }}>Holiday</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="reason" class="form-label">Reason / Description</label>
    <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Enter reason for leave...">{{ old('reason', $leave->reason ?? '') }}</textarea>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> {{ isset($leave) ? 'Update' : 'Create' }}
    </button>
    @if(request()->ajax())
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    @else
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
    @endif
</div>
