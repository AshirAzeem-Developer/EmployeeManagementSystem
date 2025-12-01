<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Department') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-primary-600 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Edit Department</h3>
                        <p class="text-primary-100 text-sm">Update department information.</p>
                    </div>

                    <div class="p-8 space-y-6">
                        
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">{{ old('description', $department->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.departments.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-600 to-indigo-600 text-white font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                                Update Department
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
