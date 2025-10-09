@extends('layouts.admin')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')

@section('sidebar')
<ul class="space-y-2">
    <li>
        <a href="{{ route('teacher.dashboard') }}" class="flex items-center px-4 py-3 text-white bg-green-600 rounded-lg">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
    </li>
    <li>
        <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
            My Students
        </a>
    </li>
    <li>
        <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Attendance
        </a>
    </li>
    <li>
        <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Assignments
        </a>
    </li>
    <li>
        <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            My Subjects
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg shadow-md p-6 mb-6 text-white">
    <h2 class="text-2xl font-bold mb-2">Welcome, {{ $user->name }}!</h2>
    <p class="text-green-100">Teacher Dashboard - {{ $user->school->name }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">My Classes</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">0</h3>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Students</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">0</h3>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pending Tasks</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">0</h3>
            </div>
            <div class="bg-orange-100 rounded-full p-3">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Teacher Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">Name</p>
            <p class="font-medium text-gray-800">{{ $user->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="font-medium text-gray-800">{{ $user->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">School</p>
            <p class="font-medium text-gray-800">{{ $user->school->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Role</p>
            <p class="font-medium text-gray-800">{{ $user->role->name }}</p>
        </div>
    </div>
</div>
@endsection

