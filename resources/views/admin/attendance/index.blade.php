@extends('layouts.adminlte')

@section('title', 'Attendance Management')
@section('page-title', 'Attendance')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Attendance</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-check mr-2"></i>Mark Attendance</h3>
            </div>
            <div class="card-body">
                <form action="#" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="class">Select Class</label>
                                <select name="class_id" id="class" class="form-control" required>
                                    <option value="">-- Select Class --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section">Select Section</label>
                                <select name="section_id" id="section" class="form-control" required>
                                    <option value="">-- Select Section --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-1"></i> Load Students
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Student Attendance List</h3>
            </div>
            <div class="card-body">
                <div class="text-center text-muted">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <p>Please select class, section, and date to load students.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

