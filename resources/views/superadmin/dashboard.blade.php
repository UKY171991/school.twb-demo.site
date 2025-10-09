@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')

@section('sidebar')
<x-superadmin-sidebar />
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Schools Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Schools</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalSchools }}</h3>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
        <p class="text-green-600 text-sm mt-2">{{ $activeSchools }} active</p>
    </div>

    <!-- Total Users Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Users</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</h3>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm mt-2">Across all schools</p>
    </div>

    <!-- Total Students Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Students</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalStudents }}</h3>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm mt-2">Enrolled students</p>
    </div>

    <!-- System Status Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">System Status</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">Active</h3>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm mt-2">All systems operational</p>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Welcome to Super Admin Dashboard</h3>
    <p class="text-gray-600">
        You have full access to manage all schools, users, and system settings. Use the sidebar to navigate through different sections.
    </p>
    <div class="mt-4">
        <p class="text-sm text-gray-500">Logged in as: <strong>{{ auth()->user()->name }}</strong></p>
        <p class="text-sm text-gray-500">Role: <strong>{{ auth()->user()->role->name }}</strong></p>
    </div>
</div>
@endsection

