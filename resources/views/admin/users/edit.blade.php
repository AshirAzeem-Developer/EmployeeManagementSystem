<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form id="update-form" action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-primary-600 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Employee Information</h3>
                        <p class="text-primary-100 text-sm">Update employee details and account settings.</p>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- Personal Information --}}
                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">Personal Details</h4>
                                
                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" required>
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Account Details --}}
                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">Account Settings</h4>

                                {{-- Employee Code --}}
                                <div>
                                    <label for="employee_code" class="block text-sm font-medium text-gray-700 mb-1">Employee Code</label>
                                    <input type="text" name="employee_code" id="employee_code" value="{{ old('employee_code', $user->employee_code) }}" class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 shadow-sm cursor-not-allowed sm:text-sm" readonly>
                                    @error('employee_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Role --}}
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">System Role</label>
                                    <select name="role" id="role" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">
                                        <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
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
                                                <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
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
                                                <option value="{{ $shift->id }}" {{ old('shift_id', $user->shift_id) == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('shift_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Password Change --}}
                            <div class="space-y-6 md:col-span-2">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">Security (Optional)</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    {{-- Password --}}
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                        <input type="password" name="password" id="password" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" placeholder="Leave blank to keep current">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="input-enhanced w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" placeholder="Confirm new password">
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
                                Update Employee
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Danger Zone --}}
            <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-2xl border border-red-100">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-red-800">Danger Zone</h3>
                            <p class="text-red-600 text-sm mt-1">Once you delete an employee, there is no going back. Please be certain.</p>
                        </div>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-white border border-red-200 text-red-600 font-bold hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm">
                                Delete Employee
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
