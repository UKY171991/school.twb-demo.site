@extends('adminlte::page')

@section('title', 'Add Teacher Salary')

@section('content_header')
<h1>Add Teacher Salary</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('teacher-salaries.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Teacher <span class="text-danger">*</span></label>
                        <select name="teacher_id" class="form-control" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Month <span class="text-danger">*</span></label>
                        <select name="salary_month" class="form-control" required>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('salary_month', date('n')) == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Year <span class="text-danger">*</span></label>
                        <select name="salary_year" class="form-control" required>
                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ old('salary_year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <hr><h5>Earnings</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Basic Salary <span class="text-danger">*</span></label>
                        <input type="number" name="basic_salary" class="form-control" value="{{ old('basic_salary', 0) }}" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>House Allowance</label>
                        <input type="number" name="house_allowance" class="form-control" value="{{ old('house_allowance', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Transport Allowance</label>
                        <input type="number" name="transport_allowance" class="form-control" value="{{ old('transport_allowance', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Medical Allowance</label>
                        <input type="number" name="medical_allowance" class="form-control" value="{{ old('medical_allowance', 0) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Other Allowance</label>
                        <input type="number" name="other_allowance" class="form-control" value="{{ old('other_allowance', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Bonus</label>
                        <input type="number" name="bonus" class="form-control" value="{{ old('bonus', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Overtime</label>
                        <input type="number" name="overtime" class="form-control" value="{{ old('overtime', 0) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <hr><h5>Deductions</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tax Deduction</label>
                        <input type="number" name="deduction_tax" class="form-control" value="{{ old('deduction_tax', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>PF Deduction</label>
                        <input type="number" name="deduction_pf" class="form-control" value="{{ old('deduction_pf', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Loan Deduction</label>
                        <input type="number" name="deduction_loan" class="form-control" value="{{ old('deduction_loan', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Other Deduction</label>
                        <input type="number" name="other_deduction" class="form-control" value="{{ old('other_deduction', 0) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <hr><h5>Payment Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Select Method</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            <a href="{{ route('teacher-salaries.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@stop
