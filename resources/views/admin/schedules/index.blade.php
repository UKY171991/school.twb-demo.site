@extends('layouts.admin')

@section('title', 'Class Schedules')
@section('page-title', 'Class Schedules')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Schedules</li>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $statistics['total'] }}</h3>
                <p>Total Schedules</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $statistics['active'] }}</h3>
                <p>Active Schedules</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $statistics['with_conflicts'] }}</h3>
                <p>With Conflicts</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $statistics['total_hours_per_week'] }}</h3>
                <p>Hours/Week</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Schedule Management</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('admin.schedules.create') }}'">
                        <i class="fas fa-plus"></i> Create Schedule
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-control" id="filter-class">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filter-teacher">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filter-day">
                            <option value="">All Days</option>
                            @foreach($weekDays as $day => $dayName)
                                <option value="{{ $day }}">{{ $dayName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filter-academic-year">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Weekly Schedule View -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="weekly-schedule">
                        <thead>
                            <tr>
                                <th width="100">Time</th>
                                @foreach($weekDays as $day => $dayName)
                                    <th class="text-center">{{ $dayName }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="schedule-grid">
                            <!-- Schedule grid will be populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Details Modal -->
<div class="modal fade" id="schedule-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="schedule-details">
                <!-- Schedule details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit-schedule-btn">Edit</button>
                <button type="button" class="btn btn-danger" id="delete-schedule-btn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentScheduleId = null;

    // Load schedule grid
    function loadScheduleGrid() {
        const filters = {
            class_id: $('#filter-class').val(),
            teacher_id: $('#filter-teacher').val(),
            day: $('#filter-day').val(),
            academic_year_id: $('#filter-academic-year').val()
        };

        $.ajax({
            url: '{{ route("admin.schedules.index") }}',
            type: 'GET',
            data: { ...filters, ajax: true },
            success: function(response) {
                $('#schedule-grid').html(response.grid_html);
            },
            error: function(xhr) {
                toastr.error('Failed to load schedule grid');
            }
        });
    }

    // Filter change handlers
    $('#filter-class, #filter-teacher, #filter-day, #filter-academic-year').on('change', function() {
        loadScheduleGrid();
    });

    // Show schedule details
    $(document).on('click', '.schedule-item', function() {
        const scheduleId = $(this).data('schedule-id');
        currentScheduleId = scheduleId;

        $.ajax({
            url: `/admin/schedules/${scheduleId}`,
            type: 'GET',
            success: function(response) {
                $('#schedule-details').html(response.details_html);
                $('#schedule-modal').modal('show');
            },
            error: function(xhr) {
                toastr.error('Failed to load schedule details');
            }
        });
    });

    // Edit schedule
    $('#edit-schedule-btn').on('click', function() {
        if (currentScheduleId) {
            window.location.href = `/admin/schedules/${currentScheduleId}/edit`;
        }
    });

    // Delete schedule
    $('#delete-schedule-btn').on('click', function() {
        if (currentScheduleId && confirm('Are you sure you want to delete this schedule?')) {
            $.ajax({
                url: `/admin/schedules/${currentScheduleId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#schedule-modal').modal('hide');
                    loadScheduleGrid();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response.message || 'Failed to delete schedule');
                }
            });
        }
    });

    // Initial load
    loadScheduleGrid();
});
</script>
@endpush