@extends('adminlte::page')

@section('adminlte_css_pre')
    @if(isset($dynamicSchoolFavicon) && $dynamicSchoolFavicon)
        <link rel="icon" type="image/x-icon" href="{{ asset($dynamicSchoolFavicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset($dynamicSchoolFavicon) }}">
    @endif
@stop