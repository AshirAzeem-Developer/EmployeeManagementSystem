<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-primary-600 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white">New Employee Details</h3>
                        <p class="text-primary-100 text-sm">Enter the information to create a new employee account.</p>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- Personal Information --}}
                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">Personal Details</h4>
                                
                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required placeholder="e.g. John Doe">
                                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required placeholder="john@example.com">
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Account Details --}}
                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">Account Settings</h4>

                                {{-- Role --}}
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">System Role</label>
                                    <select name="role" id="role" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">
                                        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Job Details --}}
                            <div class="space-y-6 md:col-span-2">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">Job Assignment</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    {{-- Department --}}
                                    <div>
                                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                        <select name="department_id" id="department_id" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Shift --}}
                                    <div>
                                        <label for="shift_id" class="block text-sm font-medium text-gray-700 mb-1">Work Shift</label>
                                        <select name="shift_id" id="shift_id" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">
                                            <option value="">Select Shift</option>
                                            @foreach($shifts as $shift)
                                                <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('shift_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Security --}}
                            <div class="space-y-6 md:col-span-2">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">Security</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    {{-- Password --}}
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" name="password" id="password" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required placeholder="••••••••">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-600 to-indigo-600 text-white font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                                Create Employee
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
