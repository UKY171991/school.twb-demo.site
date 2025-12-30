@extends('adminlte::page')

@section('adminlte_css_pre')
    @if(isset($dynamicSchoolFavicon) && $dynamicSchoolFavicon)
        <link rel="icon" type="image/x-icon" href="{{ asset($dynamicSchoolFavicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset($dynamicSchoolFavicon) }}">
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iframe-fix.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/app-custom.js') }}"></script>
    @stack('scripts')
@stop