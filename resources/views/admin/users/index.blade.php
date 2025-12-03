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
                <div class="relative bg-gradient-to-br from-blue-900 to-indigo-900 text-white p-6 overflow-hidden h-[500px] flex flex-col items-center justify-between">
                    
                    <!-- Background Shapes -->
                    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                        <div class="absolute top-[-50px] left-[-50px] w-32 h-32 bg-blue-500 rounded-full mix-blend-overlay filter blur-xl opacity-50"></div>
                        <div class="absolute bottom-[-50px] right-[-50px] w-40 h-40 bg-purple-500 rounded-full mix-blend-overlay filter blur-xl opacity-50"></div>
                        <div class="absolute top-1/2 right-0 w-24 h-24 bg-indigo-500 rounded-full mix-blend-overlay filter blur-xl opacity-30"></div>
                    </div>

                    <div class="relative z-10 w-full flex flex-col items-center h-full">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h3 class="text-xs font-bold tracking-widest uppercase opacity-80">ID CARD TEMPLATE</h3>
                        </div>

                        <!-- QR Code Section (Replaces Avatar) -->
                        <div class="bg-white p-2 rounded-lg shadow-lg mb-4 w-48 h-48 flex items-center justify-center">
                            <img id="modalQrCode" src="" alt="QR Code" class="w-full h-full object-contain">
                        </div>

                        <!-- Employee Details -->
                        <div class="text-center w-full">
                            <h2 id="modalName" class="text-2xl font-bold uppercase tracking-wide mb-1">Name Surname</h2>
                            <p id="modalRole" class="text-pink-500 font-bold tracking-widest uppercase text-sm mb-4">Position</p>
                            
                            <div class="space-y-1 text-xs opacity-90">
                                <p>ID: <span id="modalCode" class="font-mono">123 456 789</span></p>
                                <p>DEPT: <span id="modalDept">Department</span></p>
                            </div>
                        </div>

                        <!-- Barcode / Footer -->
                        <div class="mt-auto w-full text-center">
                            <!-- Fake Barcode Visual -->
                            <div class="h-8 w-2/3 mx-auto bg-white mb-2 flex items-center justify-center overflow-hidden">
                                <div class="w-full h-full flex justify-between px-1">
                                    @for($i=0; $i<30; $i++)
                                        <div class="w-[2px] h-full bg-black"></div>
                                        <div class="w-[1px] h-full bg-transparent"></div>
                                    @endfor
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-2 text-xs font-bold">
                                <div class="w-6 h-6 border-2 border-white rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <span>YOUR LOGO</span>
                            </div>
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
            document.getElementById('modalRole').textContent = user.role; // Or user.designation if available
            document.getElementById('modalCode').textContent = user.employee_code || 'N/A';
            document.getElementById('modalDept').textContent = user.department ? user.department.name : 'N/A';
            
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
            // Simple print logic - could be improved to print only the card area
            const printContent = document.querySelector('#cardModal .relative').outerHTML;
            const win = window.open('', '', 'height=700,width=500');
            win.document.write('<html><head><title>Print ID Card</title>');
            // Include Tailwind CDN for styling in print window or copy styles
            win.document.write('<script src="https://cdn.tailwindcss.com"><\/script>'); 
            win.document.write('</head><body class="flex items-center justify-center min-h-screen">');
            win.document.write(printContent);
            win.document.write('</body></html>');
            win.document.close();
            win.print();
        }
    </script>
</x-app-layout>
