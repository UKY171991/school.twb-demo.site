@extends('adminlte::page')

@section('title', 'Edit Student Fee')

@section('content_header')
<h1>Edit Student Fee - {{ $studentFee->student->name ?? 'N/A' }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('student-fees.update', $studentFee) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Student:</strong> {{ $studentFee->student->name ?? 'N/A' }} | 
                <strong>Month:</strong> {{ $studentFee->month_name }} {{ $studentFee->fee_year }}
            </div>

            <h5>Fee Components</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tuition Fee</label>
                        <input type="number" name="tuition_fee" class="form-control" value="{{ old('tuition_fee', $studentFee->tuition_fee) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Admission Fee</label>
                        <input type="number" name="admission_fee" class="form-control" value="{{ old('admission_fee', $studentFee->admission_fee) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Exam Fee</label>
                        <input type="number" name="exam_fee" class="form-control" value="{{ old('exam_fee', $studentFee->exam_fee) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Transport Fee</label>
                        <input type="number" name="transport_fee" class="form-control" value="{{ old('transport_fee', $studentFee->transport_fee) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Library Fee</label>
                        <input type="number" name="library_fee" class="form-control" value="{{ old('library_fee', $studentFee->library_fee) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sports Fee</label>
                        <input type="number" name="sports_fee" class="form-control" value="{{ old('sports_fee', $studentFee->sports_fee) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Computer Fee</label>
                        <input type="number" name="computer_fee" class="form-control" value="{{ old('computer_fee', $studentFee->computer_fee) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Other Fee</label>
                        <input type="number" name="other_fee" class="form-control" value="{{ old('other_fee', $studentFee->other_fee) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <hr><h5>Adjustments & Payment</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Discount</label>
                        <input type="number" name="discount" class="form-control" value="{{ old('discount', $studentFee->discount) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fine</label>
                        <input type="number" name="fine" class="form-control" value="{{ old('fine', $studentFee->fine) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Paid Amount</label>
                        <input type="number" name="paid_amount" class="form-control" value="{{ old('paid_amount', $studentFee->paid_amount) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $studentFee->payment_date?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Select Method</option>
                            <option value="cash" {{ old('payment_method', $studentFee->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ old('payment_method', $studentFee->payment_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="upi" {{ old('payment_method', $studentFee->payment_method) == 'upi' ? 'selected' : '' }}>UPI</option>
                            <option value="cheque" {{ old('payment_method', $studentFee->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control" value="{{ old('remarks', $studentFee->remarks) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@stop
