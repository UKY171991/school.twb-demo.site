<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Classroom: ') . $classroom->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">Enrolled Students</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b">Student Name</th>
                                <th class="px-6 py-3 border-b">Student ID</th>
                                <th class="px-6 py-3 border-b">Grades</th>
                                <th class="px-6 py-3 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                            <tr>
                                <td class="px-6 py-4 border-b">{{ $enrollment->student->user->name }}</td>
                                <td class="px-6 py-4 border-b">{{ $enrollment->student->student_id }}</td>
                                <td class="px-6 py-4 border-b">
                                    @foreach($enrollment->grades as $grade)
                                    {{ $grade->grade }} ({{ $grade->comments }})
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 border-b">
                                    <form method="POST" action="{{ route('teacher.add.grade', $enrollment) }}" class="inline">
                                        @csrf
                                        <input type="number" name="grade" min="0" max="100" placeholder="Grade" required>
                                        <input type="text" name="comments" placeholder="Comments">
                                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Add Grade</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>