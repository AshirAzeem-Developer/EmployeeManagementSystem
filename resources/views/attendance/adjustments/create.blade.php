<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Attendance Adjustment') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('attendance.adjustments.store') }}" method="POST">
                @csrf

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-primary-600 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Adjustment Request</h3>
                        <p class="text-primary-100 text-sm">Submit a request to adjust your attendance record.</p>
                    </div>

                    <div class="p-8 space-y-6">
                        
                        {{-- Date --}}
                        <div>
                            <label for="attendance_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="attendance_date" id="attendance_date" value="{{ old('attendance_date') }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                            @error('attendance_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Clock In --}}
                            <div>
                                <label for="clock_in" class="block text-sm font-medium text-gray-700 mb-1">Clock In Time</label>
                                <input type="time" name="clock_in" id="clock_in" value="{{ old('clock_in') }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                                @error('clock_in') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Clock Out --}}
                            <div>
                                <label for="clock_out" class="block text-sm font-medium text-gray-700 mb-1">Clock Out Time</label>
                                <input type="time" name="clock_out" id="clock_out" value="{{ old('clock_out') }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                                @error('clock_out') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Adjustment</label>
                            <textarea name="reason" id="reason" rows="4" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required placeholder="Explain why you need this adjustment...">{{ old('reason') }}</textarea>
                            @error('reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                            <a href="{{ route('attendance.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-600 to-indigo-600 text-white font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                                Submit Request
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
