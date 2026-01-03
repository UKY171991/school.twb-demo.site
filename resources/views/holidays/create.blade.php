
@extends('layouts.app')

@section('title', isset($holiday) ? 'Edit Holiday' : 'New Holiday')

@section('content_header')
    <h1>{{ isset($holiday) ? 'Edit Holiday' : 'New Holiday' }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="holiday-full-form" method="POST" action="{{ isset($holiday) ? route('holidays.update', $holiday) : route('holidays.store') }}">
                @include('holidays._form')
            </form>
        </div>
    </div>
@stop
 
