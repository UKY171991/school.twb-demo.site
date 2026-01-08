@extends('layouts.admin')

@section('title', 'Edit Teacher')
@section('page-title', 'Modify Faculty Profile')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
        <a href="{{ route('admin.teachers') }}" class="hover:text-emerald-600 transition-colors">Faculty</a>
        <span class="opacity-50">/</span>
        <span class="text-emerald-500">Edit Profile</span>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl shadow-emerald-100/50">
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Faculty Profile Update</h2>
                <p class="text-sm text-slate-400 font-medium">Updating records for <strong>{{ $teacher->user->name }}</strong></p>
            </div>
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-50 transition-transform hover:scale-110">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="p-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Professional Data -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Professional Record</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $teacher->user->name) }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Employee Designation / ID</label>
                            <input type="text" name="employee_id" value="{{ old('employee_id', $teacher->employee_id) }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800 tracking-wider">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Academic Department</label>
                            <input type="text" name="department" value="{{ old('department', $teacher->department) }}"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Portal Access -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0014 20.3m5.988-5.904A10.033 10.033 0 0119.3 20M12 11h.01M12 11l-.01.01M12 11L12 11z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Account Settings</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Official Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $teacher->user->email) }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Professional Bio</label>
                            <textarea name="bio" rows="4" 
                                      class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-700">{{ old('bio', $teacher->bio) }}</textarea>
                        </div>

                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 text-xs text-amber-700 font-medium">
                            * Portal password remains unchanged. Use System Reset for recovery.
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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Update Faculty Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection