@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Schools Card -->
    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-indigo-500/10 rounded-full transition-all group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Schools</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $stats['schools'] }}</h3>
            </div>
            <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between relative z-10">
            <a href="{{ route('admin.schools') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center">
                Review Details 
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <a href="{{ route('admin.schools.create') }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>
    </div>

    <!-- Students Card -->
    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-blue-500/10 rounded-full transition-all group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Students</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $stats['students'] }}</h3>
            </div>
            <div class="w-12 h-12 gradient-secondary rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between relative z-10">
            <a href="{{ route('admin.students') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center">
                Review Details 
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <a href="{{ route('admin.students.create') }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>
    </div>

    <!-- Teachers Card -->
    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-emerald-500/10 rounded-full transition-all group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Teachers</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $stats['teachers'] }}</h3>
            </div>
            <div class="w-12 h-12 gradient-success rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between relative z-10">
            <a href="{{ route('admin.teachers') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center">
                Review Details 
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <a href="{{ route('admin.teachers.create') }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>
    </div>

    <!-- Subjects Card -->
    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-purple-500/10 rounded-full transition-all group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Subjects</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $stats['subjects'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between relative z-10">
            <a href="{{ route('admin.subjects') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 flex items-center">
                Review Details 
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <a href="{{ route('admin.subjects.create') }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Students -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="card-title">Recent Students</h3>
            <a href="{{ route('admin.students') }}" class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest hover:text-indigo-700 underline underline-offset-4">View All</a>
        </div>
        <div class="p-4">
            @php
                $recentStudents = \App\Models\Student::with('user')->latest()->take(5)->get();
            @endphp
            @forelse($recentStudents as $student)
            <div class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-xl transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 gradient-secondary rounded-xl flex items-center justify-center text-white font-bold shadow-md shadow-blue-100 transform group-hover:rotate-3 transition-all">
                        {{ substr($student->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">{{ $student->user->name }}</p>
                        <p class="text-xs text-slate-400 font-medium">ID: {{ $student->student_id }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all">
                    <a href="{{ route('admin.students.edit', $student) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <p class="text-slate-400 font-medium">No students recently added</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Teachers -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="card-title">Recent Teachers</h3>
            <a href="{{ route('admin.teachers') }}" class="text-[11px] font-bold text-emerald-600 uppercase tracking-widest hover:text-emerald-700 underline underline-offset-4">View All</a>
        </div>
        <div class="p-4">
            @php
                $recentTeachers = \App\Models\Teacher::with('user')->latest()->take(5)->get();
            @endphp
            @forelse($recentTeachers as $teacher)
            <div class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-xl transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 gradient-success rounded-xl flex items-center justify-center text-white font-bold shadow-md shadow-emerald-100 transform group-hover:-rotate-3 transition-all">
                        {{ substr($teacher->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">{{ $teacher->user->name }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $teacher->department }} Department</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <p class="text-slate-400 font-medium">No teachers recently added</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
