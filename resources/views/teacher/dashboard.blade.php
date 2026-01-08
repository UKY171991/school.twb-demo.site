<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">Your Information</h3>
                    <p>Name: {{ $teacher->user->name }}</p>
                    <p>Email: {{ $teacher->user->email }}</p>
                    <p>Employee ID: {{ $teacher->employee_id }}</p>
                    <p>Department: {{ $teacher->department }}</p>
                    <p>Bio: {{ $teacher->bio }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">Your Classrooms</h3>
                    <ul>
                        @foreach($classrooms as $classroom)
                        <li><a href="{{ route('teacher.classroom', $classroom) }}">{{ $classroom->name }} - {{ $classroom->subject->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>