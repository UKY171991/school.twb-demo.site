@extends('adminlte::page')

@section('title', 'Exam Timetable Details')

@section('content_header')
    <h1>Exam Timetable Details</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Timetable Entry Information</h3>
        <div class="card-tools">
            <a href="{{ route('exam-timetables.edit', $examTimetable) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('exam-timetables.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Exam Type:</strong></td>
                        <td>
                            <span class="badge badge-primary">{{ $examTimetable->examType->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Subject:</strong></td>
                        <td>{{ $examTimetable->subject->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Class:</strong></td>
                        <td>{{ $examTimetable->class }}</td>
                    </tr>
                    <tr>
                        <td><strong>Section:</strong></td>
                        <td>{{ $examTimetable->section ?? 'All Sections' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Academic Year:</strong></td>
                        <td>{{ $examTimetable->academic_year }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Exam Date:</strong></td>
                        <td>{{ $examTimetable->exam_date->format('d M Y (l)') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Start Time:</strong></td>
                        <td>{{ $examTimetable->start_time->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>End Time:</strong></td>
                        <td>{{ $examTimetable->end_time->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Duration:</strong></td>
                        <td>
                            @php
                                $duration = $examTimetable->start_time->diffInHours($examTimetable->end_time);
                                $minutes = $examTimetable->start_time->diffInMinutes($examTimetable->end_time) % 60;
                            @endphp
                            {{ $duration }} hours {{ $minutes > 0 ? $minutes . ' minutes' : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge badge-{{ $examTimetable->is_active ? 'success' : 'secondary' }}">
                                {{ $examTimetable->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($examTimetable->exam_center)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h5>Exam Center</h5>
                    <p class="text-muted">{{ $examTimetable->exam_center }}</p>
                </div>
            </div>
        @endif

        @if($examTimetable->instructions)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h5>Special Instructions</h5>
                    <div class="alert alert-info">
                        {{ $examTimetable->instructions }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row mt-4">
            <div class="col-md-12">
                <h5>Subject Information</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                                <th>Max Marks</th>
                                <th>Pass Marks</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $examTimetable->subject->name }}</td>
                                <td>{{ $examTimetable->subject->code }}</td>
                                <td>{{ $examTimetable->subject->max_marks }}</td>
                                <td>{{ $examTimetable->subject->pass_marks }}</td>
                                <td>{{ $examTimetable->subject->teacher->name ?? 'Not Assigned' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <small class="text-muted">
            Created: {{ $examTimetable->created_at->format('d M Y H:i') }} | 
            Updated: {{ $examTimetable->updated_at->format('d M Y H:i') }}
        </small>
    </div>
</div>
@stop