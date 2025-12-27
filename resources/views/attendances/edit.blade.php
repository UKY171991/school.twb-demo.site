@extends('adminlte::page')

@section('title', 'Edit Attendance')

@section('content_header')
    <h1>Edit Attendance</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Update Attendance Record</h3>
        </div>
        <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" class="form-control" value="{{ $attendance->student->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                                <option value="excused" {{ old('status', $attendance->status) == 'excused' ? 'selected' : '' }}>Excused</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="note">Note</label>
                            <input type="text" name="note" id="note" class="form-control @error('note') is-invalid @enderror" 
                                   value="{{ old('note', $attendance->note) }}" placeholder="Optional note">
                            @error('note')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('attendances.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
