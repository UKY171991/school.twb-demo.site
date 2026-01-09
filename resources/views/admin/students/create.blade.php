@extends('layouts.admin')

@section('title', 'Add Student')
@section('page-title', 'Enroll New Student')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
        <a href="{{ route('admin.students') }}" class="hover:text-indigo-600 transition-colors">Students</a>
        <span class="opacity-50">/</span>
        <span class="text-indigo-500">New Enrollment</span>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl shadow-blue-100/50">
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Student Admissions</h2>
                <p class="text-sm text-slate-400 font-medium">Create a new academic profile for a student</p>
            </div>
            <div class="w-14 h-14 gradient-secondary rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 -rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.students.store') }}" class="p-10" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Personal Details -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Personal Details</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700" 
                                   placeholder="e.g. John Doe">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Residential Address</label>
                            <textarea name="address" rows="3" 
                                      class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700" 
                                      placeholder="Current living address..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student Photograph</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-blue-400 transition-colors bg-white">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-black text-blue-600 hover:text-blue-500">
                                            <span>Upload a photo</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tight">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account & Academic -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0014 20.3m5.988-5.904A10.033 10.033 0 0119.3 20M12 11h.01M12 11l-.01.01M12 11L12 11z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Account Credentials</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student ID / Enrollment No.</label>
                            <input type="text" name="student_id" value="{{ old('student_id') }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-black text-slate-800 tracking-wider" 
                                   placeholder="e.g. STD/2026/001">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700" 
                                   placeholder="student@example.com">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Access Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700" 
                                   placeholder="Min. 8 characters">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Emergency Contact</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700" 
                                   placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.students') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Enroll Student
                </button>
            </div>
        </form>
    </div>
</div>
@endsection