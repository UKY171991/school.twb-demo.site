@extends('layouts.admin')

@section('title', 'Add Classroom')
@section('page-title', 'Venue Configuration')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="{{ route('admin.classrooms') }}" class="hover:text-indigo-600 transition-colors">Venues</a>
        <span class="opacity-30">/</span>
        <span class="text-indigo-500">New Construction</span>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
        <div class="p-10 border-b border-slate-50 bg-slate-50/30">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Establish Classroom</h2>
            <p class="text-sm text-slate-400 font-medium">Define a new learning environment and assign resources</p>
        </div>

        <form method="POST" action="{{ route('admin.classrooms.store') }}" class="p-10 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Room Name / ID</label>
                        <input type="text" name="name" required placeholder="e.g. Science Lab 102"
                               class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student Capacity</label>
                        <input type="number" name="capacity" required placeholder="30"
                               class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Lead Faculty</label>
                        <select name="teacher_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Primary Subject</label>
                        <select name="subject_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.classrooms') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Build Classroom</button>
            </div>
        </form>
    </div>
</div>
@endsection
