<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Teacher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.teachers.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full" required>
                            @error('name') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full" required>
                            @error('email') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full" required>
                            @error('password') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                            <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}" class="mt-1 block w-full" required>
                            @error('employee_id') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" name="department" id="department" value="{{ old('department') }}" class="mt-1 block w-full">
                        </div>
                        <div class="mb-4">
                            <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                            <textarea name="bio" id="bio" class="mt-1 block w-full">{{ old('bio') }}</textarea>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Teacher</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>