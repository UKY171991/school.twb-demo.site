@extends('layouts.admin')

@section('title', 'Search Results')
@section('page-title', 'Global Search')

@section('content')
<div class="space-y-8 font-sans">
    <div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Search Results</h2>
        <p class="text-slate-500 font-medium">Found results for <span class="text-indigo-600 font-black">"{{ $query }}"</span></p>
    </div>

    @if($schools->isEmpty() && $students->isEmpty() && $teachers->isEmpty())
        <div class="glass-card rounded-[3rem] p-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h3 class="text-xl font-black text-slate-400 uppercase tracking-widest">No Matches Found</h3>
            <p class="text-slate-400 mt-2">Try searching for something else or check your spelling.</p>
        </div>
    @else
        <!-- Schools Results -->
        @if($schools->isNotEmpty())
            <section class="space-y-4">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Matching Schools ({{ $schools->count() }})</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($schools as $school)
                        <a href="{{ route('admin.schools') }}" class="glass-card p-6 rounded-3xl hover:border-indigo-300 transition-all group border border-slate-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <h5 class="font-black text-slate-800 tracking-tight">{{ $school->name }}</h5>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $school->code }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Students Results -->
        @if($students->isNotEmpty())
            <section class="space-y-4">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Matching Students ({{ $students->count() }})</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($students as $student)
                        <a href="{{ route('admin.students') }}" class="glass-card p-6 rounded-3xl hover:border-blue-300 transition-all group border border-slate-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black transition-all group-hover:bg-blue-600 group-hover:text-white">
                                    {{ substr($student->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-black text-slate-800 tracking-tight">{{ $student->user->name }}</h5>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $student->student_id }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Teachers Results -->
        @if($teachers->isNotEmpty())
            <section class="space-y-4">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Faculty Matches ({{ $teachers->count() }})</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($teachers as $teacher)
                        <a href="{{ route('admin.teachers') }}" class="glass-card p-6 rounded-3xl hover:border-emerald-300 transition-all group border border-slate-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black transition-all group-hover:bg-emerald-600 group-hover:text-white">
                                    {{ substr($teacher->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-black text-slate-800 tracking-tight">{{ $teacher->user->name }}</h5>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $teacher->employee_id }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endif
</div>
@endsection
