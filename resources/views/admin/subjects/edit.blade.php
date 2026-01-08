@extends('layouts.admin')

@section('title', 'Edit Subject')
@section('page-title', 'Curriculum Update')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="{{ route('admin.subjects') }}" class="hover:text-purple-600 transition-colors">Catalog</a>
        <span class="opacity-30">/</span>
        <span class="text-purple-500">Edit Archive</span>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
        <div class="p-10 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Modify Course</h2>
                <p class="text-sm text-slate-400 font-medium">Updating subject details for <span class="text-purple-600 font-black italic">{{ $subject->name }}</span></p>
            </div>
            <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="p-10 space-y-8">
            @csrf @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Subject Nomenclature</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" required
                           class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-black text-slate-800 tracking-tight text-lg">
                    @error('name') <p class="text-[10px] font-black text-rose-500 mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Curriculum Scope</label>
                    <textarea name="description" id="description" rows="5"
                              class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-medium text-slate-600">{{ old('description', $subject->description) }}</textarea>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.subjects') }}" class="btn-secondary">Discard Changes</a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
                    Release Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection