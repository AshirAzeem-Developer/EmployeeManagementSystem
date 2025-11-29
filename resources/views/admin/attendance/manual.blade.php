<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manual Attendance Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.attendance.manual.store') }}" method="POST">
                        @csrf
                        
                        {{-- Employee Search --}}
                        <div class="mb-6 border-b pb-6">
                            <label for="employee_code" class="block text-sm font-medium text-gray-700">Employee Code</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" name="employee_code_search" id="employee_code_search" class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="Enter Employee Code (e.g., EMP001)">
                                <button type="button" onclick="searchEmployee()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Search
                                </button>
                            </div>
                            <p id="search_message" class="mt-2 text-sm text-red-600 hidden"></p>
                        </div>

                        {{-- Employee Details (Read-only) --}}
                        <div id="employee_details" class="mb-6 hidden">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Employee Details</h3>
                                <div class="mt-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" id="employee_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Code</label>
                                        <input type="text" id="employee_code_display" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" id="user_id">
                            </div>
                        </div>

                        {{-- Attendance Form --}}
                        <div id="attendance_form" class="hidden">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 mb-6">
                                <div class="sm:col-span-3">
                                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" name="date" id="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                                        <option value="present">Present</option>
                                        <option value="late">Late</option>
                                        <option value="absent">Absent</option>
                                        <option value="half_day">Half Day</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="check_in" class="block text-sm font-medium text-gray-700">Check In Time</label>
                                    <input type="time" name="check_in" id="check_in" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                                    @error('check_in') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="check_out" class="block text-sm font-medium text-gray-700">Check Out Time (Optional)</label>
                                    <input type="time" name="check_out" id="check_out" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('check_out') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                    <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                    Mark Attendance
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchEmployee() {
            const code = document.getElementById('employee_code_search').value;
            const message = document.getElementById('search_message');
            const details = document.getElementById('employee_details');
            const form = document.getElementById('attendance_form');

            if (!code) {
                message.textContent = 'Please enter an employee code.';
                message.classList.remove('hidden');
                return;
            }

            fetch('{{ route("admin.attendance.manual.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ employee_code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    message.classList.add('hidden');
                    
                    document.getElementById('employee_name').value = data.user.name;
                    document.getElementById('employee_code_display').value = data.user.employee_code;
                    document.getElementById('user_id').value = data.user.id;

                    details.classList.remove('hidden');
                    form.classList.remove('hidden');
                } else {
                    message.textContent = data.message || 'Employee not found.';
                    message.classList.remove('hidden');
                    details.classList.add('hidden');
                    form.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                message.textContent = 'An error occurred while searching.';
                message.classList.remove('hidden');
            });
        }
    </script>
</x-app-layout>
