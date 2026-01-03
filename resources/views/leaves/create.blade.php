
@extends('layouts.app')

@section('title', isset($leave) ? 'Edit Leave' : 'New Leave')

@section('content_header')
    <h1>{{ isset($leave) ? 'Edit Leave' : 'New Leave' }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="leave-form" method="POST" action="{{ isset($leave) ? route('leaves.update', $leave) : route('leaves.store') }}">
                @include('leaves._form')
            </form>
        </div>
    </div>
@stop
 
