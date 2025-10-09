@extends('layouts.adminlte')

@section('title', 'Financial Report')
@section('page-title', 'Financial Report')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item">Reports</li>
<li class="breadcrumb-item active">Financial Report</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Generate Financial Report</h3>
            </div>
            <div class="card-body">
                <form action="#" method="POST" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Report Type</label>
                                <select name="report_type" class="form-control">
                                    <option value="income">Income Report</option>
                                    <option value="expense">Expense Report</option>
                                    <option value="fees">Fee Collection Report</option>
                                    <option value="payroll">Payroll Report</option>
                                    <option value="profit_loss">Profit & Loss</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" name="from_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" name="to_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Format</label>
                                <select name="format" class="form-control">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download mr-1"></i> Generate Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

