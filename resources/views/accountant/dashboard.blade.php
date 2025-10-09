@extends('layouts.admin')
@section('title', 'Accountant Dashboard')
@section('page-title', 'Accountant Dashboard')
@section('sidebar')
<ul class="space-y-2">
    <li><a href="{{ route('accountant.dashboard') }}" class="flex items-center px-4 py-3 text-white bg-yellow-600 rounded-lg">Dashboard</a></li>
    <li><a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">Fees Management</a></li>
    <li><a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">Income</a></li>
    <li><a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">Expenses</a></li>
    <li><a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">Reports</a></li>
</ul>
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Accountant Dashboard</h2>
    <p class="text-gray-600">Welcome, {{ auth()->user()->name }}!</p>
    <p class="text-gray-600 mt-2">School: {{ auth()->user()->school->name }}</p>
</div>
@endsection

