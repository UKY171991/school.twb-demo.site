@extends('adminlte::page')

@section('title', 'Teacher Details')

@section('content_header')
    <h1><i class="fas fa-user"></i> Teacher Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $teacher->name }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if($teacher->image)
                        <img src="{{ $teacher->image_url }}" alt="{{ $teacher->name }}" class="img-fluid img-circle elevation-2" style="max-height: 200px;">
                    @else
                        <div class="text-center py-5 bg-light rounded">
                            <i class="fas fa-user fa-5x text-secondary"></i>
                        </div>
                    @endif
                    <h3 class="profile-username text-center mt-3">{{ $teacher->name }}</h3>
                    <p class="text-muted text-center">{{ $teacher->designation ?? 'Teacher' }}</p>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th style="width: 30%">School</th>
                                <td>{{ $teacher->school->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $teacher->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $teacher->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ ucfirst($teacher->gender) }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $teacher->date_of_birth ? $teacher->date_of_birth->format('d M, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Date of Joining</th>
                                <td>{{ $teacher->date_of_joining ? $teacher->date_of_joining->format('d M, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $teacher->address ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($teacher->signature)
                        <div class="mt-4">
                            <h5>Signature</h5>
                            <img src="{{ $teacher->signature_url }}" alt="Signature" class="img-fluid border p-2" style="max-height: 100px;">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-default" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
@stop
