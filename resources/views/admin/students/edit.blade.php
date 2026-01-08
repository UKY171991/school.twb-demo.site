<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.students.update', $student) }}">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $student->user->name) }}" class="mt-1 block w-full" required>
                            @error('name') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $student->user->email) }}" class="mt-1 block w-full" required>
                            @error('email') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $student->student_id) }}" class="mt-1 block w-full" required>
                            @error('student_id') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}" class="mt-1 block w-full">
                        </div>
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" class="mt-1 block w-full">{{ old('address', $student->address) }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}" class="mt-1 block w-full">
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>