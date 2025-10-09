@extends('layouts.admin')

@section('title', 'Schools Management')
@section('page-title', 'Schools Management')

@section('sidebar')
<x-superadmin-sidebar />
@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">All Schools</h2>
    <a href="{{ route('superadmin.schools.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add New School
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($schools as $school)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $school->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $school->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $school->code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $school->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $school->phone }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                        {{ $school->users_count }} users
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($school->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('superadmin.schools.show', $school) }}" class="text-blue-600 hover:text-blue-900" title="View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('superadmin.schools.edit', $school) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form action="{{ route('superadmin.schools.destroy', $school) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this school?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No schools found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $schools->links() }}
</div>
@endsection

