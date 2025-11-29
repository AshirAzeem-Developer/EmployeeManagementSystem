<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Check In / Check Out Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="text-2xl font-bold text-gray-700">
                            {{ \Carbon\Carbon::now()->format('l, d F Y') }}
                        </div>
                        <div class="text-4xl font-extrabold text-primary-600" x-data x-init="setInterval(() => $el.innerText = new Date().toLocaleTimeString(), 1000)">
                            {{ \Carbon\Carbon::now()->format('H:i:s') }}
                        </div>

                        @if (!$todayAttendance)
                            <form action="{{ route('attendance.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-8 py-4 bg-green-600 text-white text-xl font-bold rounded-full hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition transform hover:scale-105 shadow-lg">
                                    Check In
                                </button>
                            </form>
                        @elseif (!$todayAttendance->check_out)
                            <div class="text-center">
                                <p class="text-gray-600 mb-4">Checked In at: <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i A') }}</span></p>
                                <form action="{{ route('attendance.update', $todayAttendance) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-8 py-4 bg-red-600 text-white text-xl font-bold rounded-full hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition transform hover:scale-105 shadow-lg">
                                        Check Out
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-green-800 font-semibold text-lg">You have completed your attendance for today.</p>
                                <div class="mt-2 text-sm text-green-700">
                                    <span class="mr-4">In: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i A') }}</span>
                                    <span>Out: {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i A') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Attendance History --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Attendance History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Working Hours</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i A') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i A') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if ($attendance->check_in && $attendance->check_out)
                                                {{ \Carbon\Carbon::parse($attendance->check_in)->diff(\Carbon\Carbon::parse($attendance->check_out))->format('%H:%I') }} hrs
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No attendance records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
