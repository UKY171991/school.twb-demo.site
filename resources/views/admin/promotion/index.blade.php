@extends('layouts.adminlte')

@section('title', 'Student Promotion')
@section('page-title', 'Student Promotion')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Promotion</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-level-up-alt mr-2"></i>Promote Students</h3>
            </div>
            <div class="card-body">
                <form action="#" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Current Class</label>
                                <select name="from_class" class="form-control" required>
                                    <option value="">-- Select Class --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Current Section</label>
                                <select name="from_section" class="form-control" required>
                                    <option value="">-- Select Section --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Promote To Class</label>
                                <select name="to_class" class="form-control" required>
                                    <option value="">-- Select Class --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Promote To Section</label>
                                <select name="to_section" class="form-control" required>
                                    <option value="">-- Select Section --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-arrow-up mr-1"></i> Promote Students
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Eligible Students for Promotion</h3>
            </div>
            <div class="card-body">
                <div class="text-center text-muted">
                    <p>Select current class and section to view students.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

