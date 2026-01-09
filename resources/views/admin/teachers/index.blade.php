@extends('layouts.admin')

@section('title', 'Teachers Management')
@section('page-title', 'Teachers')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Teachers Management</h2>
        <p class="text-slate-500 font-medium">Manage all teaching staff and their department information</p>
    </div>
    <a href="{{ route('admin.teachers.create') }}" 
       class="btn-premium group transition-all duration-300">
        <svg class="w-5 h-5 mr-2 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add New Teacher</span>
    </a>
</div>

<!-- Teachers Table -->
<div class="table-container">
    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-white/50">
        <h3 class="card-title">Teaching Staff <span class="ml-2 px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold">{{ $teachers->total() }}</span></h3>
    </div>
    
    @if($teachers->count() > 0)
    <div class="overflow-x-auto">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>Teacher Details</th>
                    <th>Employee ID</th>
                    <th>Department</th>
                    <th>Bio</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr>
                    <td>
                        <div class="flex items-center">
                            @if($teacher->image)
                                <img src="{{ asset('storage/' . $teacher->image) }}" alt="{{ $teacher->user->name }}" class="w-11 h-11 rounded-xl object-cover shadow-sm ring-2 ring-slate-100">
                            @else
                                <div class="w-11 h-11 gradient-success rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($teacher->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="font-bold text-slate-800">{{ $teacher->user->name }}</div>
                                <div class="text-xs text-slate-400 font-medium">{{ $teacher->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">{{ $teacher->employee_id }}</span>
                    </td>
                    <td>
                        <span class="badge-premium bg-blue-50 text-blue-600 ring-blue-500/10">
                            {{ $teacher->department }}
                        </span>
                    </td>
                    <td>
                        <div class="text-xs text-slate-500 font-medium max-w-[200px] leading-relaxed italic">
                            "{{ Str::limit($teacher->bio ?? 'No bio available', 60) }}"
                        </div>
                    </td>
                    <td>
                        <span class="badge-premium badge-success">Active</span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                               class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Edit Teacher">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" 
                                  class="confirm-action inline" data-confirm="Are you sure you want to delete this teacher?">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete Teacher">
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
        {{ $teachers->links() }}
    </div>
    @else
    <div class="text-center py-20">
        <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">No teachers found</h3>
        <p class="text-slate-500 font-medium mb-8">Get started by adding your first teacher to the academic team.</p>
        <a href="{{ route('admin.teachers.create') }}" 
           class="btn-premium btn-success">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add First Teacher
        </a>
    </div>
    @endif
</div>
@endsection