@extends('layouts.admin')

@section('title', 'Define Subject')
@section('page-title', 'Course Registry')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="{{ route('admin.subjects') }}" class="hover:text-purple-600 transition-colors">Catalog</a>
        <span class="opacity-30">/</span>
        <span class="text-purple-500">New Entry</span>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
        <div class="p-10 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Curriculum Definition</h2>
                <p class="text-sm text-slate-400 font-medium">Create a new academic subject for the institution</p>
            </div>
            <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.subjects.store') }}" class="p-10 space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Subject Nomenclature</label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Advanced Theoretical Mathematics"
                           class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-black text-slate-800 tracking-tight text-lg placeholder:font-medium placeholder:text-slate-300">
                    @error('name') <p class="text-[10px] font-black text-rose-500 mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Curriculum Scope</label>
                    <textarea name="description" id="description" rows="5" placeholder="Outline the primary objectives and learning outcomes..."
                              class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-medium text-slate-600 placeholder:text-slate-300"></textarea>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.subjects') }}" class="btn-secondary">Dismiss</a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
                    Register Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection