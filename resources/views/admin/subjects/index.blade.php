@extends('layouts.admin')

@section('title', 'Subjects Management')
@section('page-title', 'Subjects')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Subjects Management</h2>
        <p class="text-gray-600">Manage all academic subjects and courses</p>
    </div>
    <a href="{{ route('admin.subjects.create') }}" 
       class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add Subject</span>
    </a>
</div>

<!-- Subjects Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($subjects as $subject)
    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                </span>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $subject->name }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($subject->description ?? 'No description available', 100) }}</p>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    @php
                        $classroomCount = \App\Models\Classroom::where('subject_id', $subject->id)->count();
                    @endphp
                    {{ $classroomCount }} {{ Str::plural('classroom', $classroomCount) }}
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.subjects.edit', $subject) }}" 
                       class="text-purple-600 hover:text-purple-900 bg-purple-100 hover:bg-purple-200 px-3 py-1 rounded-md text-sm transition-colors">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" 
                          class="inline" onsubmit="return confirm('Are you sure you want to delete this subject?')">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-sm transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No subjects found</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by adding a new subject.</p>
        <div class="mt-6">
            <a href="{{ route('admin.subjects.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Subject
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($subjects->hasPages())
<div class="mt-6">
    {{ $subjects->links() }}
</div>
@endif
@endsection