<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">Your Information</h3>
                    <p>Name: {{ $student->user->name }}</p>
                    <p>Email: {{ $student->user->email }}</p>
                    <p>Student ID: {{ $student->student_id }}</p>
                    <p>Date of Birth: {{ $student->date_of_birth }}</p>
                    <p>Address: {{ $student->address }}</p>
                    <p>Phone: {{ $student->phone }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">Your Enrollments</h3>
                    <ul>
                        @foreach($enrollments as $enrollment)
                        <li>{{ $enrollment->classroom->name }} - {{ $enrollment->classroom->subject->name }} (Teacher: {{ $enrollment->classroom->teacher->user->name }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">Your Grades</h3>
                    <ul>
                        @foreach($grades as $grade)
                        <li>{{ $grade->enrollment->classroom->subject->name }}: {{ $grade->grade }} - {{ $grade->comments }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>