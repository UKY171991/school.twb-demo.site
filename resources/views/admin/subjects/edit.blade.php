<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Subject') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" class="mt-1 block w-full" required>
                            @error('name') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" class="mt-1 block w-full">{{ old('description', $subject->description) }}</textarea>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Subject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>