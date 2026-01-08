@extends('layouts.admin')

@section('title', 'Students Management')
@section('page-title', 'Students')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Students Management</h2>
        <p class="text-slate-500 font-medium">Manage all student records and their academic information</p>
    </div>
    <a href="{{ route('admin.students.create') }}" 
       class="btn-premium group">
        <svg class="w-5 h-5 mr-2 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add New Student</span>
    </a>
</div>

<!-- Search and Filter -->
<div class="glass-card rounded-2xl mb-8 overflow-hidden">
    <div class="p-6 bg-white/50">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="flex-1 relative">
                <input type="text" placeholder="Search by name, ID or email..." 
                       class="w-full pl-12 pr-4 py-3 bg-white border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all placeholder:text-slate-400 font-medium shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <select class="bg-white border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 min-w-[160px] font-medium shadow-sm text-slate-600">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <button class="p-3 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="table-container">
    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-white/50">
        <h3 class="card-title">Enrolled Students <span class="ml-2 px-2.5 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold">{{ $students->total() }}</span></h3>
    </div>
    
    @if($students->count() > 0)
    <div class="overflow-x-auto">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>Student Details</th>
                    <th>Student ID</th>
                    <th>Contact Info</th>
                    <th>Birthday</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-11 h-11 gradient-primary rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                {{ substr($student->user->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="font-bold text-slate-800">{{ $student->user->name }}</div>
                                <div class="text-xs text-slate-400 font-medium">{{ $student->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">{{ $student->student_id }}</span>
                    </td>
                    <td>
                        <div class="text-sm font-semibold text-slate-700">{{ $student->phone ?? 'N/A' }}</div>
                        <div class="text-xs text-slate-400 font-medium truncate max-w-[200px]">{{ $student->address ?? 'No address' }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-bold text-slate-600">
                            {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge-premium badge-success">Active</span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.students.edit', $student) }}" 
                               class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Edit Student">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" 
                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete Student">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
        {{ $students->links() }}
    </div>
    @else
    <div class="text-center py-20">
        <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">No students found</h3>
        <p class="text-slate-500 font-medium mb-8">Get started by adding your first student to the system.</p>
        <a href="{{ route('admin.students.create') }}" 
           class="btn-premium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add First Student
        </a>
    </div>
    @endif
</div>
@endsection