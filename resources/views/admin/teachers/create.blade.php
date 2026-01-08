@extends('layouts.admin')

@section('title', 'Add Teacher')
@section('page-title', 'Onboard Faculty')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
        <a href="{{ route('admin.teachers') }}" class="hover:text-emerald-600 transition-colors">Faculty</a>
        <span class="opacity-50">/</span>
        <span class="text-emerald-500">New Onboarding</span>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl shadow-emerald-100/50">
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Faculty Registration</h2>
                <p class="text-sm text-slate-400 font-medium">Create a new professional record for a teacher</p>
            </div>
            <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200 rotate-3 transition-transform hover:-rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.teachers.store') }}" class="p-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Professional Identity -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0v1m-4 0a2 2 0 014 0v1"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Faculty Identity</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Full Legal Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700" 
                                   placeholder="e.g. Dr. Sarah Jenkins">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Employee Designation / ID</label>
                            <input type="text" name="employee_id" value="{{ old('employee_id') }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800 tracking-wider" 
                                   placeholder="EMP-2026-X">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Academic Department</label>
                            <input type="text" name="department" value="{{ old('department') }}"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700" 
                                   placeholder="e.g. Mathematics and Physics">
                        </div>
                    </div>
                </div>

                <!-- Access & Profile -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0014 20.3m5.988-5.904A10.033 10.033 0 0119.3 20M12 11h.01M12 11l-.01.01M12 11L12 11z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Access Controls</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Work Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700" 
                                   placeholder="faculty@school.edu">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Portal Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700" 
                                   placeholder="Create a secure password">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Professional Bio</label>
                            <textarea name="bio" rows="3" 
                                      class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700" 
                                      placeholder="Brief background and specialties..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.teachers') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Register Faculty Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection