@extends('adminlte::page')

@section('title', 'Teacher Salaries')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Teacher Salary Management</h1>
    <div>
        <a href="{{ route('teacher-salaries.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Add Salary
        </a>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="row">
            <div class="col-md-2">
                <select name="month" class="form-control form-control-sm">
                    <option value="">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="year" class="form-control form-control-sm">
                    <option value="">All Years</option>
                    @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control form-control-sm">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="teacher_id" class="form-control form-control-sm">
                    <option value="">All Teachers</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('teacher-salaries.index') }}" class="btn btn-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Month/Year</th>
                    <th>Gross</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $salary)
                <tr>
                    <td>{{ $salary->teacher->name ?? 'N/A' }}</td>
                    <td>{{ $salary->month_name }} {{ $salary->salary_year }}</td>
                    <td>₹{{ number_format($salary->gross_salary, 2) }}</td>
                    <td>₹{{ number_format($salary->total_deductions, 2) }}</td>
                    <td>₹{{ number_format($salary->net_salary, 2) }}</td>
                    <td>{!! $salary->status_badge !!}</td>
                    <td>
                        <a href="{{ route('teacher-salaries.edit', $salary) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('teacher-salaries.print-slip', $salary) }}" class="btn btn-info btn-xs" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                        <form action="{{ route('teacher-salaries.destroy', $salary) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No salary records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $salaries->links() }}</div>
</div>
@stop
