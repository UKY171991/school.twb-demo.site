@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'School Admin Dashboard')

@section('sidebar')
<x-admin-sidebar />
@endsection

@section('content')
<!-- School Info Banner -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-md p-6 mb-6 text-white">
    <h2 class="text-2xl font-bold mb-2">{{ $school->name }}</h2>
    <p class="text-blue-100">{{ $school->code }} | {{ $school->email }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <!-- Total Students Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Students</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalStudents }}</h3>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm mt-2">Enrolled in your school</p>
    </div>

    <!-- Total Teachers Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Teachers</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalTeachers }}</h3>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm mt-2">Teaching staff</p>
    </div>

    <!-- Total Classes Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Classes</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalClasses }}</h3>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm mt-2">Available classes</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="#" class="block p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="font-medium text-gray-700">Add New Student</span>
                </div>
            </a>
            <a href="#" class="block p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="font-medium text-gray-700">Add New Teacher</span>
                </div>
            </a>
            <a href="#" class="block p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="font-medium text-gray-700">Mark Attendance</span>
                </div>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Welcome</h3>
        <p class="text-gray-600 mb-3">
            You are logged in as the School Administrator for <strong>{{ $school->name }}</strong>.
        </p>
        <div class="space-y-2">
            <p class="text-sm text-gray-500">User: <strong>{{ auth()->user()->name }}</strong></p>
            <p class="text-sm text-gray-500">Role: <strong>{{ auth()->user()->role->name }}</strong></p>
            <p class="text-sm text-gray-500">School: <strong>{{ $school->name }}</strong></p>
        </div>
    </div>
</div>
@endsection

