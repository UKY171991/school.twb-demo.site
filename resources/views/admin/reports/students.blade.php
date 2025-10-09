@extends('layouts.adminlte')

@section('title', 'Student Report')
@section('page-title', 'Student Report')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item">Reports</li>
<li class="breadcrumb-item active">Student Report</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Generate Student Report</h3>
            </div>
            <div class="card-body">
                <form action="#" method="POST" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Report Type</label>
                                <select name="report_type" class="form-control">
                                    <option value="all">All Students</option>
                                    <option value="class">By Class</option>
                                    <option value="section">By Section</option>
                                    <option value="status">By Status</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Class</label>
                                <select name="class_id" class="form-control">
                                    <option value="">-- Select Class --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Section</label>
                                <select name="section_id" class="form-control">
                                    <option value="">-- Select Section --</option>
                                </select>
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

