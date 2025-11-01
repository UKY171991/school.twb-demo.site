@extends('layouts.parent')

@section('title', 'Parent Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Children</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($children as $child)
                        <div class="col-md-6">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-info">
                                    <div class="widget-user-image">
                                        <img class="img-circle elevation-2" src="{{ $child->user->profile_photo_url ?? 'https://via.placeholder.com/128' }}" alt="User Avatar">
                                    </div>
                                    <h3 class="widget-user-username">{{ $child->user->name }}</h3>
                                    <h5 class="widget-user-desc">{{ $child->classModel->name }}</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('parent.children.show', $child) }}" class="nav-link">
                                                Profile <span class="float-right badge bg-primary"><i class="fas fa-user"></i></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('parent.attendance', ['student_id' => $child->id]) }}" class="nav-link">
                                                Attendance <span class="float-right badge bg-success">{{ $child->attendance_percentage ?? 'N/A' }}%</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('parent.grades', ['student_id' => $child->id]) }}" class="nav-link">
                                                Grades <span class="float-right badge bg-warning">{{ $child->average_grade ?? 'N/A' }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No children found.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
