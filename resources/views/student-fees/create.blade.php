@extends('adminlte::page')

@section('title', 'Add Student Fee')

@section('content_header')
<h1>Add Student Fee</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('student-fees.store') }}" method="POST">
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
                        <label>Student <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-control" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->grade->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Month <span class="text-danger">*</span></label>
                        <select name="fee_month" class="form-control" required>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('fee_month', date('n')) == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Year <span class="text-danger">*</span></label>
                        <select name="fee_year" class="form-control" required>
                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ old('fee_year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <hr><h5>Fee Components</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tuition Fee</label>
                        <input type="number" name="tuition_fee" class="form-control" value="{{ old('tuition_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Admission Fee</label>
                        <input type="number" name="admission_fee" class="form-control" value="{{ old('admission_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Exam Fee</label>
                        <input type="number" name="exam_fee" class="form-control" value="{{ old('exam_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Transport Fee</label>
                        <input type="number" name="transport_fee" class="form-control" value="{{ old('transport_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Library Fee</label>
                        <input type="number" name="library_fee" class="form-control" value="{{ old('library_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sports Fee</label>
                        <input type="number" name="sports_fee" class="form-control" value="{{ old('sports_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Computer Fee</label>
                        <input type="number" name="computer_fee" class="form-control" value="{{ old('computer_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Other Fee</label>
                        <input type="number" name="other_fee" class="form-control" value="{{ old('other_fee', 0) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <hr><h5>Adjustments & Payment</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Discount</label>
                        <input type="number" name="discount" class="form-control" value="{{ old('discount', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fine</label>
                        <input type="number" name="fine" class="form-control" value="{{ old('fine', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Paid Amount</label>
                        <input type="number" name="paid_amount" class="form-control" value="{{ old('paid_amount', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Select Method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@stop
