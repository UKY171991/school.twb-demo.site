@extends('layouts.admin')

@section('title', 'Schools Management')
@section('page-title', 'Schools')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Schools Management</h2>
        <p class="text-slate-500 font-medium">Manage all educational institutions in the system</p>
    </div>
    <a href="{{ route('admin.schools.create') }}" 
       class="btn-premium group">
        <svg class="w-5 h-5 mr-2 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add New School</span>
    </a>
</div>

<!-- Schools Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($schools as $school)
    <div class="glass-card rounded-2xl overflow-hidden group hover:translate-y-[-4px] transition-all duration-300">
        <div class="h-24 gradient-primary relative">
            <div class="absolute -bottom-10 left-6">
                @if($school->logo)
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }} Logo" class="w-20 h-20 object-cover rounded-2xl border-4 border-white shadow-lg shadow-indigo-100">
                @else
                    <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center border-4 border-white shadow-lg shadow-indigo-100">
                        <span class="text-3xl">🏫</span>
                    </div>
                @endif
            </div>
            <div class="absolute top-4 right-6 flex gap-2">
                <span class="badge-premium {{ $school->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $school->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        
        <div class="pt-14 p-6">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $school->name }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">CODE: {{ $school->code }}</p>
            </div>
            
            <p class="text-slate-500 text-sm font-medium mb-6 line-clamp-2 leading-relaxed">{{ $school->description ?? 'No description available for this institution.' }}</p>
            
            <div class="space-y-3 mb-8">
                @if($school->city)
                <div class="flex items-center text-sm font-semibold text-slate-600">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center mr-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    {{ $school->city }}{{ $school->state ? ', ' . $school->state : '' }}
                </div>
                @endif
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight mb-1">Type</p>
                        <p class="text-xs font-bold text-slate-700">{{ ucfirst($school->type) }}</p>
                    </div>
                    <div class="bg-indigo-50/50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-tight mb-1">Capacity</p>
                        <p class="text-xs font-bold text-indigo-700">{{ number_format($school->student_capacity) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-5 border-t border-slate-100 mt-auto">
                <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ ucfirst($school->level) }} LEVEL</span>
                <div class="flex gap-2">
                    <a href="{{ route('admin.schools.edit', $school) }}" 
                       class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Edit School">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    <form method="POST" action="{{ route('admin.schools.destroy', $school) }}" 
                          class="confirm-action inline" data-confirm="Are you sure you want to delete this school?">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="p-2.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete School">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center">
        <div class="w-24 h-24 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl text-slate-400">🏫</span>
        </div>
        <h3 class="text-2xl font-black text-slate-800 mb-2">No schools found</h3>
        <p class="text-slate-500 font-medium mb-8">Ready to expand your educational network?</p>
        <a href="{{ route('admin.schools.create') }}" class="btn-premium">
            Add Your First School
        </a>
    </div>
    @endforelse
</div>

@if($schools->hasPages())
<div class="mt-12">
    {{ $schools->links() }}
</div>
@endif
@endsection