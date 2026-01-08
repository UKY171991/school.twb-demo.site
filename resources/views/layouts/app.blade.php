@extends('adminlte::page')

@section('adminlte_css_pre')
    @if(isset($dynamicSchoolFavicon) && $dynamicSchoolFavicon)
        <link rel="icon" type="image/x-icon" href="{{ asset($dynamicSchoolFavicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset($dynamicSchoolFavicon) }}">
    @endif
@stop

@section('head')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iframe-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-role-badge.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@stop

@section('js')
    <script src="{{ asset('js/app-custom.js') }}"></script>
    <script src="{{ asset('js/ajax-crud.js') }}"></script>
    @stack('scripts')
@stop