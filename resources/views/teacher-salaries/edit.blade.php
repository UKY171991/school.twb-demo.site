@extends('adminlte::page')

@section('title', 'Edit Teacher Salary')

@section('content_header')
<h1>Edit Teacher Salary - {{ $teacherSalary->teacher->name ?? 'N/A' }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('teacher-salaries.update', $teacherSalary) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Teacher:</strong> {{ $teacherSalary->teacher->name ?? 'N/A' }} | 
                <strong>Month:</strong> {{ $teacherSalary->month_name }} {{ $teacherSalary->salary_year }}
            </div>

            <h5>Earnings</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Basic Salary <span class="text-danger">*</span></label>
                        <input type="number" name="basic_salary" class="form-control" value="{{ old('basic_salary', $teacherSalary->basic_salary) }}" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>House Allowance</label>
                        <input type="number" name="house_allowance" class="form-control" value="{{ old('house_allowance', $teacherSalary->house_allowance) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Transport Allowance</label>
                        <input type="number" name="transport_allowance" class="form-control" value="{{ old('transport_allowance', $teacherSalary->transport_allowance) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Medical Allowance</label>
                        <input type="number" name="medical_allowance" class="form-control" value="{{ old('medical_allowance', $teacherSalary->medical_allowance) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Other Allowance</label>
                        <input type="number" name="other_allowance" class="form-control" value="{{ old('other_allowance', $teacherSalary->other_allowance) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Bonus</label>
                        <input type="number" name="bonus" class="form-control" value="{{ old('bonus', $teacherSalary->bonus) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Overtime</label>
                        <input type="number" name="overtime" class="form-control" value="{{ old('overtime', $teacherSalary->overtime) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <hr><h5>Deductions</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tax Deduction</label>
                        <input type="number" name="deduction_tax" class="form-control" value="{{ old('deduction_tax', $teacherSalary->deduction_tax) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>PF Deduction</label>
                        <input type="number" name="deduction_pf" class="form-control" value="{{ old('deduction_pf', $teacherSalary->deduction_pf) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Loan Deduction</label>
                        <input type="number" name="deduction_loan" class="form-control" value="{{ old('deduction_loan', $teacherSalary->deduction_loan) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Other Deduction</label>
                        <input type="number" name="other_deduction" class="form-control" value="{{ old('other_deduction', $teacherSalary->other_deduction) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <hr><h5>Payment Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ old('status', $teacherSalary->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status', $teacherSalary->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ old('status', $teacherSalary->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $teacherSalary->payment_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Select Method</option>
                            <option value="bank" {{ old('payment_method', $teacherSalary->payment_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ old('payment_method', $teacherSalary->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="cheque" {{ old('payment_method', $teacherSalary->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control" value="{{ old('remarks', $teacherSalary->remarks) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('teacher-salaries.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@stop
