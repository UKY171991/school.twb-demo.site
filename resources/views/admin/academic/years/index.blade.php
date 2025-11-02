@extends('layouts.admin')

@section('title', 'Academic Years')
@section('page-title', 'Academic Year Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Academic Years</li>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $statistics['total'] }}</h3>
                <p>Total Years</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $statistics['active'] }}</h3>
                <p>Active Years</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $statistics['current'] }}</h3>
                <p>Current Year</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $statistics['upcoming'] }}</h3>
                <p>Upcoming Years</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>
</div>

@if($currentYear)
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Current Academic Year: {{ $currentYear->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-calendar-day"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Current Semester</span>
                                <span class="info-box-number">{{ $currentYear->current_semester }} / {{ $currentYear->total_semesters }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-percentage"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Progress</span>
                                <span class="info-box-number">{{ $currentYear->progress_percentage }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Students</span>
                                <span class="info-box-number">{{ $statistics['current_year_info']['total_students'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Days Remaining</span>
                                <span class="info-box-number">{{ $statistics['current_year_info']['days_remaining'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning" id="progress-semester-btn" data-year-id="{{ $currentYear->id }}">
                            <i class="fas fa-forward"></i> Progress to Next Semester
                        </button>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.academic.years.show', $currentYear) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Academic Years</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="add-year-btn">
                        <i class="fas fa-plus"></i> Add Academic Year
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Duration</th>
                                <th>Semester</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($academicYears as $year)
                            <tr>
                                <td>
                                    <strong>{{ $year->name }}</strong>
                                    @if($year->description)
                                        <br><small class="text-muted">{{ $year->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $year->start_date->format('M d, Y') }} - {{ $year->end_date->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $year->duration_days }} days</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $year->current_semester }} / {{ $year->total_semesters }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $year->progress_percentage }}%">
                                            {{ $year->progress_percentage }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{!! $year->status_badge !!}</td>
                                <td>{{ $year->students()->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.academic.years.show', $year) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning edit-year-btn" data-year-id="{{ $year->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(!$year->is_current)
                                            <button type="button" class="btn btn-sm btn-success activate-year-btn" data-year-id="{{ $year->id }}">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        @endif
                                        @if($year->canBeDeleted())
                                            <button type="button" class="btn btn-sm btn-danger delete-year-btn" data-year-id="{{ $year->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Academic Year Modal -->
<div class="modal fade" id="year-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="year-modal-label">Add Academic Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="year-form">
                @csrf
                <input type="hidden" name="_method" id="year-form-method" value="POST">
                <input type="hidden" name="id" id="year-id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Academic Year Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="e.g., 2024-2025">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_semesters">Total Semesters *</label>
                                <select class="form-control" id="total_semesters" name="total_semesters" required>
                                    <option value="1">1 Semester</option>
                                    <option value="2" selected>2 Semesters</option>
                                    <option value="3">3 Semesters</option>
                                    <option value="4">4 Semesters</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">End Date *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Optional description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-year-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add academic year
    $('#add-year-btn').on('click', function() {
        $('#year-form')[0].reset();
        $('#year-modal-label').text('Add Academic Year');
        $('#year-form-method').val('POST');
        $('#year-form').attr('action', '{{ route("admin.academic.years.store") }}');
        $('#year-modal').modal('show');
    });

    // Edit academic year
    $('.edit-year-btn').on('click', function() {
        const yearId = $(this).data('year-id');
        
        $.get(`/admin/academic/years/${yearId}`, function(data) {
            $('#year-modal-label').text('Edit Academic Year');
            $('#year-form-method').val('PUT');
            $('#year-form').attr('action', `/admin/academic/years/${yearId}`);
            $('#year-id').val(data.id);
            $('#name').val(data.name);
            $('#start_date').val(data.start_date);
            $('#end_date').val(data.end_date);
            $('#total_semesters').val(data.total_semesters);
            $('#description').val(data.description);
            $('#year-modal').modal('show');
        });
    });

    // Save academic year
    $('#save-year-btn').on('click', function() {
        const form = $('#year-form');
        const url = form.attr('action');
        const method = $('#year-form-method').val();
        const data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(response) {
                if (response.success !== false) {
                    $('#year-modal').modal('hide');
                    location.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response.errors) {
                    let errorMessages = '';
                    $.each(response.errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    toastr.error(errorMessages);
                } else {
                    toastr.error(response.message || 'An error occurred.');
                }
            }
        });
    });

    // Activate academic year
    $('.activate-year-btn').on('click', function() {
        const yearId = $(this).data('year-id');
        
        if (confirm('Are you sure you want to activate this academic year? This will deactivate all other academic years.')) {
            $.ajax({
                url: `/admin/academic/years/${yearId}/activate`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response.message || 'Failed to activate academic year');
                }
            });
        }
    });

    // Progress semester
    $('#progress-semester-btn').on('click', function() {
        const yearId = $(this).data('year-id');
        
        if (confirm('Are you sure you want to progress to the next semester? This will automatically enroll all active students.')) {
            $.ajax({
                url: `/admin/academic/years/${yearId}/progress`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response.message || 'Failed to progress semester');
                }
            });
        }
    });

    // Delete academic year
    $('.delete-year-btn').on('click', function() {
        const yearId = $(this).data('year-id');
        
        if (confirm('Are you sure you want to delete this academic year? This action cannot be undone.')) {
            $.ajax({
                url: `/admin/academic/years/${yearId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response.message || 'Failed to delete academic year');
                }
            });
        }
    });
});
</script>
@endpush