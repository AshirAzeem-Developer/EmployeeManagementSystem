<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Scanner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col items-center justify-center min-h-[400px]">
                    
                    <div class="mb-8 text-center">
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">Scan Employee Card</h3>
                        <p class="text-gray-500">Ensure the input field below is focused and scan the QR code.</p>
                    </div>

                    <!-- Input Field for Scanner (Auto-focused) -->
                    <div class="w-full max-w-md relative">
                        <input type="text" id="scannerInput" class="w-full text-center text-2xl py-4 border-2 border-indigo-500 rounded-lg focus:outline-none focus:ring-4 focus:ring-indigo-300 shadow-lg" placeholder="Waiting for scan..." autocomplete="off">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 20h.01M12 12h.01M15 11h.01M12 12v.01M12 12h.01M12 12c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path></svg>
                        </div>
                    </div>

                    <!-- Status Display -->
                    <div id="statusArea" class="mt-8 w-full max-w-lg hidden">
                        <div id="statusAlert" class="p-6 rounded-lg text-center shadow-md">
                            <h4 id="statusTitle" class="text-3xl font-bold mb-2">Checked In</h4>
                            <p id="statusMessage" class="text-xl">Welcome, John Doe</p>
                            <p id="statusTime" class="text-lg mt-2 font-mono bg-white/20 inline-block px-2 rounded">09:00:00</p>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loading" class="mt-4 hidden">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('scannerInput');
            const statusArea = document.getElementById('statusArea');
            const statusAlert = document.getElementById('statusAlert');
            const statusTitle = document.getElementById('statusTitle');
            const statusMessage = document.getElementById('statusMessage');
            const statusTime = document.getElementById('statusTime');
            const loading = document.getElementById('loading');

            // Keep focus on input
            input.focus();
            document.addEventListener('click', () => input.focus());

            let timeout = null;

            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const code = input.value.trim();
                    if (code) {
                        processScan(code);
                    }
                    input.value = '';
                }
            });

            function processScan(code) {
                // Show loading
                loading.classList.remove('hidden');
                statusArea.classList.add('hidden');

                fetch('{{ route("admin.attendance.markByQr") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ employee_code: code })
                })
                .then(response => response.json())
                .then(data => {
                    loading.classList.add('hidden');
                    showStatus(data);
                })
                .catch(error => {
                    loading.classList.add('hidden');
                    showStatus({ status: 'error', message: 'System Error', user: '' });
                    console.error('Error:', error);
                });
            }

            function showStatus(data) {
                statusArea.classList.remove('hidden');
                
                if (data.status === 'success') {
                    statusAlert.className = 'p-6 rounded-lg text-center shadow-md text-white ' + (data.type === 'checkin' ? 'bg-green-500' : 'bg-blue-500');
                    statusTitle.textContent = data.type === 'checkin' ? 'CHECKED IN' : 'CHECKED OUT';
                    statusMessage.textContent = `Welcome, ${data.user}`;
                    statusTime.textContent = data.time;
                    playSound('success');
                } else {
                    statusAlert.className = 'p-6 rounded-lg text-center shadow-md text-white bg-red-500';
                    statusTitle.textContent = 'ERROR';
                    statusMessage.textContent = data.message;
                    statusTime.textContent = '';
                    playSound('error');
                }

                // Hide after 3 seconds
                if (timeout) clearTimeout(timeout);
                timeout = setTimeout(() => {
                    statusArea.classList.add('hidden');
                }, 3000);
            }

            function playSound(type) {
                // Simple beep logic using AudioContext or HTML5 Audio
                // For now, just a placeholder or simple beep if possible
                // const audio = new Audio(type === 'success' ? '/sounds/success.mp3' : '/sounds/error.mp3');
                // audio.play().catch(e => console.log('Audio play failed', e));
            }
        });
    </script>
</x-app-layout>
