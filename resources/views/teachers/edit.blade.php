@extends('layouts.app')

@section('title', 'Edit Teacher')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Teacher</h3>
        </div>
        <form id="teacher-form" action="{{ route('teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('teachers._form', ['teacher' => $teacher])
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/image-upload.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/image-upload.js') }}"></script>
@stop