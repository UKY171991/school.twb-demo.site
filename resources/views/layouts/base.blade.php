@extends('adminlte::page')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
@stop

@yield('content')
