@extends('layouts.app')

@section('title', 'School Details')

@section('content')
<div class="school-details-modal">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">School Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th width="120">School Name</th>
                                    <td>{{ $school->name }}</td>
                                </tr>
                                <tr>
                                    <th>School Code</th>
                                    <td><code>{{ $school->code }}</code></td>
                                </tr>
                                <tr>
                                    <th>Principal</th>
                                    <td>{{ $school->principal_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($school->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th width="120">Phone</th>
                                    <td>{{ $school->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $school->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>
                                        @if($school->website)
                                            <a href="{{ $school->website }}" target="_blank">{{ $school->website }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td>{{ $school->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($school->address)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Address</h6>
                            <p class="text-muted small">{{ $school->address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($school->description)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Description</h6>
                            <p class="text-muted small">{{ $school->description }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Images Section --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>School Images</h6>
                            <div class="row">
                                @if($school->logo)
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h6 class="small">Logo</h6>
                                        <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="image-preview img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    </div>
                                </div>
                                @endif
                                @if($school->principal_signature)
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h6 class="small">Principal Signature</h6>
                                        <img src="{{ $school->principal_signature_url }}" alt="Principal Signature" class="image-preview img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    </div>
                                </div>
                                @endif
                                @if($school->exam_controller_signature)
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h6 class="small">Exam Controller Signature</h6>
                                        <img src="{{ $school->exam_controller_signature_url }}" alt="Exam Controller Signature" class="image-preview img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    </div>
                                </div>
                                @endif
                                @if(!$school->logo && !$school->principal_signature && !$school->exam_controller_signature)
                                <div class="col-12">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>No images uploaded for this school</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['students_count'] }}</h3>
                            <p>Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['teachers_count'] }}</h3>
                            <p>Teachers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['grades_count'] }}</h3>
                            <p>Grades/Classes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['subjects_count'] }}</h3>
                            <p>Subjects</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
