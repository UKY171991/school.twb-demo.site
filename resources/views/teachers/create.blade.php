@extends('layouts.app')

@section('title', 'Add Teacher')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Teacher</h3>
        </div>
        <form id="teacher-form" action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
            @include('teachers._form')
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/image-upload.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/image-upload.js') }}"></script>
@stop