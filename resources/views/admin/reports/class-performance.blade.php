@extends('layouts.admin')

@section('title', 'Class Performance Reports - Admin Dashboard')

@section('page-title', 'Class Performance Reports')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
<li class="breadcrumb-item active">Class Performance</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Class Performance Overview
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="classFilter">Filter by Class</label>
                            <select class="form-control select2" id="classFilter" style="width: 100%;">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="subjectFilter">Filter by Subject</label>
                            <select class="form-control select2" id="subjectFilter" style="width: 100%;">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dateRange">Date Range</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" id="dateRange">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <canvas id="classPerformanceChart" style="height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-table mr-2"></i>
                    Detailed Performance Data
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" id="exportDataBtn">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="performanceTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Average Score</th>
                            <th>Pass Rate</th>
                            <th>Total Students</th>
                            <th>Top Performer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize date range picker
    $('#dateRange').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        startDate: moment().subtract(30, 'days'),
        endDate: moment()
    });
    
    // Initialize Select2
    $('.select2').select2();
    
    // Initialize DataTable
    const table = $('#performanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.reports.class-performance.data") }}',
            data: function(d) {
                d.class_id = $('#classFilter').val();
                d.subject_id = $('#subjectFilter').val();
                d.date_range = $('#dateRange').val();
            }
        },
        columns: [
            { data: 'class_name', name: 'class_name' },
            { data: 'subject_name', name: 'subject_name' },
            { data: 'teacher_name', name: 'teacher_name' },
            { data: 'average_score', name: 'average_score' },
            { data: 'pass_rate', name: 'pass_rate' },
            { data: 'total_students', name: 'total_students' },
            { data: 'top_performer', name: 'top_performer' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']]
    });
    
    // Reload table when filters change
    $('#classFilter, #subjectFilter').on('change', function() {
        table.ajax.reload();
    });
    
    $('#dateRange').on('apply.daterangepicker', function() {
        table.ajax.reload();
    });
    
    // Initialize chart
    initClassPerformanceChart();
    
    // Export data
    $('#exportDataBtn').on('click', function() {
        const classId = $('#classFilter').val();
        const subjectId = $('#subjectFilter').val();
        const dateRange = $('#dateRange').val();
        
        window.location.href = '{{ route("admin.reports.class-performance.export") }}' + 
            '?class_id=' + classId + 
            '&subject_id=' + subjectId + 
            '&date_range=' + dateRange;
    });
});

function initClassPerformanceChart() {
    const ctx = document.getElementById('classPerformanceChart').getContext('2d');
    
    // Sample data - in real implementation, this would come from AJAX
    const data = {
        labels: ['Class A', 'Class B', 'Class C', 'Class D', 'Class E'],
        datasets: [{
            label: 'Average Score',
            data: [85, 78, 92, 88, 76],
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'Pass Rate (%)',
            data: [92, 85, 96, 90, 82],
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };
    
    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Class Performance Comparison'
                }
            }
        }
    };
    
    new Chart(ctx, config);
}
</script>
@endpush