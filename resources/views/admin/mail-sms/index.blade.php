@extends('layouts.adminlte')

@section('title', 'Mail & SMS')
@section('page-title', 'Mail & SMS')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Mail & SMS</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#email" role="tab">Send Email</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#sms" role="tab">Send SMS</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="email" role="tabpanel">
                        <form action="#" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Send To</label>
                                <select name="recipient_type" class="form-control">
                                    <option value="individual">Individual</option>
                                    <option value="class">Entire Class</option>
                                    <option value="all_students">All Students</option>
                                    <option value="all_teachers">All Teachers</option>
                                    <option value="all_guardians">All Guardians</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="Email subject">
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea name="message" class="form-control" rows="8" placeholder="Email message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane mr-1"></i> Send Email
                            </button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="sms" role="tabpanel">
                        <form action="#" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Send To</label>
                                <select name="recipient_type" class="form-control">
                                    <option value="individual">Individual</option>
                                    <option value="class">Entire Class</option>
                                    <option value="all_students">All Students</option>
                                    <option value="all_teachers">All Teachers</option>
                                    <option value="all_guardians">All Guardians</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea name="message" class="form-control" rows="4" maxlength="160" placeholder="SMS message (max 160 characters)"></textarea>
                                <small class="text-muted">0/160 characters</small>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sms mr-1"></i> Send SMS
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

