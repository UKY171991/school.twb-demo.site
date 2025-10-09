@extends('layouts.admin')
@section('title', 'Receptionist Dashboard')
@section('page-title', 'Receptionist Dashboard')
@section('sidebar')
<ul class="space-y-2">
    <li><a href="{{ route('receptionist.dashboard') }}" class="flex items-center px-4 py-3 text-white bg-teal-600 rounded-lg">Dashboard</a></li>
    <li><a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">Admissions</a></li>
    <li><a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">Visitor Log</a></li>
    <li><a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">Phone Calls</a></li>
</ul>
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Receptionist Dashboard</h2>
    <p class="text-gray-600">Welcome, {{ auth()->user()->name }}!</p>
    <p class="text-gray-600 mt-2">School: {{ auth()->user()->school->name }}</p>
</div>
@endsection

