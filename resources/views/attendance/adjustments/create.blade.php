<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Attendance Adjustment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('attendance.adjustments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="adjustment_date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="adjustment_date" id="adjustment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required max="{{ date('Y-m-d') }}">
                            @error('adjustment_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Adjustment Type</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required onchange="toggleTimeFields()">
                                <option value="check_in">Missed Check-in</option>
                                <option value="check_out">Missed Check-out</option>
                                <option value="full_day_missing">Full Day Missing</option>
                            </select>
                            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4" id="time_field">
                            <label for="requested_time" class="block text-sm font-medium text-gray-700">Time</label>
                            <input type="time" name="requested_time" id="requested_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('requested_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4 hidden" id="check_out_time_field">
                            <label for="check_out_time" class="block text-sm font-medium text-gray-700">Check Out Time</label>
                            <input type="time" name="check_out_time" id="check_out_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('check_out_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required></textarea>
                            @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('attendance.adjustments.index') }}" class="mr-4 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTimeFields() {
            const type = document.getElementById('type').value;
            const timeField = document.getElementById('time_field');
            const checkOutTimeField = document.getElementById('check_out_time_field');
            const timeLabel = document.querySelector('label[for="requested_time"]');

            if (type === 'full_day_missing') {
                timeLabel.textContent = 'Check In Time';
                checkOutTimeField.classList.remove('hidden');
            } else {
                timeLabel.textContent = 'Time';
                checkOutTimeField.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
