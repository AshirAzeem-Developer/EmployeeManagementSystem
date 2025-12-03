<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Add New Employee
            </a>
        </div>
        </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shift</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->employee_code ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->department ? $user->department->name : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->shift ? $user->shift->name : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="openCardModal({{ json_encode($user) }})" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-primary-600 hover:text-primary-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Employee Card Modal -->
    <div id="cardModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCardModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                
                <!-- ID Card Design -->
                <div class="relative bg-white text-gray-800 overflow-hidden h-[500px] flex flex-col items-center shadow-2xl rounded-xl">
                    
                    <!-- Blue Curved Header -->
                    <div class="absolute top-0 left-0 w-full h-48 bg-blue-600 z-0" style="clip-path: ellipse(150% 100% at 50% 0%);"></div>
                    
                    <!-- Company Name -->
                    <div class="relative z-10 w-full flex justify-center items-start px-6 pt-6">
                        <h3 class="text-white font-bold text-lg tracking-wide drop-shadow-md text-center">Information Technology<br>Services</h3>
                    </div>

                    <!-- QR Code Square (Replaces Photo) -->
                    <div class="relative z-10 mt-8 mb-4">
                        <div class="w-40 h-40 bg-white p-2 shadow-xl flex items-center justify-center border-4 border-orange-500 rounded-lg">
                            <div class="w-full h-full flex items-center justify-center bg-white">
                                <img id="modalQrCode" src="" alt="QR Code" class="w-36 h-36 object-contain">
                            </div>
                        </div>
                    </div>

                    <!-- Employee Details -->
                    <div class="relative z-10 w-full flex flex-col items-center px-6 mt-2">
                        <h2 id="modalName" class="text-2xl font-bold text-gray-900 uppercase tracking-wide border-b-2 border-blue-600 pb-1 mb-4">Name Surname</h2>
                        
                        <div class="w-full space-y-3 text-sm font-medium text-gray-700 px-4">
                            <div class="flex">
                                <span class="w-24 font-bold text-gray-900">ID No</span>
                                <span class="mr-2">:</span>
                                <span id="modalCode">20256</span>
                            </div>
                            <div class="flex">
                                <span class="w-24 font-bold text-gray-900">Role</span>
                                <span class="mr-2">:</span>
                                <span id="modalRole">Employee</span>
                            </div>
                            <div class="flex">
                                <span class="w-24 font-bold text-gray-900">E-mail</span>
                                <span class="mr-2">:</span>
                                <span id="modalEmail" class="truncate">email@example.com</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Message -->
                    <div class="mt-auto mb-6 px-6 text-center w-full">
                        <div class="bg-blue-600 text-white text-[10px] py-2 px-4 rounded-lg shadow-md">
                            <p>If found, please return to Information Technology Services.</p>
                        </div>
                    </div>
                </div>

                <!-- Close Button (Outside Card) -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeCardModal()">
                        Close
                    </button>
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="printCard()">
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCardModal(user) {
            document.getElementById('modalName').textContent = user.name;
            document.getElementById('modalRole').textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
            document.getElementById('modalCode').textContent = user.employee_code || 'N/A';
            document.getElementById('modalEmail').textContent = user.email;
            
            // Generate QR Code URL
            // Using api.qrserver.com for simplicity. 
            // Format: https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Example
            const qrData = user.employee_code || user.id;
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qrData)}`;
            document.getElementById('modalQrCode').src = qrUrl;

            document.getElementById('cardModal').classList.remove('hidden');
        }

        function closeCardModal() {
            document.getElementById('cardModal').classList.add('hidden');
        }

        function printCard() {
            const printContent = document.querySelector('#cardModal .relative').outerHTML;
            const win = window.open('', '', 'height=700,width=500');
            win.document.write('<html><head><title>Print ID Card</title>');
            win.document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
            win.document.write('<style>');
            win.document.write(`
                @media print {
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                    .bg-gradient-to-br { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                }
            `);
            win.document.write('</style>');
            win.document.write('</head><body class="flex items-center justify-center min-h-screen">');
            win.document.write(printContent);
            win.document.write('</body></html>');
            win.document.close();

            // Wait for Tailwind to load and apply styles
            win.onload = function() {
                setTimeout(function() {
                    win.print();
                    win.close();
                }, 1000); // 1 second delay to ensure styles are applied
            };
        }
    </script>
</x-app-layout>
