<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Shift') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.shifts.update', $shift) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-primary-600 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Edit Shift</h3>
                        <p class="text-primary-100 text-sm">Update shift schedule details.</p>
                    </div>

                    <div class="p-8 space-y-6">
                        
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Shift Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $shift->name) }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Start Time --}}
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $shift->start_time) }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                                @error('start_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- End Time --}}
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $shift->end_time) }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                                @error('end_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Grace Period --}}
                        <div>
                            <label for="grace_period_minutes" class="block text-sm font-medium text-gray-700 mb-1">Grace Period (Minutes)</label>
                            <input type="number" name="grace_period_minutes" id="grace_period_minutes" value="{{ old('grace_period_minutes', $shift->grace_period_minutes) }}" min="0" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                            <p class="text-xs text-gray-500 mt-1">Allowed late arrival time in minutes.</p>
                            @error('grace_period_minutes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.shifts.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-600 to-indigo-600 text-white font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                                Update Shift
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
