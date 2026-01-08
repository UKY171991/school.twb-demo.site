@extends('layouts.admin')

@section('title', 'Subjects Management')
@section('page-title', 'Academic Curriculum')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Academic Subjects</h2>
            <p class="text-slate-500 font-medium tracking-tight">Manage your institutional curriculum and course catalog</p>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="btn-primary" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Define New Subject
        </a>
    </div>

    <!-- Subjects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($subjects as $subject)
        <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl shadow-purple-100/40 border border-white hover:-translate-y-2 transition-all duration-500 group">
            <div class="p-8">
                <!-- Icon & Status -->
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 transition-transform group-hover:rotate-12">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="badge-premium bg-emerald-50 text-emerald-600 border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Active
                    </span>
                </div>
                
                <!-- Info -->
                <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2 uppercase">{{ $subject->name }}</h3>
                <p class="text-sm font-medium text-slate-400 line-clamp-2 min-h-[40px]">{{ $subject->description ?? 'Comprehensive academic course curriculum.' }}</p>
                
                <!-- Metrics -->
                <div class="mt-8 flex items-center justify-between">
                    <div class="flex flex-col">
                        @php
                            $classroomCount = \App\Models\Classroom::where('subject_id', $subject->id)->count();
                        @endphp
                        <span class="text-sm font-black text-slate-700 tracking-tighter">{{ $classroomCount }} Rooms</span>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Enrolled Slots</span>
                    </div>
                    
                    <div class="flex items-center -space-x-2">
                         <div class="w-8 h-8 rounded-full bg-indigo-50 border-2 border-white flex items-center justify-center text-indigo-600 text-[10px] font-black italic">S</div>
                         <div class="w-8 h-8 rounded-full bg-emerald-50 border-2 border-white flex items-center justify-center text-emerald-600 text-[10px] font-black italic">M</div>
                         <div class="w-8 h-8 rounded-full bg-purple-50 border-2 border-white flex items-center justify-center text-purple-600 text-[10px] font-black italic">A</div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.subjects.edit', $subject) }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-purple-600 transition-colors">Edit File</a>
                
                <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="confirm-action" data-confirm="Erase path '{{ $subject->name }}' from curriculum?">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center glass-card rounded-[3rem]">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                 <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="text-lg font-black text-slate-400 uppercase tracking-tightest">Curriculum is empty</h3>
            <p class="text-slate-400 text-sm mt-1">Initialize your catalog by defining the first subject.</p>
        </div>
        @endforelse
    </div>

    @if($subjects->hasPages())
    <div class="mt-8 font-bold">
        {{ $subjects->links() }}
    </div>
    @endif
</div>
@endsection