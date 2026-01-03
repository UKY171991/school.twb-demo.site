@extends('adminlte::page')

@section('title', 'Student Fees')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Student Fees Management</h1>
    <div>
        <a href="{{ route('student-fees.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Add Fee
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
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="student_id" class="form-control form-control-sm">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('student-fees.index') }}" class="btn btn-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Month/Year</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $fee)
                <tr>
                    <td>{{ $fee->student->name ?? 'N/A' }}</td>
                    <td>{{ $fee->student->grade->name ?? 'N/A' }}</td>
                    <td>{{ $fee->month_name }} {{ $fee->fee_year }}</td>
                    <td>₹{{ number_format($fee->total_amount, 2) }}</td>
                    <td>₹{{ number_format($fee->paid_amount, 2) }}</td>
                    <td>₹{{ number_format($fee->balance, 2) }}</td>
                    <td>{!! $fee->status_badge !!}</td>
                    <td>
                        <a href="{{ route('student-fees.edit', $fee) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('student-fees.print-slip', $fee) }}" class="btn btn-info btn-xs" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                        <form action="{{ route('student-fees.destroy', $fee) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No fee records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $fees->links() }}
    </div>
</div>
@stop
