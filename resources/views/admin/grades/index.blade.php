<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grades') }}
            </h2>
            <a href="{{ route('admin.grades.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Grade</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b">Student</th>
                                <th class="px-6 py-3 border-b">Classroom</th>
                                <th class="px-6 py-3 border-b">Grade</th>
                                <th class="px-6 py-3 border-b">Comments</th>
                                <th class="px-6 py-3 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $grade)
                            <tr>
                                <td class="px-6 py-4 border-b">{{ $grade->enrollment->student->user->name }}</td>
                                <td class="px-6 py-4 border-b">{{ $grade->enrollment->classroom->name }}</td>
                                <td class="px-6 py-4 border-b">{{ $grade->grade }}</td>
                                <td class="px-6 py-4 border-b">{{ $grade->comments }}</td>
                                <td class="px-6 py-4 border-b">
                                    <a href="{{ route('admin.grades.edit', $grade) }}" class="text-blue-500">Edit</a>
                                    <form method="POST" action="{{ route('admin.grades.destroy', $grade) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-4">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $grades->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>