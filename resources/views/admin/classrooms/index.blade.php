<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Classrooms') }}
            </h2>
            <a href="{{ route('admin.classrooms.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Classroom</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b">Name</th>
                                <th class="px-6 py-3 border-b">Teacher</th>
                                <th class="px-6 py-3 border-b">Subject</th>
                                <th class="px-6 py-3 border-b">Capacity</th>
                                <th class="px-6 py-3 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classrooms as $classroom)
                            <tr>
                                <td class="px-6 py-4 border-b">{{ $classroom->name }}</td>
                                <td class="px-6 py-4 border-b">{{ $classroom->teacher->user->name }}</td>
                                <td class="px-6 py-4 border-b">{{ $classroom->subject->name }}</td>
                                <td class="px-6 py-4 border-b">{{ $classroom->capacity }}</td>
                                <td class="px-6 py-4 border-b">
                                    <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="text-blue-500">Edit</a>
                                    <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-4">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $classrooms->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>